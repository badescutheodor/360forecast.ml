# 360forecast.ml Socket Server

Socket server created in php for serving the website forecasts in real time.

## Migrations

Create migration: 
`./phinx create MigrationName -c ../../config/phinx.php`

Run migrations: 
`./phinx migrate -c ../../config/phinx.php`

## Socket Server
To run the socket server in background use `nohup php index.php > /dev/null 2>&1&`