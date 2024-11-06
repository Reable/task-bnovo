<h1>ТЗ Bnovo</h1>

<h3>Задание выполнено с помощью паттерна проектирования MVC, личная реализация от 11.09.24, есть еще много моментов которые следует доработать. К примеру создать модуль валидации нормальный)</h3>

<h3>Задание выполнено в полном объеме (Кроме уровня мидл конечно). Также для мини-защиты API
было реализованно мини аутентификация запроса по специальному "API_KEY" его вы можете найти в ".env" файлах конфигурации. </h3>

> Для правильной работы всех запросов не забывайте в заголовки вашего запроса добавлять поле
> Authorization: .......ключ аутентификации........

<h3 style="color:red">Ошибка если обращаться без ключа приложения</h3>

```

headers: {
    "Content-Type"  : "application/json",
}
body: {
    "error": "Invalid API key",
    "statusCode": 403
}
status: 403 Forbidden

```

<p>PS. можно было бы сделать через JWT и Bearer Token, но было уже немного лень дополнять код)</p>

--------------

<h2>Установка</h2>

```

git clone https://github.com/Reable/task-bnovo
cd task-bnovo
composer install

```

-----------------

<h2>Запуск</h2>

```

docker-compose up -d

```

----------------

<h1>Были созданы все маршруты согласно CRUD</h1>

<h2>C - Create - Запрос на создание гостя в бд</h2>

```

Method: POST
Route: http://localhost:3000/api/user
headers: {
    "Content-Type"  : "application/json",
    "Authorization" : "............"
}
body: {
    "name"    : "Ivan",
    "surname" : "Razmyslov",
    "email"   : "reabletop@gmail.com",
    "phone"   : "+79121111111"
}

```

<h3 style="color:green">Правильное исполнение запроса</h3>

```

headers: {
    "Content-Type"  : "application/json",
}
body: {
    "message"   : "Guest created",
    "success"   : true,
    "statusCode": 201
}
status: 201 Created

```

<h3 style="color:red">Ошибка валидации - ответ</h3>

```

headers: {
    "Content-Type"  : "application/json",
}
body: {
    "error": {
        "message": "Key 'Имя ключа' is required"
    },
    "statusCode": 409
}
status: 409 Conflict

```

<h3 style="color:red">Запрос на добавление уже существующего пользователя - ответ</h3>

```

headers: {
    "Content-Type"  : "application/json",
}
body: {
    "error": {
        "message": "Email or phone already exists"
    },
    "statusCode": 409
}
status: 409 Conflict

```

----------------

<h2>R - Read - Запрос на чтение пользователя или пользователей</h2>

<h3>Чтение одного пользователя или нескольких по совпадающим данным поиска</h3>

```

Method: GET

Route: http://localhost:3000/api/user?id=1
Route: http://localhost:3000/api/user?name="Имя пользователя"
Route: http://localhost:3000/api/user?phone="+79129234567"
Route: http://localhost:3000/api/user?email="email@email.com"

headers: {
    "Content-Type"  : "application/json",
    "Authorization" : "............"
}

```

<h3>Чтение всех пользователей</h3>

```

Method: GET

Route: http://localhost:3000/api/user?id=1
Route: http://localhost:3000/api/user?name="Имя пользователя"
Route: http://localhost:3000/api/user?phone="+79129234567"
Route: http://localhost:3000/api/user?email="email@email.com"

headers: {
    "Content-Type"  : "application/json",
    "Authorization" : "............"
}

```

<h3 style="color:green">Правильное исполнение запроса</h3>
<p>В обоих случаях вы в ответ получите массив гостей, будьте бдительны и обрабатывайте это.</p>

```

headers: {
    "Content-Type"  : "application/json",
}
body: {
    "guests": [
        {
            "id"      : 1,
            "name"    : "Ivan",
            "surname" : "Razmyslov",
            "phone"   : "+791211111111",
            "email"   : "reabletop@gmail.com",
            "country" : "RU"
        }
    ],
    "statusCode": 200
}
status: 200 OK

```

<h3>Сервер автоматически запуститься по порту 3000 - http://localhost:3000</h3>

-----------------

<h2>U - Update - Обновление данных гостя</h2>
<p>Обновление может быть частичное или полное</p>
<p>Поле "id" в этом запросе является обязательным</p>

```

Method: PUT
Route: http://localhost:3000/api/user
headers: {
    "Content-Type"  : "application/json",
    "Authorization" : "............"
}
body: {
    "id"      : 1,
    "name"    : "Alex",
}

```

<h3 style="color:green">Правильное исполнение запроса</h3>

```

headers: {
    "Content-Type"  : "application/json",
}
body: {
    "message"    : "Guest updated",
    "values": {
        "name"   : "Alex"
    },
    "success"    : true,
    "statusCode" : 200
}
status: 200 OK

```

<h3 style="color:red">Не передан идентификатор "id" - ошибка валидации</h3>

```

headers: {
    "Content-Type"  : "application/json",
}
body: {
    "error": {
        "message": "Key id is required"
    },
    "statusCode": 409
}
status: 409 Conflict

```

```

headers: {
    "Content-Type"  : "application/json",
}
body: {
    "error": {
        "message": "Key id is not numeric"
    },
    "statusCode": 409
}
status: 409 Conflict

```

<h3 style="color:red">Не переданы параметры для обновления</h3>

```

headers: {
    "Content-Type"  : "application/json",
}
body: {
    "error": {
        "message": "You have not entered the data for the update"
    },
    "statusCode": 409
}
status: 409 Conflict

```

<h3 style="color:red">Не найден гость</h3>

```

headers: {
    "Content-Type"  : "application/json",
}
body: {
    "error": {
        "message": "Not found guest"
    },
    "statusCode": 404
}
status: 404 Not Found

```

------------------

<h2>D - Delete - Удаление данных гостя</h2>
<p>Удаление было решено реализовать полное, а не просто пометка в таблице deleted - 1)</p>
<p>Поле "id" в этом запросе является обязательным</p>

```

Method: DELETE
Route: http://localhost:3000/api/user
headers: {
    "Content-Type"  : "application/json",
    "Authorization" : "............"
}
body: {
    "id" : 1
}

```

<h3 style="color:green">Успешное удаление гостя</h3>

```

headers: {
    "Content-Type"  : "application/json",
}
body: {
    "message"    : "Guest updated",
    "id"         : 1,
    "success"    : true,
    "statusCode" : 200
}
status: 200 OK

```

<h3 style="color:red">Не найден гость</h3>

```

headers: {
    "Content-Type"  : "application/json",
}
body: {
    "error": {
        "message" : "Not found guest"
    },
    "statusCode": 404
}
status: 404 Not Found

```