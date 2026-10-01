FROM php:8.2-fpm-alpine

# Installer les dépendances système nécessaires et les extensions PHP requises par Symfony
RUN apk add --no-cache \
    git \
    unzip \
    libintl \
    icu-dev \
    libzip-dev \
    bash \
    mysql-client \
    && docker-php-ext-configure intl \
    && docker-php-ext-install intl opcache pdo pdo_mysql zip

# Installer Composer globalement depuis l'image officielle
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Définir le dossier de travail dans le conteneur
WORKDIR /var/www/symfony

# Exposer le port par défaut de PHP-FPM
EXPOSE 9000

CMD ["php-fpm"]
