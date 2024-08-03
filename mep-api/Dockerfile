FROM composer:2.5.4 as tmpComposer
FROM php:8.2-apache-bullseye

# Install packages
RUN apt-get update && apt-get install --no-install-recommends -y \
    git=1:2.30.* \
    zip=3.* \
    curl=7.* \
    sudo=1.* \
    unzip=6.* \
    libicu-dev=67.* \
    libbz2-dev=1.0.* \
    libpng-dev=1.6.* \
    libjpeg-dev=1:2.* \
    libmcrypt-dev=2.5.* \
    libreadline-dev=8.* \
    libfreetype6-dev=2.* \
    g++=4:10.* \
    npm=7.* \
    libpq-dev=13.* && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# Apache configuration
ENV APACHE_DOCUMENT_ROOT=/var/www/html/public
RUN sed -ri -e "s!/var/www/html!${APACHE_DOCUMENT_ROOT}!g" /etc/apache2/sites-available/*.conf && \
    sed -ri -e "s!/var/www/!${APACHE_DOCUMENT_ROOT}!g" /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf && \
    a2enmod rewrite headers

# Common PHP Extensions
RUN docker-php-ext-install \
    bz2 \
    intl \
    iconv \
    bcmath \
    opcache \
    calendar \
    pdo_mysql \
    pdo \
    pdo_pgsql

# Ensure PHP logs are captured by the container
ENV LOG_CHANNEL=stdout

# Set a volume mount point for your code
VOLUME /var/www/html

# Copy code and run composer
COPY --from=tmpComposer /usr/bin/composer /usr/bin/composer
COPY . /var/www/tmp
WORKDIR /var/www/tmp
RUN composer install --no-dev \
&& npm install \
&& npm install --save-dev vite@4.2.1 laravel-vite-plugin@0.7.4 vite-plugin-static-copy@0.13.1 \
&& npm install -g sass@1.60.0 \
&& npm install n@7.3.1 -g && n stable

RUN npm run build \
&& chmod +x /var/www/tmp/docker-entrypoint.sh
ENTRYPOINT ["/var/www/tmp/docker-entrypoint.sh"]

# The default apache run command
CMD ["apache2-foreground"]
