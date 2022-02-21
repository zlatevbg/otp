FROM ubuntu:20.04

ENV APP_HOME /var/www/html
ENV USERNAME www-data

RUN apt-get update -y
RUN apt-get upgrade -y
RUN apt-get install software-properties-common -y
RUN apt-get update -y
RUN apt-get install mysql-client -y
RUN add-apt-repository ppa:ondrej/php -y
RUN apt-get update -y
RUN apt-get install php8.1-fpm -y
RUN apt-get install php8.1-common -y
RUN apt-get install php8.1-mysql -y
RUN apt-get install php8.1-curl -y
RUN apt-get install php8.1-gd -y
RUN apt-get install php8.1-mbstring -y
RUN apt-get install php8.1-xml -y
RUN apt-get install php8.1-mcrypt -y
RUN apt-get install php8.1-bcmath -y
RUN apt-get install php8.1-zip -y
RUN apt-get install php8.1-opcache -y

RUN mkdir -p /run/php/
RUN sed -i '/daemonize /c daemonize = no' /etc/php/8.1/fpm/php-fpm.conf
RUN sed -i '/^listen /c listen = 9000' /etc/php/8.1/fpm/pool.d/www.conf
RUN sed -i '/error_log /c error_log = /proc/self/fd/2' /etc/php/8.1/fpm/php-fpm.conf
RUN sed -i '/^;access.log /c access.log = /proc/self/fd/2' /etc/php/8.1/fpm/pool.d/www.conf
RUN sed -i '/^;clear_env /c clear_env = no' /etc/php/8.1/fpm/pool.d/www.conf
RUN sed -i '/^;catch_workers_output /c catch_workers_output = yes' /etc/php/8.1/fpm/pool.d/www.conf
RUN sed -i '/^;decorate_workers_output /c decorate_workers_output = no' /etc/php/8.1/fpm/pool.d/www.conf

WORKDIR $APP_HOME

# COPY --chown=${USERNAME}:${USERNAME} . .

# RUN chown -R ${USERNAME}:${USERNAME} $APP_HOME

COPY ./docker/entrypoint.php.sh /usr/local/bin/entrypoint.php.sh
RUN chmod +x /usr/local/bin/entrypoint.php.sh

EXPOSE 9000

ENTRYPOINT ["/usr/local/bin/entrypoint.php.sh"]
CMD ["/usr/sbin/php-fpm8.1"]
