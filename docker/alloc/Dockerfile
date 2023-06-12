FROM php:7.4-apache

RUN apt-get update
RUN apt-get install --no-install-recommends -y libfreetype6-dev \
  libjpeg62-turbo-dev \
  libpng-dev \
  libc-client-dev \
  libkrb5-dev

RUN docker-php-ext-configure gd
RUN docker-php-ext-configure imap --with-kerberos --with-imap-ssl
RUN docker-php-ext-configure pdo_mysql
RUN docker-php-ext-install -j$(nproc) gd pdo_mysql imap

RUN mkdir -p /var/local/alloc; chown www-data /var/local/alloc/
RUN for i in task client project invoice comment whatsnew logos search tmp; do \
  mkdir /var/local/alloc/${i} && \
  chmod 775 /var/local/alloc/${i} && \
  chgrp www-data /var/local/alloc/${i}; \
  done

EXPOSE 80
