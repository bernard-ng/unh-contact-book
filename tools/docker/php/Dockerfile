FROM grafikart/php:php8.1-1

# JPEG - https://stackoverflow.com/a/52892313/2190689
RUN apt-get update && apt-get install -y \
        libfreetype6-dev \
        libjpeg62-turbo-dev \
        libpng-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd \
