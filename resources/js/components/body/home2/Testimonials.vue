<template>
  <div class="site-section testimonials-section">
    <div class="container">
      <div class="row justify-content-center mb-5">
        <div class="col-md-7 text-center">
          <h2 class="site-section-title">L'avis des utilisateurs</h2>
        </div>
      </div>

      <div class="testimonial-carousel">
        <transition name="fade" mode="out-in">
          <div
            key="testimonial-{{ currentIndex }}"
            class="testimonial-item"
          >
            <blockquote>
              <p><strong>"{{ testimonials[currentIndex].text }}"</strong></p>
            </blockquote>
            <p class="testimonial-author">
              — {{ testimonials[currentIndex].name }},
              <em>{{ testimonials[currentIndex].position }}</em>
            </p>
          </div>
        </transition>

        <div class="testimonial-controls">
          <button @click="prev" aria-label="Previous testimonial">&lt;</button>
          <button @click="next" aria-label="Next testimonial">&gt;</button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'

const testimonials = [
  {
    text: "Excellente expérience ! Les membres de l’équipe ont été professionnels et très serviables tout au long du processus.",
    name: "Alice Johnson",
    position: "Propriétaire"
  },
  {
    text: "J’ai trouvé l’appartement de mes rêves grâce à cette équipe. Je recommande vivement !",
    name: "Mark Thompson",
    position: "Locataire"
  },
  {
    text: "Service excellent et annonces fantastiques. L’achat de ma maison a été simple et sans stress.",
    name: "Sara Lee",
    position: "Acheteuse"
  }

]

const currentIndex = ref(0)

function next() {
  currentIndex.value = (currentIndex.value + 1) % testimonials.length
}

function prev() {
  currentIndex.value = (currentIndex.value - 1 + testimonials.length) % testimonials.length
}
</script>

<style scoped>
  .testimonials-section {
    /* background: linear-gradient(135deg, #000, #003d1f); */
    padding: 3rem 0;
    border-radius: 20px;
    color: rgb(0, 0, 0);
  }

  h2 {
    font-size: 2.2rem;
    font-weight: bold;
    color: #fff;
    padding: 15px 30px;
    border-radius: 12px;
    text-align: center;
    background: linear-gradient(135deg, #122f02eb, rgb(0, 0, 0) 70%, #0e2501d9);
    box-shadow: 0 6px 20px rgba(0,0,0,0.4), 0 0 15px rgba(89,165,44,0.3);
    display: inline-block;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
  }

  blockquote p {
    position: relative;
    display: inline-block;
    padding: 25px 30px;
    font-size: 1.05rem;
    color: #fff;
    background: linear-gradient(135deg, rgb(22, 52, 3), rgba(46, 111, 9, 0.793));
    /* background: radial-gradient(circle, rgba(89,165,44,0.15) 0%, transparent 60%); */
    border-radius: 15px;
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.2);
    text-align: center;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    backdrop-filter: blur(5px);
  }

  .testimonial-carousel {
    position: relative;
    max-width: 600px;
    margin: 0 auto;
    text-align: center;
  }

  .testimonial-item blockquote {
    font-style: italic;
    font-size: 1.2rem;
    margin-bottom: 1rem;
  }

  .testimonial-author {
    font-weight: bold;
    color: #0f6e2d;
  }

  .testimonial-controls {
    margin-top: 1rem;
  }

  .testimonial-controls button {
    background: radial-gradient(circle, rgba(29, 74, 3, 0.988) 0%, transparent 60%);
    border: none;
    color: white;
    font-size: 1.2rem;
    padding: 0.5rem 1rem;
    margin: 0 0.5rem;
    cursor: pointer;
    border-radius: 4px;
    transition: background-color 0.3s;
  }

  .testimonial-controls button:hover {
    background: #267a47;
  }

  /* Animation */
  .fade-enter-active, .fade-leave-active {
    transition: opacity 0.5s ease;
  }
  .fade-enter-from, .fade-leave-to {
    opacity: 0;
  }
</style>
