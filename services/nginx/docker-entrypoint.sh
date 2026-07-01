#!/usr/bin/env sh
set -eu

# Replaces environment variables in /etc/nginx/conf.d/default.conf template
envsubst '${NGINX_PORT} ${RESOLVER_IP} ${UPSTREAM_HOST} ${UPSTREAM_PORT}' < /etc/nginx/conf.d/default.conf.template > /etc/nginx/conf.d/default.conf

# Removes `resolver` if its value is empty
sed -i '/resolver ;/d' /etc/nginx/conf.d/default.conf

exec "$@"
