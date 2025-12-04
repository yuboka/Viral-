FROM php:8.2-cli

# Install dependencies for SimpleXML
RUN apt-get update && apt-get install -y \
    libxml2-dev \
    pkg-config \
    && docker-php-ext-install simplexml

# Working directory
WORKDIR /app

# Copy project
COPY . .

# Default run command
CMD ["php", "broadcast.php"]
