# Модуль интеграции ЛК с сервисом оплат

Модуль расширяет функционал ЛК возможностью проведения оплат

## Установка

В папке с установленным приложением

```bash
composer config repositories.payments "vcs" "git@github.com:maxkhim/payments.git"
composer require narfu/payments
```

#Сервер пэйкипера
PAYKEEPER_SERVER="https://*.server.paykeeper.ru"
#Адрес для приёма оповещений (для POST оповещений)
PAYKEEPER_POST_URL="https://*/*/payments/callback"
#Ключ пэйкипера (для верификации POST оповещений)
PAYKEEPER_POST_SECRET=""

#Логи для авторизации запросов
PAYKEEPER_LOGIN=""
#Пароль для авторизации запросов
PAYKEEPER_PASSWORD=""
