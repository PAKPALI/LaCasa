<template>
  <div class="site-section">
    <div class="container">
      <div class="row mb-5 justify-content-center">
        <div class="col-md-7">
          <div class="site-section-title text-center">
            <h2>Our Agents</h2>
            <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Vero magnam officiis ipsa eum pariatur...</p>
          </div>
        </div>
      </div>
      <div class="row">
        <div
          class="col-md-6 col-lg-4 mb-5 mb-lg-5"
          v-for="(agent, idx) in agents"
          :key="idx"
        >
          <TeamMember
            :image="agent.image"
            :name="agent.name"
            :position="agent.position"
            :description="agent.description"
            :social="agent.social"
          />
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import TeamMember from './TeamMember.vue'
import person1 from '@images/person_1.jpg'
import person2 from '@images/person_2.jpg'
import person3 from '@images/person_3.jpg'

const agents = [
  {
    image: person1,
    name: 'Megan Smith',
    position: 'Real Estate Agent',
    description: 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Modi dolorem totam non quis facere blanditiis praesentium est.',
    social: [
      { class: 'icon-facebook', link: '#' },
      { class: 'icon-twitter', link: '#' },
      { class: 'icon-linkedin', link: '#' }
    ]
  },
  {
    image: person2,
    name: 'Brooke Cagle',
    position: 'Real Estate Agent',
    description: 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Omnis, cumque vitae voluptates culpa earum similique corrupti itaque veniam.',
    social: [
      { class: 'icon-facebook', link: '#' },
      { class: 'icon-twitter', link: '#' },
      { class: 'icon-linkedin', link: '#' }
    ]
  },
  {
    image: person3,
    name: 'Philip Martin',
    position: 'Real Estate Agent',
    description: 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Maiores illo iusto, inventore, iure dolorum officiis modi repellat nobis.',
    social: [
      { class: 'icon-facebook', link: '#' },
      { class: 'icon-twitter', link: '#' },
      { class: 'icon-linkedin', link: '#' }
    ]
  }
]
</script>
