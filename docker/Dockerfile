FROM php:5.6-apache
RUN apt-get update && apt-get install -y \
    libxml2-dev
    
RUN docker-php-ext-install -j$(nproc) xmlrpc
