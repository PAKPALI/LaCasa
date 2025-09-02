<template>
  <!-- addModal -->
  <div class="modal fade " id="addProductModal" tabindex="-1" aria-labelledby="addProductModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header bg-dark text-white">
          <h5 class="modal-title" id="addProductModalLabel">Ajouter un pays</h5>
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
              <label for="quantite" class="form-label">Code</label>
              <input type="number" class="form-control" id="quantite" v-model="quantity" @keyup="validateQte">
              <label :class="labelQteLog">{{ qteLog }}</label>
            </div>
            <!-- <div class="mb-3">
              <label for="limite" class="form-label">Limite</label>
              <input type="number" class="form-control" id="limite" v-model="limite" @keyup="validateLimit">
              <label :class="labelLimitLog">{{ limitLog }}</label>
            </div>
            <div class="mb-3">
              <label for="statut" class="form-label">Statut (<label :class="labelStatusLog">{{ statusLog }}</label></label>)
              <select class="form-select" id="statut" v-model="status" @change="changeStatus">
                <option value="">-- Choisir un statut --</option>
                <option value="1">Disponible</option>
                <option value="2">Indisponible</option>
              </select>
            </div> -->
          </form>
        </div>
        <div class="modal-footer bg-light">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
          <button type="button" class="btn btn-dark" @click="saveProduct" :disabled="false">Ajouter</button>
        </div>
      </div>
    </div>
  </div>

  <!-- updateModal -->
  <div class="modal fade" id="updatedProductModal" tabindex="-1" aria-labelledby="addProductModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header bg-warning text-dark">
          <h5 class="modal-title" id="addProductModalLabel">Modifier un produit</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
        </div>
        <div class="modal-body">
          <!-- Formulaire ici -->
          <form>
            <div class="mb-3">
              <label for="nomProduit" class="form-label">Nom du produit</label>
              <input type="text" class="form-control" id="nomProduit" v-model="name" @keyup="validateName">
              <label :class="labelNameLog">{{ visibleName ? nameLog : '' }}</label>
            </div>
            <div class="mb-3">
              <label for="quantite" class="form-label">Quantité</label>
              <input type="number" class="form-control" id="quantite" v-model="quantity" @keyup="validateQte">
              <label :class="labelQteLog">{{ visibleQte ? qteLog: '' }}</label>
            </div>
            <div class="mb-3">
              <label for="limite" class="form-label">Limite</label>
              <input type="number" class="form-control" id="limite" v-model="limite" @keyup="validateLimit">
              <label :class="labelLimitLog">{{ visibleLimit ? limitLog : '' }}</label>
            </div>
            <div class="mb-3">
              <label for="statut" class="form-label">Statut (<label :class="labelStatusLog">{{visibleStatus ? statusLog : '' }}</label></label>)
              <select class="form-select" id="statut" v-model="status" @change="changeStatus">
                <option value="">-- Choisir un statut --</option>
                <option value="1">Disponible</option>
                <option value="2">Indisponible</option>
              </select>
            </div>
          </form>
        </div>
        <div class="modal-footer bg-light">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
          <button type="button" class="btn btn-warning" @click="updateProduct">Modifier</button>
        </div>
      </div>
    </div>
  </div>

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
                    <button class="btn btn-dark mt-3 me-2" @click="addProduct">+ Ajouter</button>
                  </div>
                </div>

                <!-- tableau des pays -->
                <div class="row mt-3 border-top pt-3 mb-3">
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
                      <tr v-for="(product, index) in products" :key="product.id">
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
                      </tr>
                      <tr v-if="products.length === 0">
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
                      <tr v-for="(product, index) in products" :key="product.id">
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
                      </tr>
                      <tr v-if="products.length === 0">
                        <td colspan="6" class="text-center text-danger">Aucune ville trouvée</td>
                      </tr>
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
  import { ref, onMounted } from 'vue'

  const name = ref('')
  const quantity = ref(0)
  const limite = ref(0)

  const nameLog = ref('Aucun nom pour le moment')
  const labelNameLog = ref('text-danger')
  let nameLenght = 0

  const qteLog = ref('La quantité est égale à 0')
  const labelQteLog = ref('text-danger')
  let qte = 0

  const limitLog = ref('La limite est égale à 0')
  const labelLimitLog = ref('text-danger')
  let limit = 0

  const statusLog = ref('Le status est sur indisponible')
  const labelStatusLog = ref('text-danger')
  const status = ref('')
  
  // call modal add
  function addProduct() {
    resetForm()
    // Ouvre le modal pour ajouter un produit
    const modal = new Modal(document.getElementById('addProductModal'))
    modal.show()
  }
  
  const visibleName = ref(false)
  const visibleQte = ref(false)
  const visibleLimit = ref(false)
  const visibleStatus = ref(false)
  const disabled = ref(false)
  
  // call modal update
  function productUpdated(product) {
    // Ouvre le modal pour modifier un produit
    productId = product.id
    name.value = product.name
    quantity.value = product.qty
    limite.value = product.limit
    status.value = product.status
    const modal = new Modal(document.getElementById('updatedProductModal'))
    modal.show()
  }
  
  function validateName(event) {
    visibleName.value = true
    nameLenght = event.target.value.length
    nameLog.value = nameLenght< 3 ? 'Le nom doit contenir au moins 3 caractères' : 'Nombre de caractère valide ('+event.target.value+')'
    labelNameLog.value = nameLenght < 3 ? 'text-danger' : 'text-success'
    validateForm()
  }

  function validateQte(event) {
    visibleQte.value = true
    qte = event.target.value
    qteLog.value = qte <= 0 ? 'La quantité est égale à 0' : 'La quantité est égale à ' + qte
    labelQteLog.value = qte <= 0 ? 'text-danger' : 'text-success'
    validateForm()
  }

  function validateLimit(event) {
    visibleLimit.value = true
    limit = event.target.value
    limitLog.value = limit <= 0 ? 'La limite est égale à 0' : 'La limite est égale à ' + limit
    labelLimitLog.value = limit <= 0 ? 'text-danger' : 'text-success'
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

  function validateForm() {
    if(nameLenght >= 3 && qte > 0 && limit > 0 && status.value !== ''){
      disabled.value = true
    } else {
      disabled.value = false
    }
  }

  const products = ref([])
  // Fonction pour recuperer les produits
  async function getProducts() {
    try {
      const response = await axios.get('/api/products', {
        headers: {Accept: 'application/json'}
      });

      products.value = response.data;
      console.log('Produits récupérés:', products.value);
    } catch (error) {
      console.error('Erreur lors de la récupération des produits:', error);
    }
  }

  onMounted(() => {
    getProducts()
  })

  function hideModal(id) {
    const modal = Modal.getInstance(document.getElementById(id))
    modal.hide()
  }

  function resetForm() {
    name.value = ''
    nameLog.value = 'Aucun nom pour le moment'
    labelNameLog.value = 'text-danger'

    quantity.value = 0
    qteLog.value = 'La quantité est égale à 0'
    labelQteLog.value = 'text-danger'

    limite.value = 0
    limitLog.value = 'La limite est égale à 0'
    labelLimitLog.value = 'text-danger'

    status.value = ''
    statusLog.value = 'Le status est sur indisponible'
    labelStatusLog.value = 'text-danger'
  }

  // npm install sweetalert2
  import Swal from 'sweetalert2'
  // save product in database
  async function saveProduct() {
    try {
      const data = {
        name: name.value,
        quantity: quantity.value,
        limit: limite.value,
        status: status.value
      }
      const response = await axios.post('/api/products', data, {
        headers: { Accept: 'application/json'}
      })
      console.log("Produit ajouté avec succès:", data)

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
        getProducts()
        // Fermer le modal
        hideModal('addProductModal')
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
        text: 'Une erreur est survenue lors de l\'ajout du produit.❌'
      })
    }
  }

  // update product in database
  async function updateProduct() {
    try {
      const data = {
        id: productId,
        name: name.value,
        quantity: quantity.value,
        limit: limite.value,
        status: status.value
      }
      const response = await axios.put('/api/products/' + productId, data, {
        headers: { Accept: 'application/json'}
      })
      console.log("Produit modifié avec succès:", data)

      if (response.data.status) {
        Swal.fire({
          toast: true,
          position: 'top-end',
          icon: 'success',
          // title: 'Produit modifié avec succès ✅',
          title: response.data.message,
          animation: true,
          showConfirmButton: false,
          timer: 5000,
          timerProgressBar: true
        })
        // Recharger les produits
        getProducts()
        // Fermer le modal
        hideModal('updatedProductModal')
      } else {
        Swal.fire({
          toast: true,
          position: 'top-end',
          icon: 'error',
          // title: 'Une erreur est survenue lors de la modification du produit.❌',
          title: response.data.message,
          showConfirmButton: false,
          timer: 5000,
          timerProgressBar: true
        })
      }
    } catch (error) {
      console.error("Erreur lors de la modification :", error)
      Swal.fire({
        icon: 'error',
        title: 'Erreur serveur',
        text: 'Une erreur est survenue lors de la modification du produit.❌'
      })
    }
  }

  // call delete alert and delete product if confirmed
  let productId = 0
  async function deletedProduct(id) {
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
        await axios.delete('/api/products/' + id, {
          headers: { Accept: 'application/json' }
        })
        
        Swal.fire({
          toast: true,
          position: 'top-end',
          icon: 'success',
          title: 'Produit supprimé avec succès ✅',
          showConfirmButton: false,
          timer: 3000
        })

        // Recharge la liste des produits
        getProducts()
      } catch (error) {
        console.error("Erreur lors de la suppression :", error)

        Swal.fire({
          toast: true,
          position: 'top-end',
          icon: 'error',
          title: 'Échec de la suppression ❌',
          text: error?.response?.data?.message || "Erreur serveur",
          showConfirmButton: false,
          timer: 4000
        })
      }
    }
  }
</script>