# Painel MyZap Laravle OpenSource

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
