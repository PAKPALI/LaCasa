<template>
  <div class="modal fade show d-block" tabindex="-1">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
      <div class="modal-content">
        <div class="modal-header bg-primary text-white">
          <h5 class="modal-title">Modifier la publication</h5>
          <button type="button" class="btn-close" @click="$emit('close')"></button>
        </div>
        <div class="modal-body">
          <form @submit.prevent="submitPublication">
            <!-- Ici tu gardes tout ton formulaire existant -->
            <!-- Les champs v-model sont exactement les mêmes -->
          </form>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, watch, onMounted } from 'vue'
import axios from 'axios'
import vSelect from "vue-select"
import "vue-select/dist/vue-select.css"
import Swal from 'sweetalert2'

const props = defineProps({
  publicationId: Number
})
const emit = defineEmits(['close', 'updated'])

// Refs pour les données du formulaire
const form = ref({
  title: '',
  price: '',
  offer_type: '',
  category_id: null,
  surface: '',
  bathroom: '',
  phone1: '',
  phone2: '',
  description: ''
})

// Charger la publication quand le composant est monté ou quand l'id change
const loadPublication = async () => {
  try {
    const { data } = await axios.get(`/api/publications/${props.publicationId}`)
    form.value = { ...data } // Préremplit le formulaire avec les données existantes
  } catch (error) {
    console.error('Erreur lors du chargement de la publication :', error)
    Swal.fire('Erreur', 'Impossible de charger la publication.', 'error')
    emit('close')
  }
}

watch(() => props.publicationId, () => {
  if (props.publicationId) loadPublication()
})

onMounted(() => {
  if (props.publicationId) loadPublication()
})

// Soumission du formulaire
const submitPublication = async () => {
  try {
    const { data } = await axios.put(`/api/publication/${props.publicationId}`, form.value)
    Swal.fire('Succès', 'Publication mise à jour avec succès.', 'success')
    emit('updated', data) // renvoie la nouvelle version au parent
    emit('close')
  } catch (error) {
    console.error('Erreur lors de la mise à jour :', error)
    Swal.fire('Erreur', 'Impossible de mettre à jour la publication.', 'error')
  }
}
</script>

