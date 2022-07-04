FROM php:8.1.1-fpm
LABEL Name="php-fpm"
LABEL Version="0.1"

# Arguments defined in docker-compose.yml
ARG user
ARG uid
# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip\
    libfreetype6-dev \
    libjpeg62-turbo-dev \
    libpng-dev \
    libxpm-dev \
    libvpx-dev \
    netcat \
    libpq-dev \
    && docker-php-ext-configure gd --with-freetype=/usr --with-jpeg=/usr \
    && apt-get install -y libmagickwand-dev --no-install-recommends

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-install pdo pdo_pgsql pgsql mbstring exif pcntl bcmath gd

RUN pecl install imagick

RUN docker-php-ext-enable imagick
# Get latest Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Create system user to run Composer and Artisan Commands
RUN useradd -G www-data,root -u $uid -d /home/$user $user
RUN mkdir -p /home/$user/.composer && \
    chown -R $user:$user /home/$user

# Set working directory
WORKDIR /var/www

COPY .docker/nginx/entrypoint_dev /entrypoint_dev
RUN chmod +x /entrypoint_dev
ENTRYPOINT [ "/entrypoint_dev" ]

USER ${user}

EXPOSE 9000
CMD ["php-fpm"]
