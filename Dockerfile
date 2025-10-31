FROM php:8.2-cli

RUN apt-get update && apt-get install -y \
    bash \
    curl \
    unzip \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    default-mysql-client \
    default-libmysqlclient-dev \
    libpq-dev \
    libsqlite3-dev \
    && docker-php-ext-configure gd --enable-gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo_pgsql pdo_mysql mbstring exif pcntl bcmath gd zip \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/bin --filename=composer
RUN apt-get update && apt-get install -y nodejs npm

WORKDIR /var/www/html
COPY . .

EXPOSE 8000
RUN composer install
RUN npm install

CMD php artisan migrate --force && php artisan storage:link && php artisan serve --host=0.0.0.0 --port=8000
