# initial step, build node artifacts
FROM node:lts-alpine3.15 as frontend_build
WORKDIR /app

# copy the folder
COPY . .
RUN npm install
RUN npm run prod


FROM php:8.1-fpm-alpine3.15

WORKDIR /var/www/ethera

# add the php extension installer and install extensions
ADD https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions /usr/local/bin/
RUN chmod +x /usr/local/bin/install-php-extensions
RUN install-php-extensions bz2 intl gmp mcrypt opcache pdo_mysql redis swoole xml zip @composer

# add required packets
RUN apk add git curl zip unzip

# copy the project folder and clone from previous step
COPY . .
RUN rm package.json package-lock.json
COPY --from=frontend_build /app/public/**/* ./public/
RUN rm -rf modules

# install composer dependencies
RUN echo "{\"github-oauth\": {\"github.com\": \"$(cat .private_repo_key)\"} }" > auth.json
RUN composer install --optimize-autoloader --no-dev --no-ansi --no-interaction --prefer-dist
RUN rm auth.json .private_repo_key
RUN apk del git

# configure secret keys
RUN php artisan key:generate
RUN php artisan crypter:gen-key

# laravel configuration optimization
RUN chmod -R 777 storage bootstrap/cache && \
    php artisan config:cache && \
    php artisan route:cache && \
    php artisan view:cache
