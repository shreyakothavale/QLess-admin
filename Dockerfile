FROM php:7.4-apache

WORKDIR /admin

RUN docker-php-ext-install mysqli

EXPOSE 80