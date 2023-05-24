# Drupal container used for the rest of the build execution.
FROM antistatique/php:8.1-drupal as drupal

ARG user=www-data
ARG group=${user}

ENV COMPOSER_DISCARD_CHANGES=true

# Feel free to disable those lines to use Xdebug on your side.
#RUN mkdir -p /usr/src/php/ext/xdebug && \
#    pecl install xdebug && \
#    docker-php-ext-enable xdebug \
#    && echo "xdebug.mode=develop,debug" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
#    && echo "xdebug.client_host=host.docker.internal" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
#    && echo "xdebug.idekey=PHPSTORM" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
#    && echo "xdebug.start_with_request=yes" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini

# This part is used to set the correct group & user to the files copied from host -> container
# Somehow due to MacOS host to Linux container, the files are not properly chowned or the container doesn't run
# Properly with the correct user. This fixes the issue.
# -----------------------------------------------------------
RUN set -eux; \
  \
  if [ "${group}" != "www-data" ]; then \
    groupadd user --gid ${group}; \
  fi; \
  \
  if [ "${user}" != "www-data" ]; then \
    useradd user --uid ${user} --gid ${group} --groups www-data --no-create-home --shell /bin/bash; \
  fi; \
  \
  chown -R $user:$group . /var/backups

# The goal of this is to not run anymore the command as root.
# But also for Windows Hosts to allow same user in Docker as the host.
USER $user:$group
# -----------------------------------------------------------

ADD --chown=${user}:${group} ./composer.json ./composer.lock ./
# COPY --chown=${user}:${group} ./patches ./patches

RUN set -eux; \
  \
  composer install --prefer-dist --no-scripts --no-progress --no-suggest --no-interaction; \
  composer clear-cache

RUN set -eux; \
  \
  jq 'del(.. |."patches_applied"? | select(. != null))' ./vendor/composer/installed.json > ./vendor/composer/installed.json.new; \
  mv ./vendor/composer/installed.json.new ./vendor/composer/installed.json; \
  \
  composer install --prefer-dist --no-progress --no-suggest --no-interaction; \
  composer clear-cache;

COPY --chown=${user}:${group} . ./
