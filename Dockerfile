FROM php:7.3-alpine

# Setup environment
RUN apk update && \
    apk add --virtual .build-deps --update --no-cache openssl ca-certificates && \
    update-ca-certificates && \
    apk del .build-deps

# Install Composer
ENV COMPOSER_ALLOW_SUPERUSER=1 \
    PATH="${PATH}:/root/.composer/vendor/bin"
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Install Phinder
RUN mkdir /phinder
COPY . /phinder
RUN composer global config repositories.phinder path /phinder && \
    composer global require sider/phinder:dev-master

WORKDIR /workdir

ENTRYPOINT ["phinder"]
