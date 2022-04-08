FROM php:7.4.27-fpm

RUN apt-get update
RUN apt-get install -y --force-yes cron
RUN apt-get install -y --force-yes g++
RUN apt-get install -y --force-yes gettext
RUN apt-get install -y --force-yes libicu-dev
RUN apt-get install -y --force-yes openssl
RUN apt-get install -y --force-yes libc-client-dev
RUN apt-get install -y --force-yes libkrb5-dev
RUN apt-get install -y --force-yes libxml2-dev
RUN apt-get install -y --force-yes libfreetype6-dev
RUN apt-get install -y --force-yes libgd-dev
RUN apt-get install -y --force-yes libmcrypt-dev
RUN apt-get install -y --force-yes libmcrypt-dev
RUN apt-get install -y --force-yes bzip2
RUN apt-get install -y --force-yes libtidy-dev
RUN apt-get install -y --force-yes libcurl4-openssl-dev
RUN apt-get install -y --force-yes libz-dev
RUN apt-get install -y --force-yes libmemcached-dev
RUN apt-get install -y --force-yes libxslt-dev
RUN apt-get install -y --force-yes build-essential
RUN apt-get install -y --force-yes libpng-dev
RUN apt-get install -y --force-yes libjpeg62-turbo-dev
RUN apt-get install -y --force-yes libfreetype6-dev
RUN apt-get install -y --force-yes locales
RUN apt-get install -y --force-yes zip
RUN apt-get install -y --force-yes libzip-dev
RUN apt-get install -y --force-yes jpegoptim optipng pngquant gifsicle
RUN apt-get install -y --force-yes vim
RUN apt-get install -y --force-yes unzip
RUN apt-get install -y --force-yes git
RUN apt-get install -y --force-yes curl
RUN apt-get install -y --force-yes libbz2-dev


ADD conf.d/custom.ini /usr/etc/php/conf.d/php.ini

RUN docker-php-ext-install mysqli
RUN docker-php-ext-install bcmath
RUN docker-php-ext-install ctype
RUN docker-php-ext-install bz2
RUN docker-php-ext-install calendar
RUN docker-php-ext-install dba
RUN docker-php-ext-install exif
RUN docker-php-ext-install fileinfo
RUN docker-php-ext-install gd
RUN docker-php-ext-install gettext
RUN docker-php-ext-install intl
RUN docker-php-ext-install mysqli pdo pdo_mysql
RUN docker-php-ext-install soap
RUN docker-php-ext-install tidy
RUN docker-php-ext-install tokenizer
RUN docker-php-ext-install xmlrpc
RUN docker-php-ext-install xsl
RUN docker-php-ext-install json
RUN docker-php-ext-install sockets
RUN docker-php-ext-install simplexml
RUN docker-php-ext-install pcntl

RUN docker-php-ext-configure zip
RUN docker-php-ext-install zip

RUN docker-php-ext-enable mysqli
RUN docker-php-ext-enable simplexml

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer