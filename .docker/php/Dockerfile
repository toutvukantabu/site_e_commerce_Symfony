# Utilisation de l'image officielle PHP avec Apache
FROM php:8.2-apache

# Installation des packages nécessaires
RUN apt-get update && apt-get install -y --no-install-recommends \
        locales \
        apt-utils \
        git \
        libicu-dev \
        g++ \
        libpng-dev \
        libxml2-dev \
        libzip-dev \
        libonig-dev \
        libxslt-dev \
    && echo "en_US.UTF-8 UTF-8" > /etc/locale.gen \
    && echo "fr_FR.UTF-8 UTF-8" >> /etc/locale.gen \
    && locale-gen

# Installation de Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer --disable-tls

# Installation et configuration des extensions PHP
RUN docker-php-ext-configure intl \
    && docker-php-ext-install \
        pdo \
        pdo_mysql \
        gd \
        opcache \
        intl \
        zip \
        calendar \
        dom \
        mbstring \
        xsl \
    && pecl install apcu \
    && docker-php-ext-enable apcu

# Copie de l'application dans le conteneur
COPY --chown=www-data:www-data . /var/www/

# Configuration du Virtual Host d'Apache
COPY vhosts/vhosts.conf /etc/apache2/sites-available/000-default.conf

# Variables d'environnement pour Composer et permission de l'exécuter en tant que super utilisateur
ENV COMPOSER_ALLOW_SUPERUSER=1

# Utilisation de l'utilisateur www-data pour les processus suivants
USER www-data

# Répertoire de travail
WORKDIR /var/www/

# Exposition du port d'Apache
EXPOSE 80
