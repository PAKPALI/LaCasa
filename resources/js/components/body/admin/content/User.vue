<template>
  <!-- Loader global -->
  <div v-if="loadingTable" class="text-center my-5">
    <div class="spinner-border text-primary" role="status">
      <span class="visually-hidden">Chargement...</span>
    </div>
  </div>

  <!-- Modal Modifier -->
  <div class="modal fade mt-5" id="updateUserModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header bg-warning text-dark">
          <h5 class="modal-title">Modifier l'utilisateur</h5>
          <button type="button" class="btn-close bg-light" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <form>
            <div class="row mb-3">
              <div class="col-md-6">
                <label class="form-label">Nom</label>
                <input type="text" class="form-control" v-model="userForm.name">
              </div>
              <div class="col-md-6">
                <label class="form-label">Email</label>
                <input type="email" class="form-control" v-model="userForm.email" disabled>
              </div>
            </div>

            <div class="row mb-3">
              <div class="col-md-6">
                <label class="form-label">Rôle</label>
                <select class="form-select" v-model="userForm.role">
                  <option value="1">Super Admin</option>
                  <option value="2">Admin</option>
                  <option value="3">Client</option>
                </select>
              </div>
              <div class="col-md-6">
                <label class="form-label">Statut</label>
                <select class="form-select" v-model="userForm.is_active">
                  <option value="1">Actif</option>
                  <option value="0">Inactif</option>
                </select>
              </div>
            </div>
          </form>
        </div>
        <div class="modal-footer bg-light">
          <button class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
          <button class="btn btn-warning" @click="updateUser" :disabled="loadingButton==='update'">
            <span v-if="loadingButton==='update'" class="spinner-border spinner-border-sm text-dark"></span>
            <span v-else>Modifier</span>
          </button>
        </div>
      </div>
    </div>
  </div>

  <!-- Modal Détails -->
  <div class="modal fade mt-5" id="viewUserModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header bg-info text-dark">
          <h5 class="modal-title">Détails de l'utilisateur</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <div v-if="selectedUser">
            <div class="row">
              <div class="col-md-4 text-center">
                <img :src="selectedUser.profile_image ? '/'+selectedUser.profile_image : '/default-avatar.png'" 
                     alt="Photo de profil" class="img-thumbnail rounded-circle mb-2" width="120" height="120">
              </div>
              <div class="col-md-8">
                <p><strong>Nom :</strong> {{ selectedUser.name }}</p>
                <p><strong>Email :</strong> {{ selectedUser.email }}</p>
                <p><strong>Téléphone 1 :</strong> {{ selectedUser.phone1 ?? '—' }}</p>
                <p><strong>Téléphone 2 :</strong> {{ selectedUser.phone2 ?? '—' }}</p>
                <p><strong>Type :</strong> 
                  <span v-if="selectedUser.user_type==1">Personne</span>
                  <span v-else-if="selectedUser.user_type==2">Agence</span>
                  <span v-else>Inconnu</span>
                </p>
                <p><strong>Pays :</strong> {{ selectedUser.country?.name ?? '—' }}</p>
                <p><strong>Ville :</strong> {{ selectedUser.town?.name ?? '—' }}</p>
                <p><strong>Quartier :</strong> {{ selectedUser.district?.name ?? '—' }}</p>
                <p><strong>Rôle :</strong><span>{{ selectedUser.role_name }}</span></p>
                <p><strong>Statut :</strong>
                  <span :class="selectedUser.is_active ? 'text-success' : 'text-danger'">
                    {{ selectedUser.is_active ? 'Actif' : 'Inactif' }}
                  </span>
                </p>
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer bg-light">
          <button class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
        </div>
      </div>
    </div>
  </div>

  <!-- Accordéon Utilisateurs -->
  <div class="accordion-item mb-4">
    <h2 class="accordion-header" id="headingUsers">
      <button class="accordion-button bg-primary text-light" type="button" data-bs-toggle="collapse" data-bs-target="#collapseUsers" aria-expanded="true" aria-controls="collapseUsers">
        SECTION UTILISATEURS
      </button>
    </h2>
    <div id="collapseUsers" class="accordion-collapse collapse show" aria-labelledby="headingUsers" data-bs-parent="#accordionExample">
      <div class="accordion-body bg-dark text-white">
        <p>Vous pouvez visualiser, modifier, activer/désactiver ou supprimer les utilisateurs enregistrés.</p>

        <!-- Recherche -->
        <div class="mb-3">
          <input type="text" class="form-control" placeholder="Rechercher un utilisateur par nom, email, pays..." v-model="searchQuery">
        </div>

        <!-- Tableau -->
        <table v-if="!loadingTable" class="table table-bordered table-hover table-primary border-dark">
          <thead>
            <tr>
              <th>#</th>
              <th>Nom</th>
              <th>Email</th>
              <th>Pays</th>
              <th>Ville</th>
              <th>Rôle </th>
              <th>Statut</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="(user, index) in paginatedUsers" :key="user.id">
              <td>{{ (currentPage - 1) * perPage + index + 1 }}</td>
              <td>{{ user.name }}</td>
              <td>{{ user.email }}</td>
              <td>{{ user.country?.name ?? '—' }}</td>
              <td>{{ user.town?.name ?? '—' }}</td>
              <td>{{ user.role_name ?? '—' }}</td>
              <td>
                <span :class="user.is_active ? 'text-success' : 'text-danger'">
                  {{ user.is_active ? 'Actif' : 'Inactif' }}
                </span>
              </td>
              <td>
                <button class="btn btn-sm btn-info me-1" @click="viewUser(user)">
                  <i class="bi bi-eye"></i>
                </button>
                <button v-if="isAuthenticated && user.role == 1" class="btn btn-sm btn-warning me-1" @click="editUser(user)" :disabled="loadingButton==='update'">
                  <i class="bi bi-pencil-square"></i>
                </button>
                <button v-if="isAuthenticated && user.role == 1" class="btn btn-sm btn-danger" @click="deleteUser(user.id)" :disabled="loadingButton===user.id">
                  <span v-if="loadingButton===user.id" class="spinner-border spinner-border-sm text-light"></span>
                  <span v-else><i class="bi bi-trash"></i></span>
                </button>
              </td>
            </tr>
            <tr v-if="filteredUsers.length===0">
              <td colspan="7" class="text-center text-danger">Aucun utilisateur trouvé</td>
            </tr>
          </tbody>
        </table>

        <!-- Pagination -->
        <div v-if="!loadingTable" class="d-flex justify-content-between align-items-center mt-2">
          <button class="btn btn-sm btn-dark" @click="prevPage" :disabled="currentPage===1">Précédent</button>
          <span>Page {{ currentPage }} / {{ totalPages }}</span>
          <button class="btn btn-sm btn-dark" @click="nextPage" :disabled="currentPage===totalPages">Suivant</button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import axios from 'axios'
