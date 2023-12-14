FROM lorisleiva/laravel-docker:8.1
RUN apk add grpc
EXPOSE 8000

WORKDIR /var/www

RUN echo "max_file_uploads=800" >> /usr/local/etc/php/conf.d/docker-php-ext-max_file_uploads.ini
RUN echo "post_max_size=500M" >> /usr/local/etc/php/conf.d/docker-php-ext-post_max_size.ini
RUN echo "upload_max_filesize=500M" >> /usr/local/etc/php/conf.d/docker-php-ext-upload_max_filesize.ini
RUN echo "memory_limit=512M" >> /usr/local/etc/php/conf.d/docker-php-ext-memory_limit.ini
RUN echo "max_execution_time=600" >> /usr/local/etc/php/conf.d/docker-php-ext-max_execution_time.ini
RUN echo "file_uploads=On" >> /usr/local/etc/php/conf.d/docker-php-ext-file_uploads.ini

COPY .  /var/www
RUN rm -f composer.lock
RUN composer install
RUN php artisan cache:clear 
RUN php artisan config:clear
RUN cp .env.example .env
RUN php artisan key:generate 
RUN php artisan jwt:secret

CMD php artisan --host=0.0.0.0 serve
