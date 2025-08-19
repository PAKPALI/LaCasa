# -------------------------------
# Stage 1: Build (Node + Composer)
# -------------------------------
FROM php:8.2-fpm AS build

# Installer les dépendances système pour Laravel & Node
RUN apt-get update && apt-get install -y \
    git unzip libonig-dev libzip-dev curl npm && \
    docker-php-ext-install pdo_mysql mbstring zip exif pcntl bcmath

# Installer Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /app

# Copier les fichiers composer.json et composer.lock pour installer les dépendances PHP
COPY composer.json composer.lock ./
RUN composer install --no-dev --optimize-autoloader

# Copier package.json et package-lock.json pour Node
COPY package*.json ./
RUN npm install
COPY . .
RUN npm run build

# -------------------------------
# Stage 2: Production
# -------------------------------
FROM php:8.2-fpm

# Installer uniquement les extensions PHP nécessaires
RUN apt-get update && apt-get install -y \
    libonig-dev libzip-dev unzip && \
    docker-php-ext-install pdo_mysql mbstring zip exif pcntl bcmath

WORKDIR /app

# Copier les fichiers PHP et les dépendances depuis le stage 1
COPY --from=build /app /app

# Assurer les permissions sur storage et bootstrap/cache
RUN chown -R www-data:www-data /app/storage /app/bootstrap/cache

# Exposer le port utilisé par PHP-FPM
EXPOSE 9000

# Commande de démarrage
CMD ["php-fpm"]
