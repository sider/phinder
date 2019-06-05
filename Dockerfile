FROM php:7.3-alpine

# Setup environment
RUN apk update && \
    apk add --virtual .build-deps --update --no-cache openssl ca-certificates && \
    update-ca-certificates

# Install Composer
ENV COMPOSER_ALLOW_SUPERUSER=1 \
    PATH="${PATH}:/root/.composer/vendor/bin"
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Install Phinder
ENV PHINDER_VERSION 0.9.1
RUN composer global require "sider/phinder:${PHINDER_VERSION}"

RUN apk del .build-deps

WORKDIR /workdir

ENTRYPOINT ["phinder"]
