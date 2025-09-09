<template>
  <!-- addModal -->
  <div class="modal fade mt-5" id="addCountryModal" tabindex="-1" aria-labelledby="addCountryModal" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header bg-dark">
          <h5 class="modal-title text-light" id="addProductModalLabel">Ajouter un pays</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
        </div>
        <div class="modal-body">
          <!-- Formulaire ici -->
          <form>
            <div class="mb-3">
              <label for="nomProduit" class="form-label">Nom</label>
              <input type="text" class="form-control" id="nomProduit" v-model="name" @keyup="validateName">
              <label :class="labelNameLog">{{ nameLog }}</label>
            </div>
            <div class="mb-3">
              <label for="quantite" class="form-label">Acronyme</label>
              <input type="text" class="form-control" id="acronym" v-model="acronym" @keyup="validateAcronym">
              <label :class="labelAcronymLog">{{ acronymLog }}</label>
            </div>
            <div class="mb-3">
              <label for="code" class="form-label">Code</label>
              <input type="text" class="form-control" id="code" v-model="code" @keyup="validateCode">
              <label :class="labelCodeLog">{{ codeLog }}</label>
            </div>
          </form>
        </div>
        <div class="modal-footer bg-light">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
          <button type="button" class="btn btn-dark" @click="saveCountry" :disabled="!isFormValid">Ajouter</button>
        </div>
      </div>
    </div>
  </div>

  <!-- updateModal --><!-- updateModal -->
  <div class="modal fade" id="updatedCountryModal" tabindex="-1" aria-labelledby="updateCountryModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header bg-warning text-dark">
          <h5 class="modal-title" id="updateCountryModalLabel">Modifier un pays</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
        </div>
        <div class="modal-body">
          <form>
            <div class="mb-3">
              <label class="form-label">Nom</label>
              <input type="text" class="form-control" v-model="name" @keyup="validateName">
              <label :class="labelNameLog">{{ visibleName ? nameLog : '' }}</label>
            </div>
            <div class="mb-3">
              <label class="form-label">Acronyme</label>
              <input type="text" class="form-control" v-model="acronym" @keyup="validateAcronym">
              <label :class="labelAcronymLog">{{ acronymLog }}</label>
            </div>
            <div class="mb-3">
              <label class="form-label">Code</label>
              <input type="text" class="form-control" v-model="code" @keyup="validateCode">
              <label :class="labelCodeLog">{{ codeLog }}</label>
            </div>
          </form>
        </div>
        <div class="modal-footer bg-light">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
          <button type="button" class="btn btn-warning" @click="updateCountry">Modifier</button>
        </div>
      </div>
    </div>
  </div>
  <!-- End updateModal -->


  <div class="site-section mt-5">
  <div class="container">

    <div class="row justify-content-center mt-5">
      <div class="col-md-7 text-center">
        <div class="site-section-title">
          <h2>Section admin</h2>
        </div>
        <p>Cette section permet d'interagir avec les outils dont le site aura besoin pour se developper</p>
      </div>
    </div>

    <div class="row justify-content-center" data-aos="fade" data-aos-delay="100">
      <div class="col-md-12">
        
        <!-- Bootstrap Accordion -->
        <div class="accordion" id="accordionExample">

          <!-- country -->
          <div class="accordion-item">
            <h2 class="accordion-header" id="headingOne">
              <button class="accordion-button bg-dark text-light" type="button" data-bs-toggle="collapse" data-bs-target="#collapse1" aria-expanded="true" aria-controls="collapse1">
                SECTION PAYS
              </button>
            </h2>
            <div id="collapse1" class="accordion-collapse collapse show" aria-labelledby="headingOne" data-bs-parent="#accordionExample">
              <div class="accordion-body text-white">
                <p class="bg-dark text-white">Vous pouvez enregistrez le pays, modifier ou supprimer</p>

                <!-- add product -->
                <div class="row mt-3">
                  <div class="col-8 border-end">
                    <h2>Liste Pays</h2>
                  </div>
                  <div class="col-4">
                    <button class="btn btn-dark mt-3 me-2" @click="addCountry">+ Ajouter</button>
                  </div>
                </div>

                <!-- tableau des pays -->
                <div class="row mt-3 border-top pt-3 mb-3">
                  <table class="table table-bordered table-hover">
                    <thead class="table-dark">
                      <tr>
                        <th>#</th>
                        <th>Nom</th>
                        <th>Acronym</th>
                        <th>Code</th>
                        <th>Actions</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr v-for="(country, index) in Countries" :key="country.id">
                        <td>{{ index + 1 }}</td>
                        <td>{{ country.name }}</td>
                        <td>{{ country.acronym }}</td>
                        <td>{{ country.code }}</td>
                        <td>
                          <button class="btn btn-sm btn-warning me-1" @click="countryUpdated(country)">
                            <i class="bi bi-pencil-square"></i>
                          </button>
                          <button class="btn btn-sm btn-danger" @click="deletedCountry(country.id)">
                            <i class="bi bi-trash"></i>
                          </button>
                        </td>
                      </tr>
                      <tr v-if="Countries.length === 0">
                        <td colspan="6" class="text-center text-danger">Aucun pays trouvé</td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>

          <!-- town -->
          <div class="accordion-item">
            <h2 class="accordion-header" id="headingTwo">
              <button class="accordion-button collapsed bg-dark text-light" type="button" data-bs-toggle="collapse" data-bs-target="#collapse2" aria-expanded="false" aria-controls="collapse2">
                SECTION VILLE
              </button>
            </h2>
            <div id="collapse2" class="accordion-collapse collapse" aria-labelledby="headingTwo" data-bs-parent="#accordionExample">
              <div class="accordion-body">
                <p class="bg-dark text-white">Vous pouvez enregistrez la ville, modifier ou supprimer</p>

                <!-- add product -->
                <div class="row mt-3">
                  <div class="col-8 border-end">
                    <h2>Liste Ville</h2>
                  </div>
                  <div class="col-4">
                    <button class="btn btn-dark mt-3 me-2" @click="addProduct">+ Ajouter</button>
                  </div>
                </div>

                <!-- tableau des villes -->
                <div class="row mt-3 border-top pt-3">
                  <table class="table table-bordered table-hover">
                    <thead class="table-dark">
                      <tr>
                        <th>#</th>
                        <th>Nom</th>
                        <th>Code</th>
                        <th>Créer par</th>
                        <th>Actions</th>
                      </tr>
                    </thead>
                    <tbody>
                      <!-- <tr v-for="(product, index) in products" :key="product.id">
                        <td>{{ index + 1 }}</td>
                        <td>{{ product.name }}</td>
                        <td>{{ product.qty }}</td>
                        <td>{{ product.limit }}</td>
                        <td>
                          <span class="badge bg-success" v-if="product.status == 1">Disponible</span>
                          <span class="badge bg-danger" v-else>Indisponible</span>
                        </td>
                        <td>
                          <button class="btn btn-sm btn-warning me-1" @click="productUpdated(product)">
                            <i class="bi bi-pencil-square"></i>
                          </button>
                          <button class="btn btn-sm btn-danger" @click="deletedProduct(product.id)">
                            <i class="bi bi-trash"></i>
                          </button>
                        </td>
                      </tr> -->
                      <!-- <tr v-if="products.length === 0">
                        <td colspan="6" class="text-center text-danger">Aucune ville trouvée</td>
                      </tr> -->
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>

        </div>
        <!-- End Bootstrap Accordion -->

      </div>
    </div>
  </div>
