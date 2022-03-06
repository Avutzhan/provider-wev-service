# Provider-wev-service

## Server

| Ubuntu | 20 |
| :---: | :---: |
| Nginx | 1.18.0 |
| php | 7.4 | 
| php-fpm | 7.4 | 
| mysql | 8.0 | 
| soap | 1.2 |

## Directory Structure

    .
    ├── ...
    ├── manual-test             # пока тестировал в ручную постманом так как это быстрее
    │   ├── arguments           # потом надо прикрутить библиотеку php unit
    │   ├── results              
    │   └── ...
    ├── nginx   
    ├── Service   
    ├── wsdl   
    ├── server.php   
    └── ...


## Description

Пополнение счета электронного кошелька картой
Получение данных о юзере

## Experience

Срок 3 дня из них 2 дня изучал SOAP протокол и работу
с php web services
Думал уже отказаться от тестового задания но на второй день
я прозрел и понял смысл soap xml wsdl

wsdl описание сервиса и нам остается только грамотно реализовать
функционал это очень удобно так как большинство предусловий и послусловий
написано за тебя

как обычно в последний 3 день реализовал тестовое задание
так как писал днем и дописывал ночью многое недоделал

плюс 2 дня не хватило на полное понимание как должен выглядеть
веб сервис с правильной архитектурой и структурой папок

плюс можно сделать какой нибудь рефакторинг, улучшить логику

## Refactoring

TODO:: 
* баланс уходит в минус исправить (пока только проверкой баланса нижний пункт) можно на уровне бд ограничить DONE
* хватает ли денег для пополнения проверка DONE
* add response class with http response code and text constants DONE
* DB::getWallet отрефакторил под one-liner если успею отрефакторю так же остальные функции DONE
*
* запилить нормальные трансакции пхпшные. Если вдруг база упадет во время трансакции чтобы денги не улетели в пустоту. in progress
* применить SRP и разделить логику сложных функций которые сильно бросаются в глаза
* вынести конект к базе в .env потом в db.config потом с конфига брать данные это по best practices
* и реализовать паттерн репозиторий для запросов к бд
* подумать о структуре проекта переименовать некоторые классы чтобы было красиво

пока только это бросается в глаза

## Архитектура проекта и бд

на счет архитектуры самого проекта и выбор фреймворков не могу адекватно оценить
так как нет большого опыта в soap web services только в rest. ресты писал на ларавел так как простая понятная да много 
чего ненужного в ней но все юзают ее везде в mvc и rest api. но я бы для rest сервисов юзал бы phalcon микрофреймворк.
по примеру python который юзает микрофреймворки для рестов как flask, fastApi, tornado.
в golang микрофреймворки echo, fasthttp, fiber для рестов.

по бд архитектуру мало данных (нет опыта в платежках наверное если бы был опыт спокойно придумал бы архитектуру бд) а 
так из тех задания не понятно как строить архитектуру бд. там только два простых метода надо реализовать даже если бы
реализовал все тоже сомневаюсь что много данныех для архиектуры думаю это надо у бизнес аналитика
просить больше данных о проекте функционале и так далее или у продукт овнера спрашивать у тех
кто собирал данные у клиента. На основе их требований уже что то делать. Или просто иметь опыт в этих делах и задавать
просто уточняющие вопросы.

## Tools

это в конце можно прикрутить
psalm xdebug phpunit docker






