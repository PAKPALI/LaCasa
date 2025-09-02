<template>
    <!-- Navbar Start -->
        <div class="container-fluid nav-bar bg-transparent">
            <!-- Icônes flottantes -->
            <span class="navbar-icon icon-home w-25"></span>
            <span class="navbar-icon icon-star w-25"></span>
            <span class="navbar-icon icon-user w-25"></span>
            <span class="navbar-icon icon-map w-25"></span>
            <nav class="navbar fixed-top navbar-expand-lg bg-dark navbar-light py-0 px-4">
                <a href="index.html" class="navbar-brand bg-light d-flex align-items-center text-center">
                    <div class="icon p-3 me-2">
                        <img class="img-fluid" :src="logo2" alt="Icon" style="width: 30px; height: 30px;">
                    </div>
                    <h1 class="m-0 text-primary">La Casa</h1>
                </a>
                <button type="button" class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarCollapse">
                    <div class="navbar-nav ms-auto">
                        <li class="nav-item"><router-link to='/home' class="nav-link text-white"@click = "click($event)" @mouseenter="hoverLink($event)" @mouseleave="leaveLink($event)"> Acceuil </router-link></li>
                        <li class="nav-item"><router-link to='/publication' class="nav-link text-white" @mouseenter="hoverLink($event)"@mouseleave="leaveLink($event)"> Publications </router-link></li>
                        <li class="nav-item"><router-link to='/admin' class="nav-link text-white" @mouseenter="hoverLink($event)"@mouseleave="leaveLink($event)"> Admin</router-link></li>
                        <li class="nav-item"><router-link to='' class="nav-link text-white" @mouseenter="hoverLink($event)"@mouseleave="leaveLink($event)"> A propos</router-link></li>
                        <li><a href="" class="nav-item nav-link"></a></li>
                        <!-- <div class="nav-item dropdown">
                            <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">Property</a>
                            <div class="dropdown-menu rounded-0 m-0">
                                <a href="property-list.html" class="dropdown-item">Property List</a>
                                <a href="property-type.html" class="dropdown-item">Property Type</a>
                                <a href="property-agent.html" class="dropdown-item">Property Agent</a>
                            </div>
                        </div> -->
                        <div class="nav-item dropdown">
                            <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">Pages</a>
                            <div class="dropdown-menu rounded-0 m-0">
                                <!-- <a href="testimonial.html" class="dropdown-item">Testimonial</a> -->
                                <a href="" class="dropdown-item">404 Error</a>
                            </div>
                        </div>
                        <!-- <a href="" class="nav-item nav-link">Contact</a> -->
                    </div>
                    <!-- <a href="" class="btn btn-primary px-3 d-none d-lg-flex">Add Property</a> -->
                </div>
            </nav>
        </div>
    <!-- Navbar End -->

</template>

<script setup>
    import logo from '@images/logo.jpeg'
    import logo2 from '@images/logo2.png'
    // import ParticleJs from '../partial/Particles.vue'

    function hoverLink(e) {
        e.target.classList.remove('text-white')
        e.target.classList.add('text-primary')
    }

    function leaveLink(e) {
        e.target.classList.remove('text-primary')
        e.target.classList.add('text-white')
    }

    function click(e) {
        e.target.classList.remove('text-white')
        e.target.classList.add('text-primary')
    }
</script>

<style scoped>

</style>

