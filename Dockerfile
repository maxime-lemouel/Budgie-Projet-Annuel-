# Utilise l'image officielle PHP + Apache
FROM php:8.2-apache

# Installer les dépendances nécessaires pour PostgreSQL et activer modules
RUN apt-get update && apt-get install -y --no-install-recommends \
    libpq-dev \
    unzip \
    git \
    libyaml-dev \
    && pecl install yaml \
    && docker-php-ext-install pdo pdo_pgsql pgsql \
    && docker-php-ext-enable yaml \
    && a2enmod rewrite ssl \
    && rm -rf /var/lib/apt/lists/*

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

RUN chown -R www-data:www-data /var/www/html

EXPOSE 80
EXPOSE 443
