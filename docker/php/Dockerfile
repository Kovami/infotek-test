FROM php:8.4-fpm

ARG USER_ID
ARG GROUP_ID

RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    && docker-php-ext-install \
    pdo_mysql \
    mbstring \
    exif \
    pcntl \
    bcmath \
    gd \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

RUN if [ ${USER_ID} != 0 ]; then \
    deluser --remove-home www-data; \
    addgroup --gid ${GROUP_ID} www-data; \
    adduser --uid ${USER_ID} --gid ${GROUP_ID} --disabled-password --gecos "" www-data; \
    fi

COPY ./php.ini /usr/local/etc/php/conf.d/custom.ini

RUN mkdir -p /var/www/html/protected/runtime /var/www/html/assets \
    && chown -R www-data:www-data /var/www/html

USER www-data

WORKDIR /var/www/html

CMD ["php-fpm"]