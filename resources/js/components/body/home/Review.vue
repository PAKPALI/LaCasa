<template>
    <div class="container py-5">

        <!-- STATISTIQUES DES AVIS -->
        <div class="card shadow-lg p-4 mb-5 rounded bg-gradient-stat text-light text-center">
            <h4 class="fw-bold text-light mb-3">Indice de confiance</h4>

            <div class="display-5 fw-bold text-warning">
                {{ averageRating.toFixed(1) }} / 5
            </div>

            <div class="stars-average mb-2">
                <span
                v-for="n in 5"
                :key="n"
                class="star-big"
                :class="{ active: averageRating >= n }"
                >★</span>
            </div>

            <p class="text-light-50 mb-0">{{ totalReviews }} avis publiés</p>

            <hr class="border-light">

            <p class="mt-3" style="font-size: 0.95rem; line-height: 1.4;">
                Chaque note reçue renforce notre transparence.  
                La moyenne ci-dessus reflète l’expérience réelle des utilisateurs.  
                Votre avis contribue à rendre la plateforme plus fiable, plus juste,  
                et continuellement en amélioration.
            </p>
        </div>


        <h2 class="text-center mb-2 fw-bold">Vos avis</h2>

        <!-- FORMULAIRE POUR LAISSER UN AVIS -->
        <div class="card shadow-lg p-4 mb-5 rounded bg-dark text-light">
            <h4 class="mb-3 text-light">Votre avis compte</h4>
            <hr>

            <div v-if="isAuthenticated">
                <div class="mb-3 d-flex align-items-center">
                <label class="me-3 mb-0">Votre note :</label>
                <div>
                    <span
                    v-for="n in 5"
                    :key="n"
                    class="star"
                    :class="{ active: rating >= n }"
                    @click="rating = n"
                    @mouseover="hoverRating = n"
                    @mouseleave="hoverRating = 0"
                    >★</span>
                </div>
                </div>

                <div class="mb-3">
                <textarea v-model="comment" class="form-control" placeholder="Votre avis..." rows="3"></textarea>
                </div>

                <button class="btn btn-success btn-shimmer" @click="submitReview" :disabled="loading.submit">
                <span v-if="loading.submit" class="spinner-border spinner-border-sm"></span>
                <span v-else>Envoyer</span>
                </button>
            </div>

            <div v-else class="text-center text-light mt-3">
                <p>Veuillez vous connecter pour laisser un avis.</p>
                <button class="btn btn-primary btn-shimmer" @click="promptLogin">Se connecter</button>
            </div>
        </div>

        <!-- DONATION -->
        <div class="text-center text-light mb-5">
            <button class="btn-lg btn-warning btn-shimmer" @click="goToDonation">Faire un don </button>
        </div>

        <!-- LISTE DES AVIS -->
        <div>
            <div v-if="reviews.data.length === 0" class="text-center text-danger-50">
                Aucun avis pour le moment.
            </div>
            <h2 class="text-center mb-2 fw-bold">Liste des avis </h2>
            <div v-for="review in reviews.data" :key="review.id" class="card mb-4 p-4 shadow rounded bg-secondary bg-opacity-10">
                
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <button class="btn btn-md btn-dark btn-shimmer"><strong class="text-light">{{ review.user.name }}</strong></button>
                    <div class="stars text-warning">
                        {{ '★'.repeat(review.rating) }}{{ '☆'.repeat(5 - review.rating) }}
                    </div>
                </div>

                <p class="text-light">{{ review.comment }}</p>
                <small class="text-light-50">{{ formatDate(review.created_at) }}</small>

                <!-- COMMENTAIRES -->
                <div v-if="review.comments.length" class="mt-3 ps-3 border-start border-light">
                <div v-for="c in review.comments" :key="c.id" class="mb-2">
                    <strong class="text-primary">
                        {{ c.is_admin_comment ? "LaCasa" : c.user.name }}
                    </strong> :
                    <span class="text-light">{{ c.comment }}</span>
                    <br>
                    <small class="text-light-50">{{ formatDate(c.created_at) }}</small>

                    <button v-if="isAdmin" class="btn btn-sm btn-danger ms-2" @click="deleteComment(c.id)" :disabled="loading.deleteComment[c.id]">
                        <span v-if="loading.deleteComment[c.id]" class="spinner-border spinner-border-sm"></span>
                        <span v-else>Supprimer</span>
                    </button>

                </div>
                </div>

                <!-- RÉPONSE ADMIN -->
                <div v-if="isAdmin" class="mt-3">
                <input v-model="replyText[review.id]" placeholder="Répondre..." class="form-control mb-2"/>
                    <button class="btn btn-sm btn-primary" @click="sendReply(review.id)" :disabled="loading.reply[review.id]">
                        <span v-if="loading.reply[review.id]" class="spinner-border spinner-border-sm"></span>
                        <span v-else>Envoyer</span>
                    </button>
                    <button v-if="user && user.id === review.user_id" class="btn btn-sm btn-danger ms-2" @click="deleteReview(review.id)" :disabled="loading.deleteReview[review.id]">
                        <span v-if="loading.deleteReview[review.id]" class="spinner-border spinner-border-sm"></span>
                        <span v-else>Supprimer l'avis</span>
                    </button>
                </div>
            </div>

            <!-- PAGINATION -->
            <div class="d-flex justify-content-between align-items-center mt-4 text-light-50" v-if="reviews.data.length">
                <button
                    class="btn btn-outline-dark"
                    :disabled="!reviews.prev_page_url"
                    @click="fetchReviews(reviews.current_page - 1)"
                >
                    Précédent
                </button>

                <span class="text-dark">Page {{ reviews.current_page }} / {{ reviews.last_page }}</span>

                <button
                    class="btn btn-outline-dark"
                    :disabled="!reviews.next_page_url"
                    @click="fetchReviews(reviews.current_page + 1)"
                >
                    Suivant
                </button>
            </div>
        </div>
    </div>
