# prestashop-clear-url-id
Данный модуль создан только для Prestashop 1.7

Модуль дает возможность удалять значения id из URL.

Для установки можно скачать и установить как модуль файл crk_urls_clear.zip
Либо скопировать файлы из папки override в соответствующие папки override своего проекта.

После установки в настройках "Параметры магазины" -> "Трафик и SEO" -> "SEO и URL" в разделе "Схема URL" изменяем виды ссылок.

```
Путь к товарам: {category}/{-:id_product_attribute}{rewrite}{-:ean13}
Путь к категории: {rewrite}
Пусть к странице: content/{rewrite}
Путь к категории страницы: pages/category/{rewrite}
```

Проверенно для приведённых выше видов ссылок.

[![ko-fi](https://az743702.vo.msecnd.net/cdn/kofi2.png?v=0)](https://www.paypal.me/corsik/3)
