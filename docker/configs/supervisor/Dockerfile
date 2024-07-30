FROM php:8.2.15-fpm

ARG APP_ROOT
ARG COMMAND
ARG OPTIONS 

ENV APP_ROOT=${APP_ROOT}
ENV COMMAND=${COMMAND}
ENV OPTIONS=${OPTIONS}

RUN apt-get update \
    && apt-get install -y --no-install-recommends supervisor

COPY --from=mlocati/php-extension-installer /usr/bin/install-php-extensions /usr/bin/
RUN install-php-extensions pcntl

RUN mkdir -p "/etc/supervisor/logs"
RUN mkdir -p "${APP_ROOT}/storage/logs"
RUN chmod -R 777 "${APP_ROOT}/storage/logs"

COPY configs/supervisor/supervisord.conf /etc/supervisor/conf.d/supervisord.conf 

ENTRYPOINT ["/usr/bin/supervisord", "--nodaemon", "--configuration", "/etc/supervisor/conf.d/supervisord.conf"]
