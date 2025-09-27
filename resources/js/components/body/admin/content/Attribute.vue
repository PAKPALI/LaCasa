<template>
    <!-- Loader global avant table -->
    <div v-if="loadingTable" class="text-center my-5">
        <div class="spinner-border text-primary" role="status">
            <span class="visually-hidden">Chargement...</span>
        </div>
    </div>

    <!-- Modals -->

    <!-- Ajouter -->
    <div class="modal fade mt-5" id="addAttributeModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-dark">
                    <h5 class="modal-title text-light">Ajouter un attribut</h5>
                    <button type="button" class="btn-close bg-light" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="mb-3">
                            <label class="form-label">Nom</label>
                            <input type="text" class="form-control" v-model="name">
                            <label :class="labelNameLog">{{ nameLog }}</label>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Type de pub</label>
                            <v-select
                                v-model="pubTypeId"
                                :options="PubTypes"
                                :reduce="p => p.id"
                                :get-option-label="option => option.category ? `${option.name} (${option.category.name})` : option.name"
                                placeholder="Rechercher un type de pub..."
                                :filterable="true"
                                :loading="loadingPubTypes"
                            />
                            <label :class="labelPubTypeLog">{{ pubTypeLog }}</label>
                        </div>
                    </form>
                </div>
                <div class="modal-footer bg-light">
                    <button class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                    <button class="btn btn-dark" @click="saveAttribute"
                        :disabled="!isFormValid || loadingButton === 'save'">
                        <span v-if="loadingButton === 'save'" class="spinner-border spinner-border-sm text-light"></span>
                        <span v-if="loadingButton !== 'save'">Ajouter</span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modifier -->
    <div class="modal fade mt-5" id="updatedAttributeModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-warning text-dark">
                    <h5 class="modal-title">Modifier un attribut</h5>
                    <button type="button" class="btn-close bg-light" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="mb-3">
                            <label class="form-label">Nom</label>
                            <input type="text" class="form-control" v-model="name">
                            <label :class="labelNameLog">{{ nameLog }}</label>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Type de pub</label>
                            <v-select
                                v-model="pubTypeId"
                                :options="PubTypes"
                                :reduce="p => p.id"
                                :get-option-label="option => option.category ? `${option.name} (${option.category.name})` : option.name"
                                placeholder="Rechercher un type de pub..."
                                :filterable="true"
                                :loading="loadingPubTypes"
                            />
                            <label :class="labelPubTypeLog">{{ pubTypeLog }}</label>
                        </div>
                    </form>
                </div>
                <div class="modal-footer bg-light">
                    <button class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                    <button class="btn btn-warning" @click="updateAttribute"
                        :disabled="!isFormValid || loadingButton === 'update'">
                        <span v-if="loadingButton === 'update'" class="spinner-border spinner-border-sm text-dark"></span>
                        <span v-if="loadingButton !== 'update'">Modifier</span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Visualiser -->
    <div class="modal fade mt-5" id="viewAttributeModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-info">
                    <h5 class="modal-title text-dark">Détails de l'attribut</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p><strong>Nom :</strong> {{ selectedAttribute ? selectedAttribute.name : '' }}</p>
                    <p><strong>Type de pub :</strong> {{ selectedAttribute && selectedAttribute.pub_type ?selectedAttribute.pub_type.name : '—' }}</p>
                    <p v-if="selectedAttribute && selectedAttribute.pub_type && selectedAttribute.pub_type.category">
                        <strong>Catégorie :</strong> {{ selectedAttribute.pub_type.category.name }}
                    </p>
                    <p
                        v-if="selectedAttribute && selectedAttribute.pub_type && selectedAttribute.pub_type.category && selectedAttribute.pub_type.category.country">
                        <strong>Pays :</strong> {{ selectedAttribute.pub_type.category.country.name }}
                    </p>
                </div>
                <div class="modal-footer bg-light">
                    <button class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Accordéon attributs -->
    <div class="accordion-item mb-4">
        <h2 class="accordion-header" id="headingAttribute">
            <button class="accordion-button bg-primary text-light" type="button" data-bs-toggle="collapse"
                data-bs-target="#collapseAttribute" aria-expanded="true" aria-controls="collapseAttribute">
                SECTION ATTRIBUTS
            </button>
        </h2>
        <div id="collapseAttribute" class="accordion-collapse collapse show" aria-labelledby="headingAttribute"
            data-bs-parent="#accordionExample">
            <div class="accordion-body bg-dark text-white">
                <p>Vous pouvez enregistrer, modifier, visualiser ou supprimer un attribut (WCDI,WCDE,CI,CE,Terrasse,Garage).</p>

                <!-- Ajouter -->
                <div class="row mt-3 mb-3">
                    <div class="col-8">
                        <h2>Liste Attributs</h2>
                    </div>
                    <div class="col-4">
                        <button class="btn btn-dark border mt-3 me-2" @click="addAttribute">+ Ajouter</button>
                    </div>
                </div>

                <!-- Search -->
                <div class="mb-3">
                    <input type="text" class="form-control"
                        placeholder="Rechercher un attribut, type de pub, catégorie ou pays..." v-model="searchQuery">
                </div>

                <!-- Tableau -->
                <table v-if="!loadingTable" class="table table-primary table-bordered border-dark table-hover">
                    <thead class="table-primary border-dark">
                        <tr>
                            <th>#</th>
                            <th>Nom</th>
                            <th>Type de pub</th>
                            <th>Catégorie</th>
                            <th>Pays</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(attribute, index) in paginatedAttributes" :key="attribute.id">
                            <td>{{ (currentPage - 1) * perPage + index + 1 }}</td>
                            <td>{{ attribute.name }}</td>
                            <td>{{ attribute.pub_type ? attribute.pub_type.name : '—' }}</td>
                            <td>{{ attribute.pub_type?.category ? attribute.pub_type.category.name : '—' }}</td>
                            <td>{{ attribute.pub_type?.category?.country ? attribute.pub_type.category.country.name : '—' }}</td>
                            <td>
                                <button class="btn btn-sm btn-info me-1" @click="viewAttribute(attribute)">
                                    <span><i class="bi bi-eye"></i></span>
                                </button>
                                <button class="btn btn-sm btn-warning me-1" @click="attributeUpdated(attribute)"
                                    :disabled="loadingButton === 'update'">
                                    <span v-if="loadingButton === 'update'"
                                        class="spinner-border spinner-border-sm text-dark"></span>
                                    <span v-if="loadingButton !== 'update'"><i class="bi bi-pencil-square"></i></span>
                                </button>
                                <button class="btn btn-sm btn-danger" @click="deletedAttribute(attribute.id)"
                                    :disabled="loadingButton === attribute.id">
                                    <span v-if="loadingButton === attribute.id"
                                        class="spinner-border spinner-border-sm text-light"></span>
                                    <span v-if="loadingButton !== attribute.id"><i class="bi bi-trash"></i></span>
                                </button>
                            </td>
                        </tr>
                        <tr v-if="filteredAttributes.length === 0">
                            <td colspan="6" class="text-center text-danger">Aucun attribut trouvé</td>
                        </tr>
                    </tbody>
                </table>

                <!-- Pagination -->
                <div v-if="!loadingTable" class="d-flex justify-content-between align-items-center mt-2">
                    <button class="btn btn-sm btn-dark" @click="prevPage" :disabled="currentPage === 1">Précédent</button>
                    <span>Page {{ currentPage }} / {{ totalPages }}</span>
                    <button class="btn btn-sm btn-dark" @click="nextPage"
                        :disabled="currentPage === totalPages">Suivant</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
    import { ref, computed, onMounted } from 'vue'
    import { Modal } from 'bootstrap'
    import axios from 'axios'
    import Swal from 'sweetalert2'
    import vSelect from "vue-select"
    import "vue-select/dist/vue-select.css"

    // Données
    const name = ref('')
    const pubTypeId = ref('')
    const Attributes = ref([])
    const PubTypes = ref([])
    let attributeId = 0
    const selectedAttribute = ref(null)
    const searchQuery = ref('')
    const currentPage = ref(1)
    const perPage = ref(5)
    const loadingButton = ref('')
    const loadingTable = ref(true)
    const loadingPubTypes = ref(false)

    // Validations
    const nameLog = computed(() => !name.value ? "Aucun nom" : name.value.length < 3 ? "Nom trop court" : "Nom valide ✅")
    const labelNameLog = computed(() => name.value.length >= 3 ? 'text-success' : 'text-danger')

    const pubTypeLog = computed(() => {
    if (!pubTypeId.value) return "Aucun type sélectionné"
    const t = PubTypes.value.find(x => x.id === Number(pubTypeId.value))
    return t ? `Type sélectionné : ${t.name}${t.category ? ` (${t.category.name})` : ''}` : 'Type sélectionné'
})


    const labelPubTypeLog = computed(() => pubTypeId.value ? 'text-success' : 'text-danger')

    const isFormValid = computed(() => name.value.length >= 3 && pubTypeId.value !== '' && Number(pubTypeId.value) > 0)

    // Filter + Pagination
    const filteredAttributes = computed(() => {
        if (!searchQuery.value) return Attributes.value
        const q = searchQuery.value.toLowerCase()
        return Attributes.value.filter(a => {
            const pt = a.pubType
            const cat = pt ? pt.category : null
            const country = cat ? cat.country : null
            return a.name.toLowerCase().includes(q) ||
                (pt && pt.name.toLowerCase().includes(q)) ||
                (cat && cat.name.toLowerCase().includes(q)) ||
                (country && country.name.toLowerCase().includes(q))
        })
    })

    const totalPages = computed(() => Math.max(1, Math.ceil(filteredAttributes.value.length / perPage.value)))
    const paginatedAttributes = computed(() => {
        const start = (currentPage.value - 1) * perPage.value
        return filteredAttributes.value.slice(start, start + perPage.value)
    })
    function nextPage() { if (currentPage.value < totalPages.value) currentPage.value++ }
    function prevPage() { if (currentPage.value > 1) currentPage.value-- }

    // CRUD
    async function getPubTypes() {
        loadingPubTypes.value = true
        try {
            const response = await axios.get('/api/pub-type')
            PubTypes.value = response.data
        } catch (e) {
            Swal.fire({ icon: 'error', title: 'Erreur API', text: 'Impossible de récupérer les types de pub' })
        } finally { loadingPubTypes.value = false }
    }

    async function getAttributes() {
        loadingTable.value = true
        try {
            const response = await axios.get('/api/attribute', { headers: { Accept: 'application/json' } })
            console.log(response.data)
            Attributes.value = response.data
        } catch (e) { console.error(e) }
        loadingTable.value = false
    }

    function resetForm() { name.value = ''; pubTypeId.value = '' }

    function addAttribute() { resetForm(); getPubTypes(); new Modal(document.getElementById('addAttributeModal')).show() }

    function attributeUpdated(attr) {
        attributeId = attr.id
        name.value = attr.name
        pubTypeId.value = attr.pub_type_id ?? (attr.pubType ? attr.pubType.id : '')
        getPubTypes()
        new Modal(document.getElementById('updatedAttributeModal')).show()
    }

    async function saveAttribute() {
        loadingButton.value = 'save'
        try {
            // const response = await axios.post('/api/attribute', {name, pub_type_id:pubTypeId})
            const response = await axios.post('/api/attribute', {
                name: name.value,
                pub_type_id: pubTypeId.value
            })

            if (response.data.status) {
                Swal.fire({ toast: true, position: 'top-end', icon: 'success', title: response.data.message, showConfirmButton: false, timer: 3000 })
                resetForm()
                await getAttributes()
                Modal.getInstance(document.getElementById('addAttributeModal')).hide()
            } else {
                Swal.fire({ icon: 'error', title: 'Erreur', text: response.data.message })
            }
        } catch (e) {
            Swal.fire({ icon: 'error', title: 'Erreur Serveur', text: e.response?.data?.message || 'Erreur' })
        }
        loadingButton.value = ''
    }

    async function updateAttribute() {
        loadingButton.value = 'update'
        try {
            const response = await axios.put('/api/attribute/' + attributeId, { name: name.value, pub_type_id: pubTypeId.value })
            if (response.data.status) {
                Swal.fire({ toast: true, position: 'top-end', icon: 'success', title: response.data.message, showConfirmButton: false, timer: 3000 })
                await getAttributes()
                Modal.getInstance(document.getElementById('updatedAttributeModal')).hide()
            } else {
                Swal.fire({ icon: 'error', title: 'Erreur', text: response.data.message })
            }
        } catch (e) { Swal.fire({ icon: 'error', title: 'Erreur Serveur', text: e.response?.data?.message || 'Erreur' }) }
        loadingButton.value = ''
    }

    async function deletedAttribute(id) {
        const result = await Swal.fire({ title: 'Es-tu sûr ?', text: "Action irréversible !", icon: 'warning', confirmButtonColor: '#d33', cancelButtonColor: '#3085d6', showCancelButton: true, confirmButtonText: 'Oui, supprimer', cancelButtonText: 'Annuler' })
        if (result.isConfirmed) {
            loadingButton.value = id
            try {
                const response = await axios.delete('/api/attribute/' + id)
                if (response.data && response.data.status === false) {
                    Swal.fire({ toast: true, position: 'top-end', icon: 'error', title: response.data.message, showConfirmButton: false, timer: 3000 })
                } else {
                    Swal.fire({ toast: true, position: 'top-end', icon: 'success', title: response.data.message || 'Attribut supprimé', showConfirmButton: false, timer: 3000 })
                    await getAttributes()
                }
            } catch (e) { Swal.fire({ icon: 'error', title: 'Erreur Serveur', text: e.response?.data?.message || 'Erreur' }) }
            loadingButton.value = ''
        }
    }

    function viewAttribute(attr) { selectedAttribute.value = attr; new Modal(document.getElementById('viewAttributeModal')).show() }

    onMounted(async () => {
        await getPubTypes()
        await getAttributes()
    })
</script>
