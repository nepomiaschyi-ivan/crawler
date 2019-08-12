FROM ubuntu:18.04
ENV DEBIAN_FRONTEND noninteractive
RUN  apt-get update && apt-get install -y locales && locale-gen en_US.UTF-8 && apt-get install -y software-properties-common && LC_ALL=en_US.UTF-8 add-apt-repository -y ppa:ondrej/php &&  apt-get update && apt-get upgrade -y
RUN  apt-get update && apt-get install -y\
        wget \
        git \
        curl \
        autoconf \
        libmcrypt-dev \
        php-pear \
        php7.3-xdebug \
        php7.3-fpm \
        php7.3-dev \
        php7.3-cli \
        php7.3-opcache \
        php7.3-curl \
        php7.3-xml \
        php7.3-mbstring  \
        php7.3-mysql \
        php7.3-gd \
        php7.3-zip \
        php7.3-bcmath

RUN curl https://getcomposer.org/installer | php -- && mv composer.phar /usr/local/bin/composer && chmod +x /usr/local/bin/composer
RUN pecl channel-update pecl.php.net && pecl install xdebug
#RUN wget https://phar.phpunit.de/phpunit-8.phar && chmod +x phpunit-8.phar && mv phpunit-8.phar /usr/local/bin/phpunit

WORKDIR /var/www/html

EXPOSE 9000

CMD ["php-fpm7.3", "-R"]