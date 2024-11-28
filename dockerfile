FROM php:8.2-fpm

RUN apt-get update && apt-get install -y \
    libfreetype-dev \
    libjpeg62-turbo-dev \
    libpng-dev \
    unzip libzip-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) gd \
    && docker-php-ext-install pdo pdo_mysql zip

WORKDIR /var/www/html

COPY . .

# Si vous avez besoin de COMPOSER_ALLOW_SUPERUSER, décommentez la ligne suivante
# ENV COMPOSER_ALLOW_SUPERUSER=1

# Installez Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Installez Symfony CLI
RUN curl -1sLf https://dl.cloudsmith.io/public/symfony/stable/setup.deb.sh | bash - &&\
    apt-get install -y symfony-cli

# Installez les dépendances PHP
RUN composer install

ENTRYPOINT [ "bash", "./api-script.sh" ]