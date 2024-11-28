FROM php:8.2-fpm

# Install system dependencies.
RUN apt-get update && apt-get install -y \
    git \
    curl \
	gnupg2 \
	libonig-dev \
    libxml2-dev \
    libwebp-dev \
	libjpeg62-turbo-dev \
    libpng-dev libxpm-dev \
    libfreetype6-dev \
	zip \
    unzip\
    nginx\
	zlib1g-dev\
    libzip-dev\
    nano\
    wget \
    cron \
    nginx-extras \	
   && docker-php-ext-configure gd --with-freetype --with-jpeg \
   && docker-php-ext-configure intl \
   && docker-php-ext-install pdo pdo_mysql mbstring exif pcntl bcmath zip gd intl \
    && apt-get clean all && rm -rf /var/lib/apt/lists/*


# Get latest Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Create system user to run Composer and Artisan Commands and Use the default production configuration and change a parameter.

RUN useradd -G www-data,root -u 1000 -d /home/learningmate learningmate \
    && mkdir -p /home/learningmate/.composer /home/learningmate/mgh49 \
        && chown -R $learningmate:learningmate /home/learningmate/\
        && mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini" \
        && sed -i 's|expose_php = On|expose_php = Off|' "$PHP_INI_DIR/php.ini" \
        && sed -i 's|memory_limit = 128M|memory_limit = 1024M|' "$PHP_INI_DIR/php.ini" \
        && sed -i 's|max_execution_time = 120|max_execution_time = 0|' "$PHP_INI_DIR/php.ini" \
        && sed -i 's|post_max_size = 8M|post_max_size = 50M|' "$PHP_INI_DIR/php.ini" \
        && sed -i 's|upload_max_filesize = 2M|upload_max_filesize = 40M|' "$PHP_INI_DIR/php.ini"


COPY . .
#changing work Directory
WORKDIR /var/www

# Deploying Code
COPY --chown=learningmate:learningmate ./ /var/www/

# Downloading dependencies
#RUN curl -sL https://deb.nodesource.com/setup_18.x | bash \
	#&& apt-get install nodejs -yq && npm install
	#&& npm run build && npm run dev

# Building application and running
RUN mkdir /var/www/bootstrap/cache \
   && composer install \
   && ls -la /var/www/ \  
   && chown -R www-data:www-data /var/www/storage && chown -R www-data:www-data /var/www/bootstrap/cache \
   && find /var/www/storage -type d -exec chmod -R 777 {} \; && find /var/www/storage -type f -exec chmod 644 {} \; && find /var/www/bootstrap/cache -type d -exec chmod -R 777 {} \; && find /var/www/bootstrap/cache -type f -exec chmod 644 {} \;
   
RUN php artisan migrate && php artisan db:seed && php artisan optimize:clear && php artisan view:clear && php artisan ui bootstrap \
   && apt-get clean all && rm -rf /var/www/storage/framework/cache/data \
   && rm -rf /var/lib/apt/lists/*
   
COPY CICD_Script/Test_page/index.html /var/www/public/index.html
COPY CICD_Script/Test_page/health-check.html /var/www/public/health-check.html
RUN chmod 777 /var/www/public/client_logo

RUN rm -f /etc/nginx/nginx.conf
COPY Nginx/mgh49stg.conf /etc/nginx/nginx.conf
COPY CICD_Script/Deploy/Docker/start.sh /var/www/start.sh

#Giving executable permission to execute start.sh
RUN chmod +x /var/www/start.sh
EXPOSE 80
#WORKDIR /var/www

CMD ["./start.sh"]