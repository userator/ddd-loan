# Тестовое задание Команды кредитования (Loan Team)

## Создать новый продукт

```bash
php ./bin/console app:product-create
```

## Создать нового клиента

```bash
php ./bin/console app:client-create
```

## Просмотреть список продуктов

```bash
php ./bin/console app:product-list
```

## Просмотреть список клиентов

```bash
php ./bin/console app:client-list
```

## Измененить информацию о существующем клиенте

```bash
php ./bin/console app:client-modify
```

## Осуществить скоринг клиента (Проверка возможности выдачи займа)

```bash
php ./bin/console app:client-score
```

## Осуществить выдачу займа

```bash
php ./bin/console app:loan-issue
```

## Просмотреть список выданных займов

```bash
php ./bin/console app:loan-list
```

## Просмотреть список отправленных email и смс сообщений

```bash
ls -l ./var/email
ls -l ./var/sms
```