FROM amiltondiego/docker-php-nginx-alpine:latest

WORKDIR /app
EXPOSE 9000

COPY . .

COPY docker/custom.php.ini /etc/php8/conf.d/zyz-custom.ini

# Configuring paths permissions
RUN mkdir -p /var/log/xdebug && chmod -Rf 777 /var/log/xdebug

# need to laravel
RUN apk --no-cache \
    add php8-fileinfo \
    --repository http://dl-cdn.alpinelinux.org/alpine/edge/community/
