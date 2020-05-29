# map-paint-group
https://paint-group.ru/about-the-group/geographical/

Через админку Bitrix добавляется элемент в инфоблок. По адресу определяются координаты меток (событие OnAfterIBlockElementAddHandler в php_interface/init.php) и, используя "Оптимальное добавление множества меток" (https://tech.yandex.ru/maps/jsbox/2.1/object_manager), на карту добавляется метка.

При клике по метке на карте (geographical/object_manager.js) ajax'ом (ajax/city-geografiya.php) выводятся карточки с информацией об объектах сгруппированные по городам. Информация также выводится при запросе в поисковой строке города на карте.

Реализовано обновление и удаление меток на карте при удалении элемента из инфоблока (события в php_interface/init.php).
