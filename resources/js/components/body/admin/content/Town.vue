<template>
    <!-- Modal d'ajout -->
    <div class="modal fade mt-5" id="addTownModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-dark">
                    <h5 class="modal-title text-light">Ajouter une ville</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="mb-3">
                            <label class="form-label">Nom</label>
                            <input type="text" class="form-control" v-model="name">
                            <label :class="labelNameLog">{{ nameLog }}</label>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Code</label>
                            <input type="text" class="form-control" v-model="code">
                            <label :class="labelCodeLog">{{ codeLog }}</label>
                        </div>
                    </form>
                </div>
                <div class="modal-footer bg-light">
                    <button class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                    <button class="btn btn-dark" @click="saveTown" :disabled="!isFormValid">Ajouter</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de mise à jour -->
    <div class="modal fade mt-5" id="updatedTownModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-warning text-dark">
                    <h5 class="modal-title">Modifier une ville</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="mb-3">
                            <label class="form-label">Nom</label>
                            <input type="text" class="form-control" v-model="name">
                            <label :class="labelNameLog">{{ nameLog }}</label>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Code</label>
                            <input type="text" class="form-control" v-model="code">
                            <label :class="labelCodeLog">{{ codeLog }}</label>
                        </div>
                    </form>
                </div>
                <div class="modal-footer bg-light">
                    <button class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                    <button class="btn btn-warning" @click="updateTown"
                        :disabled="!isFormValid">Modifier</button>
                </div>
            </div>
        </div>
    </div>

    <div class="accordion-item">
        <h2 class="accordion-header" id="headingTwo">
            <button class="accordion-button collapsed bg-primary text-light" type="button" data-bs-toggle="collapse" data-bs-target="#collapse2" aria-expanded="false" aria-controls="collapse2">
                SECTION VILLE 
            </button> 
        </h2>
        <div id="collapse2" class="accordion-collapse collapse" aria-labelledby="headingTwo"data-bs-parent="#accordionExample">
            <div class="accordion-body bg-dark text-white">

                <p>Vous pouvez enregistrer la ville, modifier ou supprimer</p>

                <!-- Bouton Ajouter -->
                <div class="row mt-3 mb-3">
                    <div class="col-8">
                        <h2>Liste Ville</h2>
                    </div>
                    <div class="col-4">
                        <button class="btn btn-dark border mt-3 me-2" @click="addTown">+ Ajouter</button>
                    </div>
                </div>

                <!-- Tableau des villes -->
                <table class="table table-primary table-bordered border-dark table-hover">
                    <thead class="table-primary border-dark">
                        <tr>
                            <th>#</th>
                            <th>Nom</th>
                            <th>Code</th>
                            <th>Créé par</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(town, index) in Towns" :key="town.id">
                            <td>{{ index + 1 }}</td>
                            <td>{{ town.name }}</td>
                            <td>{{ town.code }}</td>
                            <td>{{ town.created_by }}</td>
                            <td>
                                <button class="btn btn-sm btn-warning me-1" @click="townUpdated(town)">
                                    <i class="bi bi-pencil-square"></i>
                                </button>
                                <button class="btn btn-sm btn-danger" @click="deletedTown(town.id)">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </td>
                        </tr>
                        <tr v-if="Towns.length === 0">
                            <td colspan="5" class="text-center text-danger">Aucune ville trouvée</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import { Modal } from 'bootstrap'
import axios from 'axios'
import Swal from 'sweetalert2'

// Prop accordéon
defineProps({
    parentAccordionId: { type: String, required: true }
})
const collapseId = computed(() => parentAccordionId + '-collapse-town')
const headingId = computed(() => parentAccordionId + '-heading-town')

// Données
const name = ref('')
const code = ref('')
const Towns = ref([])
let townId = 0

// Computed validations
const nameLog = computed(() => {
    if (!name.value) return "Aucun nom pour le moment"
    if (name.value.length < 3) return "Le nom doit contenir au moins 3 caractères"
    return "Nom valide ✅"
})
const labelNameLog = computed(() => name.value.length >= 3 ? 'text-success' : 'text-danger')

const codeLog = computed(() => {
    if (!code.value) return "Aucun code pour le moment"
    if (isNaN(Number(code.value))) return "Le code doit être numérique"
    return `Code valide (${code.value}) ✅`
})
const labelCodeLog = computed(() => isNaN(Number(code.value)) || !code.value ? 'text-danger' : 'text-success')

const isFormValid = computed(() =>
    name.value.length >= 3 &&
    code.value !== '' &&
    !isNaN(Number(code.value))
)

// CRUD
async function getTowns() {
    try {
        const response = await axios.get('/api/town', { headers: { Accept: 'application/json' } })
        Towns.value = response.data
    } catch (error) { console.error(error) }
}
onMounted(() => getTowns())

function addTown() {
    resetForm()
    new Modal(document.getElementById('addTownModal')).show()
}

function townUpdated(town) {
    townId = town.id
    name.value = town.name
    code.value = town.code
    new Modal(document.getElementById('updatedTownModal')).show()
}

function resetForm() {
    name.value = ''
    code.value = ''
}

// Save
async function saveTown() {
    try {
        const response = await axios.post('/api/town', { name: name.value, code: code.value }, { headers: { Accept: 'application/json' } })
        if (response.data.status) {
            Swal.fire({ toast: true, position: 'top-end', icon: 'success', title: response.data.message, showConfirmButton: false, timer: 3000 })
            resetForm()
            getTowns()
            Modal.getInstance(document.getElementById('addTownModal')).hide()
        }
    } catch (error) { console.error(error) }
}

// Update
async function updateTown() {
    try {
        const response = await axios.put('/api/town/' + townId, { name: name.value, code: code.value }, { headers: { Accept: 'application/json' } })
        if (response.data.status) {
            Swal.fire({ toast: true, position: 'top-end', icon: 'success', title: response.data.message, showConfirmButton: false, timer: 3000 })
            getTowns()
            Modal.getInstance(document.getElementById('updatedTownModal')).hide()
        }
    } catch (error) { console.error(error) }
}

// Delete
async function deletedTown(id) {
    const result = await Swal.fire({
        title: 'Es-tu sûr ?',
        text: "Cette action est irréversible !",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Oui, supprimer',
        cancelButtonText: 'Annuler'
    })
    if (result.isConfirmed) {
        try {
            await axios.delete('/api/town/' + id, { headers: { Accept: 'application/json' } })
            Swal.fire({ toast: true, position: 'top-end', icon: 'success', title: 'Ville supprimée ✅', showConfirmButton: false, timer: 3000 })
            getTowns()
        } catch (error) { console.error(error) }
    }
}
</script>
