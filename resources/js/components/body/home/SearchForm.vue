<template>
  <div class="row mt-5">
    <!-- <div class="col-12">
      <router-link to='/createPub' class="btn btn-dark border-0 w-100 py-3"> + Ajouter publication </router-link>
    </div> -->
  </div>
  <div class="container-fluid bg-primary mb-5" style="padding: 35px;">
    <div class="container">
      <div class="row g-2">
        <div class="col-md-10">
          <div class="row g-2">
            <div class="col-md-4">
              <input type="text" class="form-control border-0 py-3" placeholder="Mot-clé (ex: chambre, villa)">
            </div>
            <div class="col-md-4">
              <select class="form-select border-0 py-3">
                <option selected>Type de logement</option>
                <option value="1">Chambre</option>
                <option value="2">Appartement</option>
                <option value="3">Maison</option>
              </select>
            </div>
            <div class="col-md-4">
              <select class="form-select border-0 py-3">
                <option selected>Ville</option>
                <option value="1">Lomé</option>
                <option value="2">Kara</option>
                <option value="3">Sokodé</option>
              </select>
            </div>
          </div>
        </div>
        <div class="col-md-2">
          <button class="btn btn-dark border-0 w-100 py-3">Rechercher</button>
        </div>
      </div>
    </div>
  </div>
</template>
