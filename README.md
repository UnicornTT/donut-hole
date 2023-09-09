## Как развернуть

Установите docker и docker-compose.

Скопируйте .env

```shell
cp .env.example .env
```

Ставим зависимости
```shell
docker-compose run --rm back composer install
```

Запускаем
```shell
docker-compose up -d
```

Создаем базу данных
```shell
docker-compose exec db psql -U postgres -c "create database backend;"
```

Накатить миграции
```shell
docker-compose exec back php artisan migrate
```
В браузере открываем http://localhost

## О проекте

Созданно 2 сущности, для каждой из которых написанны следующие роуты:
1. department/employee - вывод объектов с пагинацией
2. create - создание экземпляра сущности
3. update/{id} - изменение объекта
4. delete/{id} - удаление объекта

Реализована связь между сотрудниками и отделами, при которой удален может быть только тот отдел, к которому не относится ни один сотрудник

Все ошибки возвращаются в формате JSON

Заросы к базе данных осуществляются посредством моделей

Составлена документация по API и находится по роуту /api

Примерное время на реализацию  - 8 часов
