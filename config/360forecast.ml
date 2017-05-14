server {
    listen 80;
    listen [::]:80;

    server_name 360forecast.ml www.360forecast.ml;
    return 301 https://360forecast.ml$request_uri;
}

server {
    server_name 360forecast.ml;

    listen 443 ssl http2 default_server;
    listen [::]:443 ssl http2 default_server;
    include snippets/ssl-360forecast.ml.conf;
    include snippets/ssl-params.conf;

    root /usr/local/src/360forecast.ml/client/dist;
    index index.html;

    location ~ /.well-known {
        allow all;
    }

    location ~* \.(?:css|js|ico|woff|eot|svg|ttf|otf|png|gif|jpe?g)$ {
      expires 1y;
      access_log off;
      add_header Cache-Control "immutable";
    }

    location /socket {
        proxy_set_header X-Forwarded-For $proxy_add_x_forwarded_for;
        proxy_pass http://localhost:8080;
        proxy_http_version 1.1;
        proxy_set_header Upgrade $http_upgrade;
        proxy_set_header Connection "upgrade";
        proxy_set_header Host $host;
        proxy_set_header X-Real-IP $remote_addr;
        proxy_set_header X-Forwarded-Proto https;
        proxy_read_timeout 86400;
        proxy_redirect off;
    }

    location / {
        try_files $uri $uri/ =404;
    }
}
