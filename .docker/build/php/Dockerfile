FROM php:7.1-fpm

RUN apt-get update && apt-get install -y \
    librabbitmq-dev \
    openssl \
    git \
    unzip

RUN mkdir -p /run/php

RUN curl -sSk https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename composer
#RUN chmod +x /usr/local/bin/composer
#RUN composer install --no-interaction --no-progress

RUN ln -snf /usr/share/zoneinfo/Europe/Warsaw /etc/localtime
RUN ln -snf /usr/share/zoneinfo/Europe/Warsaw /etc/localtime && echo "Europe/Warsaw" > /etc/timezone \
&& printf '[PHP]\ndate.timezone = "%s"\n', "Europe/Warsaw" > /usr/local/etc/php/conf.d/tzone.ini \
&& "date"

RUN docker-php-ext-install pdo pdo_mysql

RUN pecl install xdebug amqp \
&& docker-php-ext-enable xdebug amqp \
&& echo "error_reporting = E_ALL" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
&& echo "display_startup_errors = On" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
&& echo "display_errors = On" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
&& echo "xdebug.remote_enable=1" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
&& echo "xdebug.remote_connect_back=1" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
&& echo "xdebug.idekey=\"PHPSTORM\"" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
&& echo "xdebug.remote_port=9001" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini

ADD conf/www.conf /etc/php/7.1/fpm/pool.d/www.conf
ADD conf/php-fpm.conf /etc/php/7.1/fpm/php-fpm.conf

#RUN wget --no-check-certificate https://phar.phpunit.de/phpunit-6.5.3.phar && \
#    mv phpunit*.phar phpunit.phar && \
#    chmod +x phpunit.phar && \
#    mv phpunit.phar /usr/local/bin/phpunit

WORKDIR /var/www/html/symfony

EXPOSE 9000

CMD ["php-fpm"]