import Swal from 'sweetalert2'
import { Modal } from 'bootstrap'
import { user, isAuthenticated } from '../../../auth/auth.js'

const Users = ref([])
const selectedUser = ref(null)
const searchQuery = ref('')
const currentPage = ref(1)
const perPage = ref(5)
const loadingTable = ref(true)
const loadingButton = ref('')

const userForm = ref({
  id: null,
  name: '',
  email: '',
  role: 3,
  is_active: 1
})

// --- PAGINATION & FILTRE ---
const filteredUsers = computed(() => {
  if (!searchQuery.value) return Users.value
  const q = searchQuery.value.toLowerCase()
  return Users.value.filter(u =>
    u.name.toLowerCase().includes(q) ||
    u.email.toLowerCase().includes(q) ||
    (u.country?.name?.toLowerCase().includes(q) ?? false)
  )
})
const totalPages = computed(() => Math.max(1, Math.ceil(filteredUsers.value.length / perPage.value)))
const paginatedUsers = computed(() => {
  const start = (currentPage.value - 1) * perPage.value
  return filteredUsers.value.slice(start, start + perPage.value)
})
function nextPage() { if (currentPage.value < totalPages.value) currentPage.value++ }
function prevPage() { if (currentPage.value > 1) currentPage.value-- }

// --- CRUD ---
async function getUsers() {
  loadingTable.value = true
  try {
    const res = await axios.get('/api/users', { headers: { Accept: 'application/json' } })
    Users.value = res.data.data  // ✅ on récupère la clé "data"
  } finally {
    loadingTable.value = false
  }
}

function viewUser(user) {
  selectedUser.value = user
  new Modal(document.getElementById('viewUserModal')).show()
}
function editUser(user) {
  userForm.value = { ...user }
  new Modal(document.getElementById('updateUserModal')).show()
}
async function updateUser() {
  loadingButton.value = 'update'
  try {
    const res = await axios.put(`/api/users/${userForm.value.id}`, {
      name: userForm.value.name,
      role: userForm.value.role,
      is_active: userForm.value.is_active
    })
    if (res.data.status) {
      Swal.fire({ toast: true, position: 'top-end', icon: 'success', title: res.data.message, showConfirmButton: false, timer: 3000 })
      await getUsers()
      Modal.getInstance(document.getElementById('updateUserModal')).hide()
    }
  } finally {
    loadingButton.value = ''
  }
}
async function deleteUser(id) {
  const confirm = await Swal.fire({
    title: 'Supprimer cet utilisateur ?',
    text: "Cette action est irréversible !",
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: 'red',
    confirmButtonText: 'Oui, supprimer',
    cancelButtonText: 'Annuler'
  })
  if (confirm.isConfirmed) {
    loadingButton.value = id
    try {
      const res = await axios.delete(`/api/users/${id}`)
      if (res.data.status) {
        Swal.fire({ toast: true, icon: 'success', title: res.data.message, showConfirmButton: false, timer: 3000 })
        await getUsers()
      }
    } finally {
      loadingButton.value = ''
    }
  }
}

onMounted(getUsers)
</script>