</template>

<script setup>
    import { ref, onMounted } from "vue"
    import axios from "axios"
    import Swal from "sweetalert2"
    import { isAuthenticated, user } from "../../auth/auth.js"

    const reviews = ref({
        data: [],
        current_page: 1,
        last_page: 1,
        next_page_url: null,
        prev_page_url: null,
        total: 0
    })
    const comment = ref("")
    const rating = ref(0)
    const hoverRating = ref(0)
    const replyText = ref({})
    const isAdmin = ref(false)
    const loading = ref({
        submit: false,
        reply: {},       // loader par reviewId
        deleteReview: {},// loader par reviewId
        deleteComment: {}// loader par commentId
    })

    const averageRating = ref(0)
    const totalReviews = ref(0)
    import { useRouter } from 'vue-router'
    const router = useRouter()

    onMounted(() => {
        fetchReviews()
        if(user.value && (user.value.role === 1 || user.value.role === 2)) {
            isAdmin.value = true
        }
    })

    async function fetchReviews(page = 1) {
        try {
            const res = await axios.get(`/reviews?page=${page}`)
            const data = res.data

            reviews.value.data = data.data
            reviews.value.current_page = data.current_page
            reviews.value.last_page = data.last_page
            reviews.value.next_page_url = data.next_page_url
            reviews.value.prev_page_url = data.prev_page_url
            reviews.value.total = data.total

            // stats
            totalReviews.value = data.total
            averageRating.value = data.data.length
            ? data.data.reduce((acc, r) => acc + r.rating, 0) / data.data.length
            : 0

        } catch (err) {
            console.error(err)
        }
    }

    async function submitReview() {
        if (!rating.value || !comment.value.trim()) {
            Swal.fire("Erreur", "Veuillez choisir une note et écrire un commentaire.", "error")
            return
        }
        try {
            loading.value.submit = true
            await axios.post("/reviews", { rating: rating.value, comment: comment.value })
            Swal.fire("Merci", "Votre avis a été envoyé.", "success")
            comment.value = ""
            rating.value = 0
            fetchReviews()
        } catch (err) {
            Swal.fire("Erreur", err.response?.data?.message || "Impossible d'envoyer l'avis.", "error")
        } finally { loading.value.submit = false }
    }

    async function sendReply(reviewId) {
        if (!replyText.value[reviewId]) return

        loading.value.reply[reviewId] = true

        try {
            await axios.post(`/reviews/${reviewId}/comment`, {
            comment: replyText.value[reviewId]
            })

            Swal.fire("Réponse envoyée", "", "success")
            replyText.value[reviewId] = ""
            fetchReviews()

        } catch (err) {
            Swal.fire("Erreur", err.response?.data?.message || "Impossible d'envoyer la réponse.", "error")

        } finally {
            loading.value.reply[reviewId] = false
        }
    }

    async function deleteComment(commentId) {
        const confirm = await Swal.fire({
            title: "Supprimer le commentaire ?",
            text: "Cette action est irréversible.",
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: "Oui, supprimer",
            confirmButtonColor: '#d33',
            cancelButtonText: "Annuler"
        })

        if (!confirm.isConfirmed) return

        loading.value.deleteComment[commentId] = true

        try {
            await axios.delete(`/review-comments/${commentId}`)
            Swal.fire("Supprimé", "", "success")
            fetchReviews()

        } catch (err) {
            Swal.fire("Erreur", err.response?.data?.message || "Impossible de supprimer.", "error")

        } finally {
            loading.value.deleteComment[commentId] = false
        }
    }

    async function deleteReview(reviewId) {
        const confirm = await Swal.fire({
            title: "Supprimer l'avis ?",
            text: "Cette action est irréversible.",
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: "Oui, supprimer",
            confirmButtonColor: '#d33',
            cancelButtonText: "Annuler"
        })

        if (!confirm.isConfirmed) return

        loading.value.deleteReview[reviewId] = true

        try {
            await axios.delete(`/reviews/${reviewId}`)
            Swal.fire("Supprimé", "", "success")
            fetchReviews()

        } catch (err) {
            Swal.fire("Erreur", err.response?.data?.message || "Impossible de supprimer.", "error")

        } finally {
            loading.value.deleteReview[reviewId] = false
        }
    }

    function formatDate(date) {
        return new Date(date).toLocaleString("fr-FR", { dateStyle: "short", timeStyle: "short" })
    }

    function promptLogin() {
        Swal.fire({
            title: "Connexion requise",
            html: `
            <div style="display:flex; flex-direction:column; align-items:center; justify-content:center;">
                <img width="94" height="94" src="https://img.icons8.com/3d-fluency/94/user-male-circle.png" alt="user-male-circle"/>
                <p style="font-size:16px; color:#0e2e50; font-weight:500; margin-top:15px;">
                Vous devez être connecté pour donner votre avis.<br>
                Voulez-vous vous connecter maintenant ?
                </p>
            </div>
            `,
            showCancelButton: true,
            confirmButtonText: "Oui, se connecter",
            cancelButtonText: "Plus tard",
            background: '#f9f9f9',
            color: '#0e2e50',
            confirmButtonColor: '#00b88d',
            cancelButtonColor: '#0e2e50',
            customClass: {
            popup: 'animated fadeInDown faster',
            }
        }).then((result) => {
            if (result.isConfirmed) {
            // 👇 Deuxième étape : demande s’il a déjà un compte
            Swal.fire({
                title: "Avez-vous déjà un compte ?",
                html: `
                <div style="display:flex; flex-direction:column; align-items:center; justify-content:center;">
                    <img width="94" height="94" src="https://img.icons8.com/3d-fluency/94/user-shield.png" alt="user-shield"/>
                    <p style="font-size:16px; color:#0e2e50; font-weight:500; margin-top:15px;">
                    Vous n'avez pas encore de compte ?<br>
                    Créez-en un maintenant pour publier vos annonces !
                    </p>
                </div>
                `,
                showDenyButton: true,
                confirmButtonText: "Oui, j'ai un compte",
                denyButtonText: "Non, je veux m'inscrire",
                confirmButtonColor: '#00b88d',
                denyButtonColor: '#0e2e50',
                background: '#f9f9f9',
                color: '#0e2e50',
            }).then((choice) => {
                if (choice.isConfirmed) {
                // 👉 Redirige vers connexion
                router.push('/login')
                } else if (choice.isDenied) {
                // 👉 Redirige vers inscription
                router.push('/register')
                }
            })
            }
        })
    }

    async function goToDonation() {
        // 1️⃣ Demander le montant
        const { value: amount } = await Swal.fire({
            title: "Faire un don",
            input: "number",
            inputLabel: "Entrez le montant de votre don (en XOF)",
            inputPlaceholder: "Ex: 5000",
            inputAttributes: { min: 100, step: 1 },
            showCancelButton: true,
            confirmButtonText: "Continuer",
            cancelButtonText: "Annuler"
        });

        if (!amount) return;

        Swal.fire({
            title: "Paiement en cours...",
            allowOutsideClick: false,
            didOpen: () => Swal.showLoading()
        });

        try {
            // 2️⃣ Appel API donation
            const response = await axios.post("/donation/payment", {
            amount: amount
            });

            if (!response.data || !response.data.payment_url) {
            Swal.fire("Erreur", "Lien de paiement introuvable.", "error");
            return;
            }

            // 3️⃣ Redirection vers KPrimePay
            window.location.href = response.data.payment_url;

        } catch (error) {
            Swal.fire(
            "Erreur",
            error.response?.data?.message || "Impossible de démarrer le paiement.",
            "error"
            );
        }
    }
