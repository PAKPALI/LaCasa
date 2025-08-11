import { defineConfig } from 'vite';
import vue from '@vitejs/plugin-vue';
import laravel from 'laravel-vite-plugin';
import path from 'path';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/layouts/app.js'],
            refresh: true,
        }),
        vue(),
    ],
    resolve: {
        alias: {
            '@images': path.resolve(__dirname, 'resources/js/images'),
        },
    }
});

// Explication ligne par ligne :

// js
// Copier
// Modifier
// import { defineConfig } from 'vite'
// ➡️ On importe la fonction defineConfig de Vite, qui permet d’écrire une configuration proprement typée.

// js
// Copier
// Modifier
// import laravel from 'laravel-vite-plugin'
// ➡️ On importe le plugin Laravel qui permet à Vite de bien intégrer les fichiers Laravel (Blade, assets, etc.).

// js
// Copier
// Modifier
// import vue from '@vitejs/plugin-vue'
// ➡️ Ce plugin permet à Vite de comprendre les fichiers .vue (sinon il ne saurait pas les interpréter).

// js
// Copier
// Modifier
// export default defineConfig({
// ➡️ On exporte la configuration Vite avec la fonction defineConfig.

// js
// Copier
// Modifier
//     plugins: [
//         laravel({
//             input: ['resources/css/app.css', 'resources/js/app.js'],
//             refresh: true,
//         }),
// ➡️ Le plugin Laravel prend deux fichiers d’entrée :

// app.css : ton fichier de styles

// app.js : ton point d’entrée JavaScript (où Vue est initialisé)
// Et refresh: true permet à Laravel de recharger les fichiers automatiquement dans le navigateur pendant le développement.

// js
// Copier
// Modifier
//         vue(),
// ➡️ On ajoute le plugin Vue à la configuration.