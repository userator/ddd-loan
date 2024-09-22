# Тестовое задание Команды кредитования (Loan Team)

## Создать новый продукт и нового клиента

```bash
/usr/bin/php8.2 ./bin/console app:product-create
/usr/bin/php8.2 ./bin/console app:client-create
```

## Просмотреть списки продуктов и клиентов

```bash
/usr/bin/php8.2 ./bin/console app:product-list
/usr/bin/php8.2 ./bin/console app:client-list
```

## Измененить информацию о существующем клиенте

```bash
/usr/bin/php8.2 ./bin/console app:client-modify
```

## Осуществить скоринг клиента (Проверка возможности выдачи займа)

```bash
/usr/bin/php8.2 ./bin/console app:client-score
```

## Осуществить выдачу займа

```bash
/usr/bin/php8.2 ./bin/console app:loan-issue
```

## Просмотреть список выданных займов

```bash
/usr/bin/php8.2 ./bin/console app:loan-list
```
