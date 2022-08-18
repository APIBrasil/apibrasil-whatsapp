# Painel OpenSource MyZap - Laravel 
Painel para gestão de dispositivos conectados na API do MYZAP, o software é de uso aberto e você pode alterar da forma que desejar.

### Instalando as dependencias e clonando o projeto
```bash
sudo apt install -y software-properties-common
```

```bash
sudo add-apt-repository ppa:ondrej/php
```

```bash
sudo apt update
```

```bash
apt install -y nginx git software-properties-common unzip zip build-essential zlib1g-dev libncurses5-dev libgdbm-dev libnss3-dev libssl-dev libreadline-dev libffi-dev wget mariadb-server php7.4 php7.4-mbstring php7.4-xmlrpc php7.4-soap php7.4-gd php7.4-xml php7.4-cli php7.4-zip php7.4-bcmath php7.4-tokenizer php7.4-json php-pear php7.4-curl php7.4-intl php7.4-mysqli php7.4-fpm python3-certbot-nginx
```

```bash
cd /opt
```

```bash
git clone https://github.com/APIBrasil/apibrasil-whatsapp.git painel-whatsapp
```

### Altere as configurações do php

```bash
nano /etc/php/7.4/fpm/pool.d/www.conf
```

listen = 127.0.0.1:9000

### Altere as informações do banco de dados

```bash
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

### Instale as dependencias do composer

```bash
cd /opt/painel-whatsapp
```

```bash 
php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
php -r "if (hash_file('sha384', 'composer-setup.php') === '55ce33d7678c5a611085589f1f3ddf8b3c52d662cd01d4ba75c0ee0459970c2200a51f492d557530c71c15d8dba01eae') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;"
php composer-setup.php
php -r "unlink('composer-setup.php');"
```

```bash
php composer.phar install
```

```bash
cp .env_example .env
```

```bash
nano .env
```
### Reinicie tudo e coloque para iniciar automaticamente

```bash 

service mysql restart
service php7.4-fpm restart
service php7.4-fpm status

sudo systemctl enable nginx
sudo systemctl enable mysql.service
```

### Instale as crons necessárias

```bash
crontab -e

* * * * * cd /opt/painel-whatsapp && php7.4 artisan schedule:run >> /dev/null 2>&1
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

### Rodar local para testes
```
php artisan serve --host=127.0.0.1 --port=8000
```

### Execute os jobs
```bash
php artisan queue:work &

jobs -l
```

### Server Block Nginx
```bash
nano /etc/nginx/sites-available/painel
```

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

```bash
ln -s /etc/nginx/sites-available/painel /etc/nginx/sites-enabled/painel
```

```bash
certbot --nginx
```

### Tudo certo.
Usuário padrão de acesso a plataforma

acesso@whatsapp.com<br />
1234

### Prints
![alt text](https://i.imgur.com/FgP8CRZ.png "Home")
![alt_text](https://i.imgur.com/zGzWKjg.png "Painel")
![alt_text](https://i.imgur.com/1KYVNUD.png "Criar sessões")
![alt_text](https://i.imgur.com/iBq8atI.png "Gestão de sessões")


