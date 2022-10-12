![](https://storage.yandexcloud.net/webazon/github/yoomoney.png)

------

# yoomoney-wallet

[![Latest Version](https://img.shields.io/github/v/release/katsef/yoomoney.svg?style=plastic)](https://github.com/katsef/yoomoney/releases)
[![Packagist Downloads](https://img.shields.io/packagist/dt/webazon/yoomoney?color=green&style=plastic)](https://packagist.org/packages/webazon/yoomoney)

## Неофициальная PHP библиотека [API кошелька ЮMoney](https://yoomoney.ru/docs/wallet).

API кошелька позволяет частным лицам использовать возможности сервиса ЮMoney:

- получать и отправлять переводы и совершать платежи с банковских карт или из кошелька [ЮMoney](https://yoomoney.ru/)

- запрашивать информацию о кошельке: баланс, историю платежей и переводов;

- получать HTTP-уведомления о переводах.

  

------

## Установка

Можно установить используя менеджер пакетов [Composer](https://getcomposer.org)

```bash
$ composer require webazon/yoomoney
```

или скачать пакет с [GitHub](https://github.com/katsef/yoomoney)

------

**!!!** **Все доступные методы возвращают объект, содержащий результат запроса к сервису [ЮMoney](https://yoomoney.ru).**

| Параметр          | Тип     | Описание                                                     |
| ----------------- | ------- | ------------------------------------------------------------ |
| status            | boolean | Статус операции ( *`true` / `false`* )                       |
| result_code       | int     | Код ответа сервера                                           |
| response          | object  | Тело результата операции при успехе (status = *`true`*) согласно [документации](https://yoomoney.ru/docs/wallet) |
| error             | string  | Ошибка при неуспешном выполнении операции.                   |
| error_description | string  | Описание ошибки                                              |

------

## Запрос авторизации

Перед запросом авторизации должно быть [зарегестрировано приложение](https://yoomoney.ru/myservices/new) и получены `client_id` и `client_secret`

```php
$auth = new Webazon\Yoomoney\Auth($options);
$auth_url = $auth -> getAuthUrl();
```

| Параметр       | Тип       | Описание                                                     |
| -------------- | --------- | ------------------------------------------------------------ |
| **options**    | **array** | **Массив параметров для авторизации**                        |
| *client_id     | string    | Идентификатор приложения, полученный при регистрации.        |
| *client_secret | string    | Секретное слово для проверки подлинности приложения.         |
| *redirect_uri  | string    | URI, на который сервер OAuth передает результат авторизации. |
| *scope         | string    | [Список запрашиваемых прав](https://yoomoney.ru/docs/wallet/using-api/authorization/protocol-rights). Разделитель элементов списка — пробел. Элементы списка чувствительны к регистру. |
| *instance_name | string    | [***Не обязательный параметр***] Идентификатор экземпляра авторизации в приложении. Необязательный параметр. Позволяет получить несколько авторизаций для одного приложения. |

Успешный результат (в поле `result`)

| Параметр | Тип    | Описание                                                     |
| -------- | ------ | ------------------------------------------------------------ |
| auth_url | string | Путь (ссылка) куда нужно отправть пользователя для авторизации. |

------

## Получение токена

```php
$access_token = $auth -> getAccessToken($code);
```

$code - Временный токен ( `authorization code` ) полученный на этапе Авторизации в `redirect_uri`.

------

## Формат запроса API

object **api** ( [string *$metod*] ,[array *$options*] )

- *$metod* 	 - Метод запроса в соответсвии с [официальной документацией](https://yoomoney.ru/docs/wallet/user-account/account-info)
- *$options*    - Входные параметры в соответсвии с [официальной документацией](https://yoomoney.ru/docs/wallet/user-account/account-info)

```php
$api = new Webazon\Yoomoney\Api($access_token);

$result = $api->api('account-info',$options);
$result = $api->api('<МЕТОД>',$options);
```

------

## ![](https://storage.yandexcloud.net/webazon/github/massachusetts_institute_of_technology.png) [License](https://github.com/katsef/yoomoney/blob/master/LICENSE)  

```
© 2022 ИП Кацеф Алексей Михайлович
```

