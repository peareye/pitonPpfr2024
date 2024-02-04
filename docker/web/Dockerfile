FROM ubuntu:latest

ARG DEBIAN_FRONTEND=noninteractive
RUN apt-get update && \
    apt-get -y install software-properties-common && \
    add-apt-repository ppa:ondrej/php && \
    apt-get update && \
    apt-get install -y \
      apache2 \
      php7.4 \
      php7.4-cli \
      libapache2-mod-php7.4 \
      php-apcu \
      php7.4-xdebug \
      php7.4-gd \
      php7.4-json \
      php7.4-ldap \
      php7.4-mbstring \
      php7.4-mysql \
      php7.4-xml \
      php7.4-xsl \
      php7.4-zip \
      nano \
      sendmail \
      php-curl \
      curl \
      git

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/bin --filename=composer
COPY apache-host.conf /etc/apache2/sites-available/000-default.conf
COPY run.sh /usr/local/bin/run
RUN chmod +x /usr/local/bin/run
COPY wait-for-it.sh /usr/local/bin/wait-for-it
RUN chmod +x /usr/local/bin/wait-for-it

EXPOSE 80
