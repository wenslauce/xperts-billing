FROM php:8.4-fpm-alpine AS build

RUN apk add --no-cache \
    nginx \
    nodejs \
    npm \
    curl \
    git \
    zip \
    unzip \
    libzip-dev \
    oniguruma-dev \
    libxml2-dev \
    libpng-dev \
    freetype-dev \
    libjpeg-turbo-dev \
    mysql-client \
    redis \
    icu-dev \
    icu-libs \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install \
    pdo \
    pdo_mysql \
    mbstring \
    exif \
    pcntl \
    bcmath \
    gd \
    zip \
    intl \
    opcache \
    && apk add --no-cache $PHPIZE_DEPS \
    && pecl install redis \
    && docker-php-ext-enable redis

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /app

COPY composer.json composer.lock ./
RUN composer install --no-dev --no-interaction --no-progress --optimize-autoloader --ignore-platform-reqs

COPY . .

RUN npm ci && npm run build

# Generate APP_KEY for the build - this is just to enable config caching
RUN cp .env.example .env \
    && php artisan key:generate \
    && php artisan storage:link || true

RUN chmod -R 777 storage bootstrap/cache

FROM php:8.4-fpm-alpine

RUN apk add --no-cache \
    nginx \
    curl \
    supervisor \
    libzip-dev \
    oniguruma-dev \
    libxml2-dev \
    libpng-dev \
    freetype-dev \
    libjpeg-turbo-dev \
    mysql-client \
    icu-dev \
    icu-libs \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install \
    pdo \
    pdo_mysql \
    mbstring \
    exif \
    pcntl \
    bcmath \
    gd \
    zip \
    intl \
    opcache \
    && apk add --no-cache $PHPIZE_DEPS \
    && pecl install redis \
    && docker-php-ext-enable redis

COPY --from=build /app /app
COPY --from=build /usr/bin/composer /usr/bin/composer

COPY docker/nginx.conf /etc/nginx/http.d/default.conf
COPY docker/php.ini /usr/local/etc/php/conf.d/app.ini
COPY docker/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

WORKDIR /app

RUN chmod -R 777 storage bootstrap/cache \
    && mkdir -p /var/log/nginx /var/cache/nginx /var/log/supervisor /var/run

EXPOSE 80

CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]