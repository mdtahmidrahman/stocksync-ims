# Stage 1: Build Node.js assets
FROM node:20 AS node_builder
WORKDIR /app
COPY package*.json ./
RUN npm install
COPY . .
RUN npm run build

# Stage 2: Build Composer dependencies
FROM php:8.3-cli AS composer_builder
RUN apt-get update && apt-get install -y git unzip zip
COPY --from=composer:2.7 /usr/bin/composer /usr/bin/composer
WORKDIR /app
COPY composer.json composer.lock ./
RUN composer install --no-dev --no-scripts --no-autoloader --prefer-dist --ignore-platform-reqs
COPY . .
RUN composer dump-autoload --optimize --no-dev --no-scripts

# Stage 3: Final Apache Image
FROM php:8.3-apache

# Install system dependencies & PHP extensions required by Laravel & PostgreSQL/MySQL
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    libicu-dev \
    libpq-dev \
    zip \
    unzip \
    && docker-php-ext-install pdo_mysql pdo_pgsql mbstring exif pcntl bcmath gd intl zip \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Enable Apache mod_rewrite for Laravel routing
RUN a2enmod rewrite

# Update Apache DocumentRoot to point to Laravel's public directory
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# Set working directory
WORKDIR /var/www/html

# Copy application files from Composer stage
COPY --from=composer_builder /app /var/www/html

# Copy built assets from Node stage
COPY --from=node_builder /app/public/build /var/www/html/public/build

# Copy startup script and make it executable
COPY start.sh /usr/local/bin/start.sh
RUN chmod +x /usr/local/bin/start.sh

# Set correct permissions
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# Expose port 80
EXPOSE 80

# Start script
ENTRYPOINT ["/usr/local/bin/start.sh"]
