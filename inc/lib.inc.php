<?php
/*Функция для приведения данных к положительному числу*/
function clearInt($data){
	return abs((int)$data);
}
/*Функция для приведения данных в безопасную строку*/
function clearStr($data){
	global $link;
	return mysqli_real_escape_string($link, trim(strip_tags($data)));
}
/*Функция добавления товара в каталог*/
function addItemToCatalog($title, $author, $pubyear, $price){
	global $link;
	$sql = "INSERT INTO catalog(title, author, pubyear, price)
			VALUES(?, ?, ?, ?)";
	if(!$stmt = mysqli_prepare($link, $sql))
		return false;
	mysqli_stmt_bind_param($stmt, "ssii", $title, $author, $pubyear, $price);
	mysqli_stmt_execute($stmt);
	mysqli_stmt_close($stmt);
	return true;	
}
/*---------------------------------------------------------------*/
/*Функция выборки товара из каталога*/
function selectAllItems(){
	global $link;
	$sql = 'SELECT id, title, author, pubyear, price FROM catalog';	
	if(!$result = mysqli_query($link, $sql))
		return false;
	$items = mysqli_fetch_all($result, MYSQLI_ASSOC);
	mysqli_free_result($result);
	return $items;
}
/*Функция сохранения корзины с товарами в куки*/
function saveBasket(){
	global $basket;
	$basket = base64_encode(serialize($basket));
	setcookie('basket',$basket,0x7FFFFFFF);
}
/*Функция либо созданет корзину при посещение магазина впервые либо загружает корзину*/
function basketInit(){
	global $basket, $count;
	if(!isset($_COOKIE['basket'])){
		$basket = array('orderid' => uniqid());
		saveBasket();
	}else{
		$basket = unserialize(base64_decode($_COOKIE['basket']));
		$count = count($basket)-1;
	}
}
/*Функция сохранения товара в корзину*/
function add2Basket($id, $q){
	global $basket;
	$basket[$id] = $q;
	saveBasket();
}
/*Функция выборки товаров из корзины*/
function myBasket(){
	global $link, $basket;
	$goods = array_keys($basket);
	array_shift($goods);
	$ids = implode(",", $goods);
	$sql = "SELECT id, title, author, pubyear, price FROM catalog WHERE id IN ($ids)";
	if(!$result = mysqli_query($link, $sql))
		return false;
	$items = result2Array($result);
	mysqli_free_result($result);
	return $items;
}
function result2Array($data){
	global $basket;
	$arr = array();
	while($row = mysqli_fetch_assoc($data)){
		$row['quantity'] = $basket[$row['id']];
		$arr[] = $row;
	}
	return $arr;
}
/*Функция удаления товаров из корзины*/
function deleteItemFromBasket($id){
	global $basket;
	$basket[$id] = $q;
	saveBasket();
}