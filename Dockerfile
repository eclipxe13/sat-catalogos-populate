FROM debian:trixie

COPY . /opt/sat-catalogos-populate/

RUN set -e \
    && export DEBIAN_FRONTEND=noninteractive \
    # Update debian base system \
    && apt-get update -y \
    && apt-get dist-upgrade -y \
    # Install repository PHP from Ondřej Surý \
    && apt install -y curl \
    && curl --no-progress-meter https://packages.sury.org/php/README.txt | bash \
    && apt-get update -y \
    && apt-get dist-upgrade -y \
    # Install required packages \
    && apt-get install -y \
        unzip git \
        xlsx2csv sqlite3 \
        php-cli php-curl php-zip php-sqlite3 php-xml \
    && apt-get install -y --no-install-recommends \
        libreoffice-calc-nogui default-jre-headless libreoffice-java-common \
    && apt-get install -y --no-install-recommends \
        chromium \
    # Clean APT \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

RUN set -e \
    # Set up PHP: variables_order & date.timezone \
    && find /etc/php/ -type f -name "*.ini" -exec sed -i -E -e 's/^;?variables_order.*/variables_order=EGPCS/' -e 's#^;?date.timezone.*#date.timezone = America/Mexico_City#' "{}" \; \
    && php -i

RUN set -e \
    # Install composer \
    && curl --progress-bar https://getcomposer.org/download/latest-stable/composer.phar --output /usr/local/bin/composer \
    && chmod +x /usr/local/bin/composer \
    && export COMPOSER_ALLOW_SUPERUSER=1 \
    && (composer diagnose --no-interaction || true)

RUN set -e \
    && cd /opt/sat-catalogos-populate/ \
    && composer update --no-dev --prefer-dist --optimize-autoloader --no-interaction \
    && rm -rf "$(composer config cache-dir --global)" "$(composer config data-dir --global)" "$(composer config home --global)"

ENV CHROME_BINARY="/usr/bin/chromium"
ENV CHROME_NOSANDBOX="yes"
ENV TZ="America/Mexico_City"

ENTRYPOINT ["/opt/sat-catalogos-populate/bin/sat-catalogos-update"]
