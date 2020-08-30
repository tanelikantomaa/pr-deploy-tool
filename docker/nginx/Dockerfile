FROM nginx:latest

RUN mkdir -p /etc/nginx/ssl/
COPY ./docker/nginx/configs/nginx-selfsigned.crt /etc/nginx/ssl/nginx
COPY ./docker/nginx/configs/nginx-selfsigned.key /etc/nginx/ssl/nginx
COPY ./docker/nginx/configs/nginx.conf /etc/nginx/conf.d/default.conf

COPY . /var/www/html

# Create Storage Link (php artisan storage:link)
RUN rm -rf /var/www/html/public/storage
RUN ln -s /var/www/html/storage/app/public /var/www/html/public/storage