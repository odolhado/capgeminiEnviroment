FROM php:7.0-fpm

RUN apt-get update

    # Install PHP Extensions

RUN apt-get install -y libpq-dev \
    && docker-php-ext-configure pgsql -with-pgsql=/usr/local/pgsql \
    && docker-php-ext-install pdo pdo_pgsql pgsql

RUN docker-php-ext-install zip
RUN docker-php-ext-install mbstring
RUN docker-php-ext-install opcache
RUN docker-php-ext-install bcmath


    # Install tools
RUN apt-get update -y && apt-get install -y \
    vim-nox \
    git \
    nano \
    wget \
    socat \
    build-essential \
    libicu-dev \
    curl \
    libcurl4-openssl-dev \
    zlib1g-dev \
    libxslt1-dev \
    libxml2-dev \
    libssl-dev


    # Install Composer
RUN curl -sS https://getcomposer.org/installer | php && mv composer.phar /usr/local/bin/composer

    # Install PHP Configurations
ADD datetime.ini /usr/local/etc/php/conf.d/
ADD execution.ini /usr/local/etc/php/conf.d/
COPY php-fpm.conf /usr/local/etc/

    # Add user for cache and logs folder priviliges
RUN useradd -ms /bin/bash capgemini
USER capgemini

    # Set path of workdir 
WORKDIR /var/www






