# -------------------------------
# Stage 1: Build (Node + Composer)
# -------------------------------
FROM php:8.2-fpm AS build

# Installer dépendances système
RUN apt-get update && apt-get install -y \
    git unzip libonig-dev libzip-dev curl npm && \
    docker-php-ext-install pdo_mysql mbstring zip exif pcntl bcmath

# Installer Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /app

# Installer dépendances PHP
COPY composer.json composer.lock ./
RUN composer install --no-dev --optimize-autoloader

# Installer dépendances JS
COPY package*.json ./
RUN npm install

# Copier le reste du projet et builder les assets Vite
COPY . .
RUN npm run build

# -------------------------------
# Stage 2: Production (Nginx + PHP-FPM)
# -------------------------------
FROM php:8.2-fpm

# Installer PHP extensions + Nginx
RUN apt-get update && apt-get install -y \
    nginx libonig-dev libzip-dev unzip supervisor && \
    docker-php-ext-install pdo_mysql mbstring zip exif pcntl bcmath && \
    rm -rf /var/lib/apt/lists/*

WORKDIR /app

# Copier projet compilé depuis le build
COPY --from=build /app /app

# Supprimer config nginx par défaut et copier la nôtre
RUN rm /etc/nginx/sites-enabled/default
COPY ./deploy/nginx.conf /etc/nginx/conf.d/default.conf

# Permissions
RUN chown -R www-data:www-data /app/storage /app/bootstrap/cache

# Exposer le port HTTP
EXPOSE 80

# Supervisord pour lancer PHP-FPM + Nginx
CMD ["sh", "-c", "php-fpm -D && nginx -g 'daemon off;'"]
