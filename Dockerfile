FROM php:8.2.0-apache

# Define o diretório de trabalho
WORKDIR /var/www/html

# instala os editores de texto
RUN apt-get update && apt-get install -y vim
RUN apt-get update && apt-get install -y nano

# Linux Library
RUN apt-get update -y && apt-get install -y \
    libicu-dev \
    libmariadb-dev \
    unzip zip \
    zlib1g-dev \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libjpeg62-turbo-dev \
    libpng-dev

# Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer


# Copia o arquivo de configuração do Apache com as configurações do host virtual
COPY docker/apache/site.conf /etc/apache2/sites-available/000-default.conf


# Habilita o módulo rewrite do Apache
RUN a2enmod rewrite

# Define a variável de ambiente DOCUMENT_ROOT para apontar para a pasta public do Laravel
ENV DOCUMENT_ROOT /var/www/html/social_media/public

# Copia os arquivos do projeto para o diretório de trabalho
COPY . /var/www/html/social_media

# PHP Extension
RUN docker-php-ext-install gettext intl pdo_mysql gd

RUN docker-php-ext-configure gd --enable-gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) gd

# Define as permissões de diretório corretas para o Apache
RUN chown -R www-data:www-data /var/www
RUN cd /var/www/html/social_media && chmod 777 -R storage/

# Define a porta do Apache
EXPOSE 80
