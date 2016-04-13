# Use phusion/baseimage as base image. To make your builds
# reproducible, make sure you lock down to a specific version, not
# to `latest`! See
# https://github.com/phusion/baseimage-docker/blob/master/Changelog.md
# for a list of version numbers.
FROM phusion/baseimage:0.9.18

# Use baseimage-docker's init system.
CMD ["/sbin/my_init"]

# install pacakages
RUN apt-get update && apt-get install -y nginx php5-fpm

# nginx runit script
ADD docker/etc/nginx/nginx.conf /etc/nginx/nginx.conf
ADD docker/etc/nginx/sites-available/default /etc/nginx/sites-available/default
RUN mkdir /etc/service/nginx
ADD docker/service/nginx.sh /etc/service/nginx/run

VOLUME /var/www

# php5-fpm runit script
RUN mkdir /etc/service/php5-fpm
ADD docker/service/php5-fpm.sh /etc/service/php5-fpm/run

# Clean up APT when done.
RUN apt-get clean && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*

EXPOSE 80