FROM php:8.3-apache

# Zapobieganie uruchamianiu usług
RUN echo '#!/bin/sh\nexit 0' > /usr/sbin/policy-rc.d

# Instalacja zależności systemowych
RUN apt-get update && apt-get install -y \
    curl ca-certificates gnupg \
    libxml2-dev \
    libcurl4-openssl-dev \
    libonig-dev \
    libzip-dev \
    libgd-dev \
    libbz2-dev \
    libicu-dev \
    git \
    nodejs \
    yarn \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Instalacja rozszerzeń PHP
RUN docker-php-ext-install \
    pdo_mysql \
    soap \
    curl \
    mbstring \
    bz2 \
    xml \
    gd \
    zip \
    intl

# Czyszczenie
RUN apt-get clean && rm -rf /var/lib/apt/lists/*
