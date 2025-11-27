Webasyst PHP Framework
----------------------

Webasyst is PHP framework for creating backend and frontend applications.

Homepage:        https://www.webasyst.com/
Documentation:   https://developers.webasyst.com/
Repository:      https://github.com/webasyst/webasyst-framework

Webasyst is released under the LGPL license.
Copyright 2011-2018 WebAsyst LLC.
-----------------------

## Тестовое задание
плагин "Подарок для вас"

### Требования системы

- PHP 7.4 или выше
- MySQL 5.7+ или MariaDB 10.2+
- Webasyst Framework с установленным приложением Shop-Script
- Веб-сервер (Apache/Nginx)

### Способы установки

Есть два способа развернуть плагин:

---

## Способ 1: Установка через Git (полный проект)

Этот способ подходит, если вы хотите получить базовый проект с Webasyst, Shop-Script и нужным плагином.

### Шаг 1: Клонирование репозитория

```bash
git clone https://github.com/kepo4ka/shop-script-gift-for-you-plugin.git
```

### Шаг 2: Настройка базы данных

1. Создайте новую базу данных MySQL c названием `gift_shop`:
2. Импортируйте дамп базы данных (файл `gift_shop.sql`).

### Шаг 3: Настройка конфигурации

1. Откройте или создайте файл `wa-config/db.php` (если его нет) и настройте подключение к базе данных:

```php
return array(
    'default' => array(
        'host' => 'localhost',
        'port' => '3306',
        'user' => 'root',
        'password' => 'ваш_пароль',
        'database' => 'gift_shop',
        'type' => 'mysqli',
    ),
);
```

### Шаг 4: Доступ к админ-панели

- URL: `http://localhost/webasyst/`
- Логин: `gift`
- Пароль: `gift`

### Шаг 5: Настройка плагина

1. Войдите в админ-панель Webasyst
2. Перейдите в раздел настройки плагина `http://localhost/webasyst/shop/?action=plugins#/giftforyou`
4. В настройках выберите "Набор товаров" из выпадающего списка
5. Сохраните настройки
---

## Способ 2: Установка плагина в существующий проект

Этот способ подходит, если у вас уже установлен Webasyst с Shop-Script.

### Шаг 1: Распаковка архива

1. Распакуйте архив `giftforyou.zip` в папку плагинов:
   ```
   wa-apps/shop/plugins/giftforyou/
   ```

2. Убедитесь, что структура папок следующая:
   ```
   wa-apps/shop/plugins/giftforyou/
   ├── img/
   ├── js/
   ├── lib/
   ├── templates/
   └── ...
   ```

### Шаг 2: Активация плагина

1. Откройте файл `wa-config/apps/shop/plugins.php`

2. Добавьте строку активации плагина (если её нет):

```php
<?php

return array(
    // ... другие плагины ...
    'giftforyou'  => true,
);
```

3. Сохраните файл

### Шаг 3: Настройка плагина

1. Войдите в админ-панель Webasyst
2. Перейдите в приложение Shop-script > Плагины
3. Найдите плагин "Подарок для вас" и нажмите "Настроить"
4. В настройках выберите "Набор товаров" из выпадающего списка
5. Сохраните настройки

---

## Проверка работы плагина

После установки и настройки плагина:

1. Откройте в браузере страницу: `http://localhost/shop/gift/` (`http://localhost/shop` - эта часть может отличаться)
2. Вы должны увидеть страницу с заголовком "Подарок для вас!" и кнопкой "Крутить"
3. Нажмите кнопку - должен появиться случайный товар из выбранного набора
4. Заполните форму с email и отправьте - на указанный email должно прийти письмо с информацией о товаре
5. ...
6. Profit
