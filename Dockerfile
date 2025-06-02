# Use a base image with Nginx and PHP-FPM
# The 'latest' tag is convenient. For production, you might consider pinning to a specific version tag
# for example, richarvey/nginx-php-fpm:php82 or another PHP 8.x version.
FROM richarvey/nginx-php-fpm:latest

# Set the working directory in the container.
# /var/www/html is the typical web root for this base image.
WORKDIR /var/www/html

# Copy your single PHP file (assumed to be named index.php)
# from your build context (the directory containing the Dockerfile)
# into the web root directory inside the container.
COPY index.php /var/www/html/index.php

# The base image (richarvey/nginx-php-fpm) already takes care of:
# - Setting up Nginx and PHP-FPM to work together.
# - Default Nginx configuration to serve PHP files from /var/www/html (index.php is usually the default).
# - Common PHP extensions like 'session' are typically enabled.
# - A 'sendmail' compatible utility (often ssmtp or similar) is usually included and configured
#   to allow PHP's mail() function to work. Actual mail delivery will depend on the
#   environment's (e.g., Coolify server's) ability to send mail or relay it.

# Expose port 80, which is the standard HTTP port Nginx will listen on.
EXPOSE 80

# The base image's default command will start Nginx and PHP-FPM.
# No need to override CMD or ENTRYPOINT for this simple case.