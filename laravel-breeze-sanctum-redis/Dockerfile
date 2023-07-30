FROM php:8.1.19-fpm

# Arguments defined in docker-compose.yml
ARG user
ARG uid

RUN docker-php-ext-install pdo_mysql
RUN docker-php-ext-install pdo
RUN docker-php-ext-install mysqli
RUN docker-php-ext-install sockets
RUN docker-php-ext-install bcmath
RUN docker-php-ext-install exif
RUN apt-get update -y && apt-get install -y openssl unzip git
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN composer --version
RUN apt-get update && apt-get install -y libzip-dev \
        libfreetype6-dev \
        libjpeg62-turbo-dev \
        libmcrypt-dev \
        libpng-dev \
    && docker-php-ext-install -j$(nproc) iconv \
    && docker-php-ext-configure gd --with-jpeg=/usr/include/ --with-freetype=/usr/include/ \
    && docker-php-ext-install -j$(nproc) gd \ 
	&& docker-php-ext-install zip 

# Redis
RUN apt-get install -y redis-tools
# RUN pecl install redis \
#     && docker-php-ext-enable redis


# Node.js and NPM
RUN curl -sL https://deb.nodesource.com/setup_18.x | bash - && apt-get install -y nodejs

# laravel environment
# ----------------------
RUN mkdir -p /var/www
WORKDIR /var/www
COPY ./ /var/www/

# Create system user to run Composer and Artisan Commands
RUN useradd -G www-data,root -u $uid -d /home/$user $user
RUN mkdir -p /home/$user/.composer && \
    chown -R $user:$user /home/$user

# Set working directory
WORKDIR /var/www

#ref https://www.digitalocean.com/community/tutorials/how-to-install-and-set-up-laravel-with-docker-compose-on-ubuntu-22-04