<template>

  <!-- Loader -->
  <div v-if="loadingTable" class="text-center my-5">
    <div class="spinner-border text-primary" role="status">
      <span class="visually-hidden">Chargement...</span>
    </div>
  </div>

  <!-- Modal Détails Paiement -->
  <div class="modal fade" id="viewPaymentModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        
        <div class="modal-header bg-info text-dark">
          <h5 class="modal-title">Détails du paiement</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>

        <div class="modal-body" v-if="selectedPayment">

          <p><strong>Méthode :</strong> {{ selectedPayment.payment_method }}</p>
          <p><strong>Gateway :</strong> {{ selectedPayment.payment_gateway }}</p>
          <p><strong>Référence :</strong> {{ selectedPayment.payment_reference }}</p>
          <p><strong>Montant :</strong> {{ selectedPayment.amount }} {{ selectedPayment.currency }}</p>
          <p><strong>Statut :</strong>
            <span :class="selectedPayment.status=='paid' ? 'text-success' : selectedPayment.status=='failed' ? 'text-danger' : 'text-warning'">
              {{ selectedPayment.status }}
            </span>
          </p>
          <p><strong>Utilisateur :</strong> {{ selectedPayment.user?.name ?? 'Donateur anonyme' }}</p>

          <hr>
          <p><strong>Payload Webhook :</strong></p>
          <pre class="bg-dark text-white p-2 rounded">{{ selectedPayment.webhook_payload }}</pre>

        </div>

        <div class="modal-footer bg-light">
          <button class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
        </div>

      </div>
    </div>
  </div>

  <!-- SECTION PAIEMENTS -->
  <div class="accordion-item mb-4">
    <h2 class="accordion-header" id="headingPayments">
      <button class="accordion-button bg-primary text-light" type="button" data-bs-toggle="collapse" data-bs-target="#collapsePayments">
        SECTION PAIEMENTS
      </button>
    </h2>

    <div id="collapsePayments" class="accordion-collapse collapse show">

      <div class="accordion-body bg-dark text-white">

        <p>Liste des paiements enregistrés.</p>

        <!-- Recherche -->
        <input type="text" class="form-control mb-3" placeholder="Rechercher..." v-model="searchQuery">

        <!-- Tableau -->
        <table v-if="!loadingTable" class="table table-bordered table-hover table-primary border-dark">
          <thead>
            <tr>
              <th>#</th>
              <th>Utilisateur</th>
              <th>Méthode</th>
              <th>Gateway</th>
              <!-- <th>Référence</th> -->
              <th>Montant</th>
              <th>Statut</th>
              <th>Actions</th>
            </tr>
          </thead>

          <tbody>
            <tr v-for="(p, index) in paginatedPayments" :key="p.id">
              <td>{{ (currentPage - 1) * perPage + index + 1 }}</td>
              <td>{{ p.user?.name }}</td>
              <td>{{ p.payment_method ?? '-' }}</td>
              <td>{{ p.payment_gateway ?? '-' }}</td>
              <!-- <td>{{ p.payment_reference }}</td> -->
              
              <td>{{ p.amount }} {{ p.currency }}</td>

              <td>
                <span :class="p.status=='paid' ? 'text-success' : p.status=='failed' ? 'text-danger' : 'text-warning'">
                  {{ p.status }}
                </span>
              </td>

              <td>
                <button class="btn btn-sm btn-info" @click="viewPayment(p)">
                  <i class="bi bi-eye"></i>
                </button>
              </td>
            </tr>

            <tr v-if="filteredPayments.length === 0">
              <td colspan="8" class="text-center text-danger">Aucun paiement trouvé</td>
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
import { Modal } from 'bootstrap'

const Payments = ref([])
const loadingTable = ref(true)
const searchQuery = ref('')
const selectedPayment = ref(null)

const currentPage = ref(1)
const perPage = ref(5)

async function getPayments() {
  loadingTable.value = true
  const res = await axios.get('/api/payments')
  Payments.value = res.data.data
  loadingTable.value = false
}

const filteredPayments = computed(() => {
  if (!searchQuery.value) return Payments.value
  const q = searchQuery.value.toLowerCase()
  return Payments.value.filter(p =>
    (p.payment_method?.toLowerCase().includes(q)) ||
    (p.payment_gateway?.toLowerCase().includes(q)) ||
    (p.payment_reference?.toLowerCase().includes(q)) ||
    (p.user?.name?.toLowerCase().includes(q))
  )
})

const totalPages = computed(() => Math.max(1, Math.ceil(filteredPayments.value.length / perPage.value)))

const paginatedPayments = computed(() => {
  const start = (currentPage.value - 1) * perPage.value
  return filteredPayments.value.slice(start, start + perPage.value)
})

function nextPage() { if (currentPage.value < totalPages.value) currentPage.value++ }
function prevPage() { if (currentPage.value > 1) currentPage.value-- }

function viewPayment(payment) {
  selectedPayment.value = payment
  new Modal(document.getElementById('viewPaymentModal')).show()
}

onMounted(getPayments)
</script>
