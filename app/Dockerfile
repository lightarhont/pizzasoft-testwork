FROM php:8.2-fpm

ARG XDEBUG_VERSION="xdebug-3.3.0"

WORKDIR /var/www/html/

COPY . /var/www/html/

RUN apt-get update && \
    apt-get install -y zip unzip && \
    apt-get install -y libzip-dev && \
    docker-php-ext-install pdo zip pdo_mysql

RUN yes | pecl install ${XDEBUG_VERSION} \
    && echo "zend_extension=$(find /usr/local/lib/php/extensions/ -name xdebug.so)" > /usr/local/etc/php/conf.d/xdebug.ini \
    && echo "xdebug.remote_enable=1" >> /usr/local/etc/php/conf.d/xdebug.ini \
    #&& echo "xdebug.remote_handler=dbgp" >> /usr/local/etc/php/conf.d/xdebug.ini \
    #&& echo "xdebug.remote_mode=req" >> /usr/local/etc/php/conf.d/xdebug.ini \
    && echo "xdebug.remote_host=172.17.0.1" >> /usr/local/etc/php/conf.d/xdebug.ini \
    && echo "xdebug.client_port=9000" >> /usr/local/etc/php/conf.d/xdebug.ini \
    && echo "xdebug.remote_connect_back=1" >> /usr/local/etc/php/conf.d/xdebug.ini \
    && echo "xdebug.remote_cookie_expire_time=36000" >> /usr/local/etc/php/conf.d/xdebug.ini
    #&& echo "xdebug.remote_autostart=off" >> /usr/local/etc/php/conf.d/xdebug.ini

RUN useradd -m mikhail && echo "mikhail:mikhail" | chpasswd