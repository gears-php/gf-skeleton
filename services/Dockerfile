####################################################################
### The base PHP and Nginx images for development and production ###
####################################################################

# Build argument: App environment (dev, prod, test)
ARG APP_ENV=dev

# Build argument: Image tag
ARG IMAGE_TAG=8.3-fpm

###############################
### Stage: Base Nginx image ###
###############################
FROM nginx:1.26 as nginx-dev

# Copy scripts and configs into image filesystem
COPY nginx/ /

# Set permissions for scripts
RUN chmod +x /docker-entrypoint.sh

# Change entrypoint
ENTRYPOINT ["/docker-entrypoint.sh"]

# Start Nginx
CMD ["nginx", "-g", "daemon off;"]


#############################
### Stage: Base PHP image ###
#############################
FROM php:$IMAGE_TAG as php-dev

# Installs libraries
RUN apt-get update && apt-get install -y \
    git \
    zip \
    unzip \
    zlib1g-dev \
    libzip-dev \
    libfreetype6-dev \
    libjpeg-dev \
    libwebp-dev \
    libpng-dev \
    libicu-dev

# Installs PHP extensions
RUN docker-php-ext-configure gd --with-freetype=/usr/include/ --with-jpeg=/usr/include/ --with-webp=/usr/include/ \
    && docker-php-ext-install \
    bcmath \
    intl \
#    mcrypt \
#    pdo_mysql \
    gd \
    zip \
    opcache

# Set PATHs
ENV PATH=/app:/app/vendor/bin:/root/.composer/vendor/bin:$PATH

# Installs Composer
ENV COMPOSER_ALLOW_SUPERUSER=1
ARG COMPOSER_VERSION=2.7.7
RUN curl -sS https://getcomposer.org/installer | php -- \
        --filename=composer \
        --install-dir=/usr/local/bin \
        --version=${COMPOSER_VERSION} \
    && composer clear-cache

# Set up XDebug
ARG XDEBUG_ENABLED=false
RUN if $XDEBUG_ENABLED; then pecl install xdebug \
    && docker-php-ext-enable xdebug \
    && echo "xdebug.mode=debug" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
    && echo "xdebug.max_nesting_level=1000" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
    ; fi

# Copy custom scripts and configs
COPY php/custom.ini /usr/local/etc/php/conf.d

ARG APP_ENV

# Display PHP errors depending on environment
RUN if [ "$APP_ENV" = "dev" ]; then sed -i 's/display_errors = Off/display_errors = On/g' /usr/local/etc/php/conf.d/custom.ini; fi

# Expand the rights for www-data user
RUN usermod -u 1000 www-data

# Set the working directory
WORKDIR /app

# Build argument: Built-in variable for app version
ARG APP_VERSION="1111 Thu Mar 20 00:00:00 2020 +0000"
# Build argument: Built-in variable for active git branch name
ARG BRANCH=no-branch

# Application version (built in the image)
#ENV AE_APP_VERSION=$APP_VERSION
# Active git branch name (built in the image)
#ENV AE_ACTIVE_BRANCH=$BRANCH

################################################################################
### Stage: Interim builder image for Composer dependencies and cache warm-up ###
################################################################################
FROM php-dev as builder-composer

# Copies SSH key
COPY base/ssh/id_rsa /root/.ssh/id_rsa

# Prepares SSH key
RUN chmod 0600 /root/.ssh/id_rsa

# Copies Composer files
COPY php/app/composer.* /app/

# Loads vendors and prepares binaries (this should be cached if Composer files have not been changed)
RUN composer install --no-dev --no-scripts

# Creating missing cache folders
RUN mkdir -p var/cache

# Copies all files
COPY php/app /app

# Runs Composer related scripts and optimizes the autoloader
RUN composer install --no-dev --classmap-authoritative

##########################################################
### Stage: Interim builder image for Yarn dependencies ###
##########################################################
FROM node:16.13-alpine as builder-yarn

# Sets the working directory
WORKDIR /app

# Installs Git
RUN apk --update add git less openssh && \
    rm -rf /var/lib/apt/lists/* && \
    rm /var/cache/apk/*

# Copies frontend-related files
COPY php/app/package.json /app/
COPY php/app/yarn.lock /app/

# Installs Yarn dependencies
RUN yarn install

##############################################################
### Stage: Interim builder image for building the frontend ###
##############################################################
FROM builder-yarn as builder-frontend

# Copies frontend source
COPY php/app/frontend /app/frontend

# Copies vendor assets
#COPY --from=builder-composer /app/vendor/sylius/ui-bundle/Resources/private \
#    /app/vendor/sylius/ui-bundle/Resources/private

# Build argument: Build environment (dev, prod, test)
ARG BUILD_ENV

# Warms up the cache
RUN yarn run build:$BUILD_ENV
