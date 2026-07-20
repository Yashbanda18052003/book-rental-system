FROM php:8.2-cli

ENV COMPOSER_ALLOW_SUPERUSER=1

RUN apt-get update && apt-get install -y \
    git \
    unzip \
    zip \
    libzip-dev \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    libonig-dev \
    libxml2-dev \
    libicu-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install \
        gd \
        pdo \
        pdo_mysql \
        mbstring \
        zip \
        intl \
        exif

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# Copy the ENTIRE project first
COPY . .

# Then install dependencies
RUN pwd && \
    ls -la && \
    echo "========== /var/www/html ==========" && \
    ls -la /var/www/html && \
    echo "========== artisan ==========" && \
    cat artisan && \
    composer install \
    --no-dev \
    --optimize-autoloader \
    --no-interaction

EXPOSE 8000

CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]