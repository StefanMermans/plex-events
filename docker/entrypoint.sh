#!/bin/sh

# Substitute environment variables in nginx config
envsubst '${FRONTEND_DOMAIN} ${API_DOMAIN}' < /etc/nginx/http.d/default.conf.template > /etc/nginx/http.d/default.conf

# Start supervisord
exec /usr/bin/supervisord -c /etc/supervisord.conf
