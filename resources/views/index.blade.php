<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="google-site-verification" content="wh-St2vOS2B9axsBRSJJicLCqx_eHDXqvEE220bl5TY" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- SEO de base -->
    <title>LaCasa – Trouvez un logement au Togo | Bouger simplement</title>
    <meta name="description" content="LaCasa est la plateforme moderne pour trouver un logement au Togo et en Afrique : chambres, appartements, maisons, villas. Annonces fiables, filtrage rapide, démarcheurs vérifiés. Bouger simplement.">

    <meta name="keywords" content="location Togo, maison à louer Lomé, logement Togo, logement Afrique, location Afrique, appartement à louer, annonces immobilières Togo, annonces Afrique, chambre salon, villa à louer, plateforme immobilière Afrique, LaCasa">
    <meta name="author" content="PAKPALI Essolissam Didier">

    <!-- SEO géographique (important !) -->
    <meta name="geo.region" content="TG">
    <meta name="geo.placename" content="Togo">
    <meta name="geo.position" content="8.619543;-0.824782">
    <meta name="ICBM" content="8.619543, -0.824782">

    <!-- Open Graph (Afrique + Togo) -->
    <meta property="og:title" content="LaCasa – Location de logements au Togo & Afrique">
    <meta property="og:description" content="Découvrez des annonces fiables de logements au Togo et en Afrique. Appartements, villas, maisons. Plateforme simple et moderne.">
    <meta property="og:image" content="{{ config('app.url') }}/logo/logo.jpeg">
    <meta property="og:url" content="{{ config('app.url') }}">
    <meta property="og:type" content="website">
    <meta property="og:locale" content="fr_FR">
    <meta property="og:locale:alternate" content="en_US">

    <!-- 6. Twitter Card -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="LaCasa – Logements au Togo et en Afrique">
    <meta name="twitter:description" content="Plateforme moderne pour trouver un logement au Togo & Afrique. Simple, rapide et fiable.">
    <meta name="twitter:image" content="{{ config('app.url') }}/logo/logo.jpeg">

    <!-- Logo du site -->
    <meta name="logo" content="{{ config('app.url') }}/logo/logo.jpeg">

    <!-- Assets locaux -->
    <!-- Favicon -->
    <link href="img/favicon.ico" rel="icon">

    <!-- Google Web Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Heebo:wght@400;500;600&family=Inter:wght@700;800&display=swap" rel="stylesheet">
    
    <!-- Icon Font Stylesheet -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">


    <!-- Libraries Stylesheet -->
    <link href="{{ asset('template/lib/animate/animate.min.css') }}" rel="stylesheet">
    <link href="{{ asset('template/lib/owlcarousel/assets/owl.carousel.min.css') }}" rel="stylesheet">

    <!-- Customized Bootstrap Stylesheet -->
    <link href="{{ asset('template/css/bootstrap.min.css') }}" rel="stylesheet">

    <!-- Template Stylesheet -->
    <link href="{{ asset('template/css/style.css') }}" rel="stylesheet">

    <!-- Vite -->
    @vite(['resources/css/app2.css', 'resources/js/layouts/app2.js'])
</head>
<body>
    <div id="app"></div>

    <!-- Scripts locaux -->
    <!-- <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script> -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('template/lib/wow/wow.min.js') }}"></script>
    <script src="{{ asset('template/lib/easing/easing.min.js') }}"></script>
    <script src="{{ asset('template/lib/waypoints/waypoints.min.js') }}"></script>
    <script src="{{ asset('template/lib/owlcarousel/owl.carousel.min.js') }}"></script>

    <!-- Template Javascript -->
    <script src="{{ asset('template/js/main.js') }}"></script>

    <!-- 7. JSON-LD (Google Rich Snippet) -->
    @verbatim
        <script type="application/ld+json">
        {
            "@context": "https://schema.org",
            "@type": "RealEstateAgent",
            "name": "LaCasa",
            "url": "https://ton-site.com",
            "logo": "https://ton-site.com/logo/logo.jpeg",
            "description": "Plateforme de location immobilière au Togo et en Afrique.",
            "areaServed": ["Togo", "Afrique de l'Ouest", "Afrique"],
            "address": {
                "@type": "PostalAddress",
                "addressCountry": "TG"
            }
        }
        </script>
    @endverbatim

</body>
</html>
