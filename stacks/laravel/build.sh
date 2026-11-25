#!/usr/bin/env bash
set -euo pipefail

BASE_DIR="$1"
APP_NAME="$2"
COMPOSER_IMAGE="$3"
SERVICES="$4"
PULL_SERVICES="$5"

APP_DIR="${BASE_DIR}/${APP_NAME}"

docker info > /dev/null 2>&1 || {
    echo "Docker is not running."
    exit 1
}

docker run --rm --pull=always \
    -v "${BASE_DIR}:/opt" \
    -w /opt \
    "${COMPOSER_IMAGE}" \
    bash -c "laravel new '${APP_NAME}' --no-interaction && cd '${APP_NAME}' && (composer show laravel/sail 2>/dev/null || composer require laravel/sail --dev) && php ./artisan sail:install --with=${SERVICES}"

cd "${APP_DIR}"

if [ "${PULL_SERVICES}" == "none" ]; then
    ./vendor/bin/sail build
else
    ./vendor/bin/sail pull ${PULL_SERVICES}
    ./vendor/bin/sail build
fi

echo ""
echo "Hotovo! Laravel projekt je připraven v: ${APP_DIR}"
