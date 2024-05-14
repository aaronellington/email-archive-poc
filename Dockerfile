FROM node:20 as npmBuilder
WORKDIR /app
ADD . /app
RUN npm ci
RUN npm run build

FROM php:8.3
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
RUN apt-get update && apt-get install -y supervisor zlib1g-dev libzip-dev unzip
RUN apt-get clean && rm -rf /var/lib/apt/lists/*
RUN mkdir -p /var/log/supervisor
COPY ops/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

RUN docker-php-ext-install pdo pdo_mysql sockets zip
WORKDIR /app
ADD . /app
COPY --from=npmBuilder /app/public/build /app/public/build
RUN composer install

CMD ["/usr/bin/supervisord"]

EXPOSE 8000
