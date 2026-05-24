FROM php:8.0-apache

# Install mysqli and other extensions
RUN apt-get update && apt-get install -y libpq-dev && \
	docker-php-ext-install mysqli pdo pdo_mysql pgsql pdo_pgsql && \
	rm -rf /var/lib/apt/lists/*

# Enable Apache modules
RUN a2enmod rewrite

# Set working directory
WORKDIR /var/www/html

# Copy application
COPY . .

# Set permissions
RUN chown -R www-data:www-data /var/www/html

# Configure database connection from environment
ENV DB_HOST=${DB_HOST:-localhost}
ENV DB_USER=${DB_USER:-root}
ENV DB_PASS=${DB_PASS:-}
ENV DB_NAME=${DB_NAME:-barangay_records}

# Expose port
EXPOSE 80

# Start Apache
CMD ["apache2-foreground"]
