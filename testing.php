<?php
include("config.php");
include("Const.php");
include("ActiveRecordInterface.php");
include("ActiveRecord.php");
include("Posts.php");
include("LitresData.php");
$_SESSION['pdo'] = $pdo;

$liresDataArr = LitresData::model()->findAll();
if(is_null($liresDataArr)){
	throw new Exception("Нет книг для импорта");
	
}
foreach ($liresDataArr as $key => $liresData) {
	// Если книга не была еще импортирована
	if(is_null($liresData->local_book_id)){
		// Книга еще не была вставлена - новая книга
		if(is_null($liresData->local_book_id_litres_catalog)){
			$post = Posts::model();
			$post->se

		} else { // Книга уже есть в базе, осталось обновить

		}
	}
}
session_destroy();