FROM php:8.2-apache
RUN docker-php-ext-install pdo_mysql
# Install tools for Composer
RUN apt-get update && apt-get install -y --no-install-recommends \
	unzip \
	git \
	curl \
 && rm -rf /var/lib/apt/lists/*

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
