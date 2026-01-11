#!/bin/sh

# Substitute environment variables in nginx config
envsubst '${FRONTEND_DOMAIN} ${API_DOMAIN}' < /etc/nginx/http.d/default.conf.template > /etc/nginx/http.d/default.conf

# Test nginx config and show any errors
echo "Testing nginx configuration..."
nginx -t

# Start supervisord
exec /usr/bin/supervisord -c /etc/supervisord.conf
