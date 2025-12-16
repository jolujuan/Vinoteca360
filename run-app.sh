#!/usr/bin/env bash
set -e

# Symfony necesita permisos de escritura
mkdir -p var/cache var/log config/jwt
chmod -R ugo+rw var || true

# Escribir las claves JWT desde variables
if [ -n "${JWT_PRIVATE_PEM:-}" ]; then
  printf "%s" "$JWT_PRIVATE_PEM" > config/jwt/private.pem
  chmod 600 config/jwt/private.pem || true
fi

if [ -n "${JWT_PUBLIC_PEM:-}" ]; then
  printf "%s" "$JWT_PUBLIC_PEM" > config/jwt/public.pem
  chmod 644 config/jwt/public.pem || true
fi

php bin/console cache:clear --no-warmup || true
php bin/console doctrine:migrations:migrate --no-interaction

# Arranque HTTP
php -S 0.0.0.0:${PORT} -t public
