Это тестовое задание от компании "nevatrip".

На локальной машине нужен установленный: git, docker и docker compose Если их нет -пригодятся следующие ссылки:

    https://docs.docker.com/engine/install/
    https://docs.docker.com/engine/install/linux-postinstall/ (для линукс-пользователей)
    https://git-scm.com/downloads

Запуск приложения: Выполняем в терминале поочередно команды ниже


    docker compose up --build -d
    docker compose exec php composer install
    docker compose exec php php bin/console doctrine:migrations:migrate --no-interaction


Для корректной отработки кода предполагается, что будет отправляться post запрос на адрес http://localhost:8080/ след. вида:

{
"event_id" : 1113,
"event_date" : "10-12-2024",
"tickets" : [
{
"type": "kid",
"price": 500,
"quantity": 2
},
{
"type": "adult",
"price": 1000,
"quantity": 1
}
]
}