</div>

</template>

<script setup>
// Importation des dépendances
  import { Modal } from 'bootstrap'
  import axios from 'axios'
  import { ref, onMounted, computed } from 'vue'

  const name = ref('')
  const acronym = ref(0)
  const code = ref(0)

  // const nameLog = ref('Aucun nom pour le moment')
  // const labelNameLog = ref('text-danger')
  let nameLenght = 0

  const acronymLog = ref('Aucun acronym pour le moment')
  const labelAcronymLog = ref('text-danger')
  let acronymLenght = acronym.value.length

  const codeLog = ref('Aucun code pour le moment')
  const labelCodeLog = ref('text-danger')
  
  // call modal add
  function addCountry() {
    resetForm()
    // Ouvre le modal pour ajouter un produit
    const modal = new Modal(document.getElementById('addCountryModal'))
    modal.show()
  }
  
  const visibleName = ref(false)
  const visibleQte = ref(false)
  const visibleLimit = ref(false)
  const visibleStatus = ref(false)
  
  // call modal update
  // function productUpdated(product) {
  //   // Ouvre le modal pour modifier un produit
  //   productId = product.id
  //   name.value = product.name
  //   quantity.value = product.qty
  //   limite.value = product.limit
  //   status.value = product.status
  //   const modal = new Modal(document.getElementById('updatedProductModal'))
  //   modal.show()
  // }

  const nameLog = computed(() => {
    if (name.value.length === 0) return "Aucun nom pour le moment"
    if (name.value.length < 3) return "Le nom doit contenir au moins 3 caractères"
    return "Nom valide ✅"
  })

  const labelNameLog = computed(() => {
    return name.value.length >= 3 ? 'text-success' : 'text-danger'
  })

  function validateAcronym(event) {
    acronym.value = event.target.value
    acronymLenght = acronym.value.length 
    acronymLog.value = acronymLenght < 2 ? 'Acronyme trop court' : 'Acronyme valide (' + acronym.value + ')'
    labelAcronymLog.value = acronym.value.length < 2 ? 'text-danger' : 'text-success'
    validateForm()
  }

  function validateCode(event) {
    code.value = event.target.value
    acronymLenght = acronym.value.length 
    codeLog.value = isNaN(code.value) ? 'Le code doit être numérique' : 'Code valide (' + code.value + ')'
    labelCodeLog.value = isNaN(code.value) ? 'text-danger' : 'text-success'
    validateForm()
  }


  function changeStatus(event) {
    visibleStatus.value = true
    status.value = event.target.value
    if(status.value == 0 ) {
      statusLog.value = 'Choisir un statut'
      labelStatusLog.value = 'text-danger'
    }else if (status.value == 1) {
      statusLog.value = 'Le status est disponible'
      labelStatusLog.value = 'text-success'
    }else {
      statusLog.value = 'Le status est indisponible'
      labelStatusLog.value = 'text-danger'
    }
    validateForm()
  }

  const isFormValid = computed(() => {
    return name.value.length >= 3 
      && acronym.value.length >= 2 
      && code.value !== '' 
      && !isNaN(Number(code.value))
  })

  // function validateForm() {
  //   if (nameLenght >= 3 && acronymLenght >= 2 && !isNaN(code.value)) {
  //     alert()
  //     isFormValid.value = true
  //   } else {
  //     isFormValid.value = false
  //   }
  // }


  const Countries = ref([])
  // Fonction pour recuperer les produits
  async function getCountries() {
    try {
      const response = await axios.get('/api/country', {
        headers: {Accept: 'application/json'}
      });

      Countries.value = response.data;
      console.log('Pays récupérés:', Countries.value);
    } catch (error) {
      alert('Erreur lors de la récupération des pays:', error);
      console.error('Erreur lors de la récupération des pays:', error);
    }
  }

  onMounted(() => {
    getCountries()
  })

  function hideModal(id) {
    const modal = Modal.getInstance(document.getElementById(id))
    modal.hide()
  }

  function resetForm() {
    name.value = ''
    nameLog.value = 'Aucun nom pour le moment'
    labelNameLog.value = 'text-danger'

    acronym.value = ''
    acronymLog.value = 'Aucun acronyme pour le moment'
    labelAcronymLog.value = 'text-danger'

    code.value = ''
    codeLog.value = 'Aucun code pour le moment'
    labelCodeLog.value = 'text-danger'
  }

  // npm install sweetalert2
  import Swal from 'sweetalert2'
  // save product in database
  async function saveCountry() {
    try {
      const data = {
        name: name.value,
        acronym: acronym.value,
        code: code.value,
      }
      const response = await axios.post('/api/country', data, {
        headers: { Accept: 'application/json'}
      })
      console.log("Pays ajouté avec succès:", data)

      if (response.data.status) {
        Swal.fire({
          toast: true,
          position: 'top-end',
          icon: 'success',
          // title: 'Produit ajouté avec succès ✅',
          title: response.data.message,
          animation: true,
          showConfirmButton: false,
          timer: 5000,
          timerProgressBar: true
        })
        // Vider les champs
        resetForm()
        // Recharger les produits
        getCountries()
        // Fermer le modal
        hideModal('addCountryModal')
      } else {
        Swal.fire({
          toast: true,
          position: 'top-end',
          icon: 'error',
          // title: 'Une erreur est survenue lors de l\'ajout du produit.❌',
          title: response.data.message,
          showConfirmButton: false,
          timer: 5000,
          timerProgressBar: true
        })
      }
    } catch (error) {
      console.error("Erreur lors de l'ajout :", error)
      Swal.fire({
        icon: 'error',
        title: 'Erreur serveur',
        text: 'Une erreur est survenue lors de l\'ajout du pays.❌'
      })
    }
  }

  let countryId = 0

  // ouvrir modal update
  function countryUpdated(country) {
    countryId = country.id
    name.value = country.name
    acronym.value = country.acronym
    code.value = country.code
    const modal = new Modal(document.getElementById('updatedCountryModal'))
    modal.show()
  }

  // update dans la DB
  async function updateCountry() {
    try {
      const data = {
        name: name.value,
        acronym: acronym.value,
        code: code.value,
      }

      const response = await axios.put('/api/country/' + countryId, data, {
        headers: { Accept: 'application/json' }
      })

      if (response.data.status) {
        Swal.fire({
          toast: true,
          position: 'top-end',
          icon: 'success',
          title: response.data.message,
          showConfirmButton: false,
          timer: 3000
        })

        getCountries() // recharge
        hideModal('updatedCountryModal')
      } else {
        Swal.fire({
          toast: true,
          position: 'top-end',
          icon: 'error',
          title: response.data.message,
          showConfirmButton: false,
          timer: 3000
        })
      }
    } catch (error) {
      console.error("Erreur lors de la modification :", error)
      Swal.fire({
        icon: 'error',
        title: 'Erreur serveur',
        text: "Impossible de modifier le pays ❌"
      })
    }
  }

  // call delete alert and delete product if confirmed
  let productId = 0
  // async function deletedProduct(id) {
  //   const result = await Swal.fire({
  //     title: 'Es-tu sûr ?',
  //     text: "Cette action est irréversible !",
  //     icon: 'warning',
  //     showCancelButton: true,
  //     confirmButtonColor: '#d33',
  //     cancelButtonColor: '#3085d6',
  //     confirmButtonText: 'Oui, supprimer',
  //     cancelButtonText: 'Annuler'
  //   })

  //   if (result.isConfirmed) {
  //     try {
  //       await axios.delete('/api/products/' + id, {
  //         headers: { Accept: 'application/json' }
  //       })
        
  //       Swal.fire({
  //         toast: true,
  //         position: 'top-end',
  //         icon: 'success',
  //         title: 'Produit supprimé avec succès ✅',
  //         showConfirmButton: false,
  //         timer: 3000
  //       })

  //       // Recharge la liste des produits
  //       getProducts()
  //     } catch (error) {
  //       console.error("Erreur lors de la suppression :", error)

  //       Swal.fire({
  //         toast: true,
  //         position: 'top-end',
  //         icon: 'error',
  //         title: 'Échec de la suppression ❌',
  //         text: error?.response?.data?.message || "Erreur serveur",
  //         showConfirmButton: false,
  //         timer: 4000
  //       })
  //     }
  //   }
  // }
</script>