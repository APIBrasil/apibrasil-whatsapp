# Painel OpenSource MyZap - Laravel 

### Setup project
Create .env file
```bash
cp .\.env_example .env
```
Install composer and dependencies
```bash
composer install
```
Populate first user
```bash
php artisan db:seed
```

Run project in local
```bash
php artisan serve --port=9002
```

### Setup Cron Job
```bash
crontab -e
```

```bash
* * * * * cd /opt/apibrasil-whatsapp && php7.4 artisan schedule:run >> /dev/null 2>&1
```

### Run Job
```bash
php artisan queue:work
```

### Prints
![alt text](https://i.imgur.com/FgP8CRZ.png "Home")
![alt_text](https://i.imgur.com/zGzWKjg.png "Painel")
![alt_text](https://i.imgur.com/1KYVNUD.png "Criar sessões")
![alt_text](https://i.imgur.com/iBq8atI.png "Gestão de sessões")


