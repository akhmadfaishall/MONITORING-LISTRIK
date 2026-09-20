FROM php:8.2-cli

# Install Python 3, Pip, dan dependensi sistem Linux
RUN apt-get update && apt-get install -y \
    python3 \
    python3-pip \
    zip \
    unzip \
    git \
    libpng-dev \
    libonig-dev \
    libxml2-dev

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /app
COPY . .

# Install paket PHP dan library Python
RUN composer install --ignore-platform-reqs --no-dev --optimize-autoloader
RUN python3 -m pip install -r requirements.txt --break-system-packages

# Gunakan shell form agar variabel $PORT dari Railway terbaca dengan benar
CMD ["sh", "-c", "php artisan serve --host=0.0.0.0 --port=${PORT:-8000}"]