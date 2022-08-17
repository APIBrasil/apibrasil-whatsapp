# Painel OpenSource MyZap - Laravel 

### Install composer and dependencies
```bash
sudo apt install -y software-properties-common
sudo add-apt-repository ppa:ondrej/php
sudo apt update

apt install -y composer nginx git software-properties-common unzip zip build-essential zlib1g-dev libncurses5-dev libgdbm-dev libnss3-dev libssl-dev libreadline-dev libffi-dev wget mariadb-server php7.4 php7.4-mbstring php7.4-xmlrpc php7.4-soap php7.4-gd php7.4-xml php7.4-cli php7.4-zip php7.4-bcmath php7.4-tokenizer php7.4-json php-pear php7.4-curl php7.4-intl php7.4-mysqli php7.4-fpm 

```

### Setup project
```bash
cd /opt
git clone https://github.com/APIBrasil/apibrasil-whatsapp.git painel-whatsapp
```

```bash
cd /opt/painel-whatsapp
```

```bash
cp .env_example .env
```

```bash
nano /etc/php/7.4/fpm/pool.d/www.conf
```
listen = 127.0.0.1:9000

```bash 
sudo nano /etc/mysql/mariadb.conf.d/50-server.cnf
mysql_secure_installation
mariadb -u root -p
```

```mysql
CREATE DATABASE apibrasil;
USE mysql;
UPDATE user SET PLUGIN='' WHERE User='root';
GRANT ALL PRIVILEGES ON *.* TO 'root'@'%' IDENTIFIED BY '_SeT_On3_B1g_STR0NG_P@ssw0rd_';
FLUSH PRIVILEGES;
EXIT;
```

```bash 
composer install
```

```bash 
sudo systemctl enable nginx
sudo systemctl enable mariadb.service
```

### Setup Cron Job

```bash
crontab -e

* * * * * cd /opt/painel-whatsapp && php7.4 artisan schedule:run >> /dev/null 2>&1
```

### Restart all services
```bash
service mysql restart
service php7.4-fpm restart
service php7.4-fpm status
```

### Populate first user
```bash
php artisan migrate
php artisan db:seed
```

### Permissions
```bash
chmod 777 storage/app
chmod 777 -R storage/framework
chmod 777 -R storage/logs
```

### Nice! run.
```php artisan serve 127.0.0.1 --port=8000```

### Lauch!

http://127.0.0.1:8000

### Default user and password

```
E-mail: jhowjhoe@gmail.com
Senha: 221568
```
### Run Job
```bash
php artisan queue:work
```

### Server Block Nginx
```
server {

    root /opt/painel-whatsapp/public;
    index index.php index.html index.htm;

    server_name DOMINIO;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
        resolver 8.8.8.8 ipv6=off;
    }

    location ~ \.php$ {
        try_files $uri $uri/ /index.php?$query_string;
        fastcgi_split_path_info ^(.+\.php)(/.+)$;
        fastcgi_pass 127.0.0.1:9000;
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
        include fastcgi_params;
    }
    listen 80;

}
```

```ln -s /etc/nginx/sites-available/painel /etc/nginx/sites-enabled/painel```

```bash
certbot --nginx
```

### Prints
![alt text](https://i.imgur.com/FgP8CRZ.png "Home")
![alt_text](https://i.imgur.com/zGzWKjg.png "Painel")
![alt_text](https://i.imgur.com/1KYVNUD.png "Criar sessões")
![alt_text](https://i.imgur.com/iBq8atI.png "Gestão de sessões")


