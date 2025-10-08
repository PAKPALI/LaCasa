<template>
  <div class="site-section mt-5">
    <div class="container">

      <div class="row justify-content-center mt-5">
        <div class="col-md-7 text-center mt-5">
          <div class="site-section-title">
            <h2>Section admin</h2>
          </div>
          <p>Cette section permet d'interagir avec les outils dont le site aura besoin pour se développer</p>
        </div>
      </div>

      <div class="row justify-content-center" data-aos="fade" data-aos-delay="100">
        <div class="accordion" id="accordionExample">
          <Country accordionId="adminAccordion" />
          <Town accordionId="adminAccordion" />
          <District accordionId="adminAccordion" />
          <Category accordionId="adminAccordion" />
          <PubType accordionId="adminAccordion" />
          <Attribute accordionId="adminAccordion" />
          <User accordionId="adminAccordion" />
        </div>
      </div>

    </div>
  </div>
</template>

<script setup>
  import Country from './content/Country.vue'
  import Town from './content/Town.vue'
  import District from './content/District.vue'
  import Category from './content/Category.vue'
  import PubType from './content/PubType.vue'
  import Attribute from './content/Attribute.vue'
  import User from './content/User.vue'
</script>
