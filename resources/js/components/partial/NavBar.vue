<template>
    <!-- <nav class="navbar bg-dark text-white p-3">
    <h5 class="mb-0">Mon Application Vue</h5>
  </nav> -->
    <div class="site-mobile-menu">
        <div class="site-mobile-menu-header">
            <div class="site-mobile-menu-close mt-3">
                <span class="icon-close2 js-menu-toggle"></span>
            </div>
        </div>
        <div class="site-mobile-menu-body"></div>
    </div> <!-- .site-mobile-menu -->

    <div class="site-navbar mt-4">
        <div class="container py-1">
            <div class="row align-items-center">
                <div class="col-8 col-md-8 col-lg-4">
                    <h1 class="mb-0 text-light">LaCasa<a href="index.html" class="text-white h2 mb-0">
                        
                        <!-- <img :src="logo" alt="logo" class="img-fluid mb-4" /> -->
                        <strong><span class="text-danger">.</span></strong></a>
                    </h1>
                </div>
                <div class="col-4 col-md-4 col-lg-8">
                    <nav class="site-navigation text-right text-md-right" role="navigation">

                        <div class="d-inline-block d-lg-none ml-md-0 mr-auto py-3"><a href="#"
                                class="site-menu-toggle js-menu-toggle text-white"><span
                                    class="icon-menu h3"></span></a></div>

                        <ul class="site-menu js-clone-nav d-none d-lg-block">
                            <li class="active"><router-link to='/home'> Acceuil </router-link></li>
                            <!-- <li><a href="buy.html">Buy</a></li> -->
                            <!-- <li><a href="rent.html">Rent</a></li> -->
                            <li class="has-children">
                                <a href="properties.html">Properties</a>
                                <ul class="dropdown arrow-top">
                                    <li><a href="#">Condo</a></li>
                                    <li><a href="#">Property Land</a></li>
                                    <li><a href="#">Commercial Building</a></li>
                                    <li class="has-children">
                                        <a href="#">Sub Menu</a>
                                        <ul class="dropdown">
                                            <li><a href="#">Menu One</a></li>
                                            <li><a href="#">Menu Two</a></li>
                                            <li><a href="#">Menu Three</a></li>
                                        </ul>
                                    </li>
                                </ul>
                            </li>
                            <!-- <li><a href="blog.html">Blog</a></li> -->
                            <li><a href="about.html">A propos</a></li>
                            <li><a href="contact.html">Contact</a></li>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    </div>

</template>

<script setup>
    import logo from '@images/logo.jpeg'

</script>

<style scoped>
/* Style local pour la navbar si nécessaire */
</style>
