FROM php:5.6-apache

RUN echo 'deb http://snapshot.debian.org/archive/debian/20230423T032533Z/ stretch main' \
  > /etc/apt/sources.list

RUN apt-get update
RUN apt-get install --no-install-recommends -y libpng-dev

RUN docker-php-ext-install gd pdo_mysql

RUN mkdir -p /var/local/alloc; chown www-data /var/local/alloc/
RUN for i in task client project invoice comment whatsnew logos search tmp; do \
  mkdir /var/local/alloc/${i} && \
  chmod 775 /var/local/alloc/${i} && \
  chgrp www-data /var/local/alloc/${i}; \
done

EXPOSE 80
