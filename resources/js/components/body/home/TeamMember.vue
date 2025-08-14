<template>
  <div class="team-member">
    <div class="image-wrapper">
      <img :src="image" alt="Image" class="img-fluid rounded" />
      <div class="social-links">
        <a
          v-for="(icon, i) in social"
          :key="i"
          :href="icon.link"
          target="_blank"
          rel="noopener noreferrer"
        >
          <span :class="icon.class"></span>
        </a>
      </div>
    </div>
    <div class="member-info">
      <h2>{{ name }}</h2>
      <span class="position">{{ position }}</span>
      <p>{{ description }}</p>
    </div>
  </div>
</template>

<script setup>
const props = defineProps({
  image: String,
  name: String,
  position: String,
  description: String,
  social: Array
})
</script>

<style scoped>
.team-member {
  background: #fff;
  border-radius: 15px;
  overflow: hidden;
  box-shadow: 0 6px 20px rgba(0, 0, 0, 0.08);
  transition: transform 0.4s ease, box-shadow 0.4s ease;
  position: relative;
}

.team-member:hover {
  transform: translateY(-10px) scale(1.03);
  box-shadow: 0 0 20px rgba(89,165,44,0.6),
              0 0 40px rgba(89,165,44,0.4),
              0 0 60px rgba(89,165,44,0.2);
}

.image-wrapper {
  position: relative;
  overflow: hidden;
}

.image-wrapper img {
  width: 100%;
  transition: transform 0.4s ease;
}

.team-member:hover img {
  transform: scale(1.05);
}

/* Social icons */
.social-links {
  position: absolute;
  bottom: 10px;
  left: 50%;
  transform: translateX(-50%);
  display: flex;
  gap: 15px;
  opacity: 0;
  transition: opacity 0.4s ease;
}

.team-member:hover .social-links {
  opacity: 1;
}

.social-links a span {
  font-size: 18px;
  color: #59A52C;
  transition: transform 0.3s ease, color 0.3s ease;
  animation: pulse 2s infinite;
}

.social-links a span:hover {
  transform: scale(1.3);
  color: #4b8d27;
  text-shadow: 0 0 8px #59A52C, 0 0 15px #59A52C;
}

/* Animation pulsation */
@keyframes pulse {
  0%, 100% { transform: scale(1); }
  50% { transform: scale(1.1); }
}

/* Member info */
.member-info {
  padding: 20px;
  text-align: center;
}

.member-info h2 {
  font-size: 1.4rem;
  color: #333;
  margin-bottom: 5px;
}

.member-info .position {
  color: #59A52C;
  font-weight: 600;
  margin-bottom: 10px;
  display: block;
}

.member-info p {
  font-size: 0.95rem;
  color: #555;
}
</style>
