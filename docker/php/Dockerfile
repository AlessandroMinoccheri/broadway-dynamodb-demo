FROM php:7.4-fpm

RUN apt-get update && apt-get install -y curl zip unzip && apt-get install -y autoconf  && apt-get install -y build-essential \
    && apt-get install -y mariadb-client --no-install-recommends \
    && docker-php-ext-install pdo_mysql

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

WORKDIR /var/www

COPY entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

ENTRYPOINT [ "/usr/local/bin/entrypoint.sh" ]
CMD ["php-fpm"]