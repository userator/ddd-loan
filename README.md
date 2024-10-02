# Тестовое задание Команды кредитования (Loan Team)

## Подготовка

Сборка и запуск

```bash
make init
```

Подключение к контейнеру

```bash
make sh_php
```

Установка php-пакетов

```bash
composer install
```

## Использование

Создать новый продукт

```bash
php ./bin/console app:product-create
```

Создать нового клиента

```bash
php ./bin/console app:client-create
```

Просмотреть список продуктов

```bash
php ./bin/console app:product-list
```

Просмотреть список клиентов

```bash
php ./bin/console app:client-list
```

Измененить информацию о существующем клиенте

```bash
php ./bin/console app:client-modify
```

Осуществить скоринг клиента (Проверка возможности выдачи займа)

```bash
php ./bin/console app:client-score
```

Осуществить выдачу займа

```bash
php ./bin/console app:loan-issue
```

Просмотреть список выданных займов

```bash
php ./bin/console app:loan-list
```

Просмотреть список отправленных email сообщений

```bash
ls -l ./var/email
```

Просмотреть список отправленных смс сообщений

```bash
ls -l ./var/sms
```

## Разрботка

Запустить статический анализатор

```bash
make stan
```

Запустить юнит-тесты

```bash
make phpunit
```

Запустить rector-процессинг

```bash
make rector
```

Запустить deptrac-анализатор

```bash
make deptrac
```
