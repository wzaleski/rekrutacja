# Dockerfile
FROM php:8.1-apache

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    zip \
    libsqlite3-dev \
    libpq-dev \
    sqlite3 \
    && docker-php-ext-install pdo pdo_sqlite

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Install Node.js and Yarn
RUN curl -fsSL https://deb.nodesource.com/setup_18.x | bash - && \
    apt-get install -y nodejs && \
    npm install -g yarn

# Copy Apache config
COPY docker/apache.conf /etc/apache2/conf-available/servername.conf
RUN a2enconf servername

# Enable Apache rewrite module
RUN a2enmod rewrite

# Copy project files
WORKDIR /var/www/html
COPY --exclude='.git' --exclude='.dockerignore' --exclude='.gitignore' . .

# Install PHP dependencies
RUN composer install --no-dev --optimize-autoloader

# Install JS dependencies and build assets
RUN yarn install && yarn build

# Create SQLite database and table
# Create SQLite database and table
RUN mkdir -p /var/www/html/db && \
    touch /var/www/html/db/assqlite.db && \
    echo "CREATE TABLE IF NOT EXISTS vehicles (" \
    "id INTEGER PRIMARY KEY AUTOINCREMENT," \
    "registration_number TEXT(16)," \
    "brand TEXT(60)," \
    "model TEXT(60)," \
    "\"type\" TEXT," \
    "created_at INTEGER," \
    "updated_at INTEGER" \
    ");" | /usr/bin/sqlite3 /var/www/html/db/assqlite.db


# Expose port
EXPOSE 80
