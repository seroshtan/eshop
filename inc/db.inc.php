<?php
header('Content-Type:text/html; charset=utf-8');//уточнение кодировки, избавляет от кракозябров
/* Задаем константы на подключение к БД */
define('DB_HOST','localhost');
define('DB_LOGIN','root');
define('DB_PASSWORD','');
define('DB_NAME','eshop');
/*--------------------------------------*/
define('ORDERS_LOG','orders.log');//данные покупателя
/*Корзина покупателя*/
$basket = array();
/*Кол-во товаров в корзине покупателя*/
$count = 0;
/*Запрос на подключение к БД*/
$link = mysqli_connect(DB_HOST, DB_LOGIN, DB_PASSWORD, DB_NAME) or die(mysqli_connect_error());

basketInit();
