<template>
    <footer class="site-footer">
        <!-- Icônes flottantes -->
        <span class="footer-icon icon-home w-50"></span>
        <span class="footer-icon icon-star w-50"></span>
        <span class="footer-icon icon-user w-50"></span>
        <span class="footer-icon icon-map w-50"></span>

        <div class="container">
            <div class="row">
                <div class="col-lg-4">
                    <!-- <div class="mb-5">
                        <h3 class="footer-heading mb-4">About Homeland</h3>
                        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Saepe pariatur reprehenderit vero atque, consequatur id ratione, et non dignissimos culpa? Ut veritatis, quos illum totam quis blanditiis, minima minus odio!</p>
                    </div> -->
                    <img :src="logo2" alt="logo2" class="img-fluid w-45 mb-4" />
                </div>
                <div class="col-lg-4 mb-5 mb-lg-0">
                <div class="row mb-5">
                    <div class="col-md-12">
                    <h3 class="footer-heading mb-4">Navigations</h3>
                    </div>
                    <div class="col-md-6 col-lg-6">
                    <ul class="list-unstyled">
                        <li><a href="#">Acceuil</a></li>
                        <li><a href="#">Publications</a></li>
                    </ul>
                    </div>
                    <div class="col-md-6 col-lg-6">
                    <ul class="list-unstyled">
                        <li><a href="#">A propos</a></li>
                        <!-- <li><a href="#">Privacy Policy</a></li> -->
                        <li><a href="#">Contact</a></li>
                        <!-- <li><a href="#">Terms</a></li> -->
                    </ul>
                    </div>
                </div>
                </div>

                <div class="col-lg-4 mb-5 mb-lg-0">
                <h3 class="footer-heading mb-4">Suivez nous</h3>
                    <div>
                        <a href="#" class="pl-0 pr-3"><span class="icon-facebook"></span></a>
                        <a href="#" class="pl-3 pr-3"><span class="icon-twitter"></span></a>
                        <a href="#" class="pl-3 pr-3"><span class="icon-instagram"></span></a>
                        <a href="#" class="pl-3 pr-3"><span class="icon-linkedin"></span></a>
                    </div>
                </div>
                
            </div>
            <div class="row pt-5 mt-5 text-center ">
                <div class="col-md-12">
                    <p class="text-light">
                        Copyright &copy; {{ currentYear }} Tout droits réserveés | Développé par
                        <!-- <i class="icon-heart text-danger" aria-hidden="true"></i>  -->
                        <a href="" target="_blank">PAKPALI essolisssam didier</a>
                    </p>
                </div>
            </div>
        </div>
    </footer>
</template>

<script setup>
    import logo2 from '@images/logo2.png'
    const currentYear = new Date().getFullYear()
</script>

<style scope>
    .site-footer {
        position: relative;
        background: linear-gradient(135deg, rgba(0,0,0,0.98), rgba(0,0,0,0.95) 70%, rgba(53, 99, 26, 0.996));
        color: #fffbfb;
        padding: 60px 0 20px;
        border-top: 2px solid rgba(89,165,44,0.4);
        overflow: hidden;
    }

    /* Effet de surbrillance verte subtile */
    .site-footer::before {
        content: "";
        position: absolute;
        top: -50%;
        left: -50%;
        width: 200%;
        height: 200%;
        background: radial-gradient(circle, rgba(89,165,44,0.15) 0%, transparent 60%);
        animation: glowMove 12s linear infinite;
    }

    @keyframes glowMove {
        0% { transform: translate(0, 0); }
        50% { transform: translate(30px, 20px); }
        100% { transform: translate(0, 0); }
    }

    /* Icônes flottantes futuristes */
    .footer-icon {
        position: absolute;
        font-size: 24px;
        color: rgba(193, 213, 181, 0.962);
        animation: floatIcons 8s infinite ease-in-out;
        opacity: 0.6;
    }

    .footer-icon:nth-child(1) { top: 20%; left: 5%; animation-delay: 0s; }
    .footer-icon:nth-child(2) { top: 40%; left: 90%; animation-delay: 2s; }
    .footer-icon:nth-child(3) { top: 70%; left: 10%; animation-delay: 4s; }
    .footer-icon:nth-child(4) { top: 85%; left: 80%; animation-delay: 6s; }

    @keyframes floatIcons {
        0% { transform: translateY(0) rotate(0deg); }
        50% { transform: translateY(-15px) rotate(10deg); }
        100% { transform: translateY(0) rotate(0deg); }
    }

    .site-footer a {
        color: #ffffff;
        transition: color 0.3s ease;
    }
    .site-footer a:hover {
        color: #35a516;
    }

</style>