</script>

<style scoped>
    .star {
        cursor: pointer;
        font-size: 1.8rem;
        transition: transform 0.2s, color 0.2s;
        color: #ccc;
    }
    .star.active, .star:hover, .star:hover ~ .star {
        color: #f4c150;
        transform: scale(1.3);
    }

    /* Boutons shimmer inspirés de ton profil */
    .btn-shimmer {
        position: relative;
        overflow: hidden;
        z-index: 0;
        transition: box-shadow .15s ease;
    }
    .btn-shimmer::before {
        content: "";
        position: absolute;
        top: -40%;
        left: -50%;
        width: 200%;
        height: 200%;
        background: linear-gradient(
            120deg,
            rgba(255,255,255,0) 0%,
            rgba(255,255,255,0.95) 30%,
            rgba(255,230,150,0.85) 45%,
            rgba(255,255,255,0.95) 60%,
            rgba(255,255,255,0) 100%
        );
        transform: translateX(-120%) rotate(15deg);
        z-index: 1;
        pointer-events: none;
        animation: shimmer-swipe 3s cubic-bezier(.4,0,.6,1) infinite;
        mix-blend-mode: screen;
        opacity: 0;
        filter: blur(6px);
    }
    @keyframes shimmer-swipe {
        0%   { transform: translateX(-120%) rotate(15deg); opacity: 0; }
        10%  { opacity: 0; }
        30%  { transform: translateX(0%) rotate(15deg); opacity: 1; }
        45%  { transform: translateX(30%) rotate(15deg); opacity: 0.9; }
        60%  { transform: translateX(70%) rotate(15deg); opacity: 0; }
        100% { transform: translateX(120%) rotate(15deg); opacity: 0; }
    }
    .btn-shimmer {
        animation: shimmer-pulse-shadow 3s steps(1) infinite;
    }
    @keyframes shimmer-pulse-shadow {
        0%   { box-shadow: none; }
        33%  { box-shadow: 0 6px 22px rgba(255,200,80,0); }
        34%  { box-shadow: 0 8px 28px rgba(255,220,110,0.26); }
        37%  { box-shadow: 0 4px 14px rgba(0,0,0,0.12); }
        100% { box-shadow: none; }
    }
    .btn-shimmer * { position: relative; z-index: 2; }

    /* Couleurs et style premium */
    .card { border-radius: 15px; }
    .bg-secondary.bg-opacity-10 { background-color: rgba(10, 39, 112, 0.949) !important; }
    .text-light { color: #f8f9fa; }
    .text-light-50 { color: rgba(248,249,250,0.5); }


    /* CARD STATISTIQUE */
    .bg-gradient-stat {
        background: linear-gradient(135deg, #0e2e50, #174d7f, #0e2e50);
        border: 1px solid rgba(255,255,255,0.08);
    }

    /* Étoiles grandes pour la moyenne */
    .star-big {
    font-size: 2.5rem;
    color: rgba(255,255,255,0.25);
    transition: color .3s ease;
    }
    .star-big.active {
    color: #f7c844;
    }

    /* Ajuste les étoiles petites pour les avis */
    .stars-average {
    margin: 10px 0;
    }

</style>
