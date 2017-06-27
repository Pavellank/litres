<?php
session_start();
error_reporting(-1);

define ( 'DATALIFEENGINE', true );
define ( 'ROOT_DIR', dirname ( __FILE__ ) . '..' );
define ( 'ENGINE_DIR', ROOT_DIR . '/engine' );
require_once  '../wp-config.php';
define('DBHOST',    DB_HOST);
define('DBUSER',    DB_USER);
define('DBPASS',    DB_PASSWORD);


define('DBNAME',    DB_NAME);
/*$db_link = mysql_connect(DBHOST,DBUSER,DBPASS);
mysql_select_db(DBNAME);
mysql_query("SET NAMES utf8");*/

$optionArr = array(
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
);

$pdo = new PDO("mysql:host=".DBHOST.";dbname=".DBNAME.";charset=utf8", DBUSER, DBPASS, $optionArr);

$partner_id = 299612548;	//получить в ЛитРес
define('PARTNER_ID', 299612548);
define('SITE', 'http://я-книга.рф');
$partner_a_id = 0; //для совпадений только по автору
$siteUrl = 'http://я-книга.рф';
$fullStoryTemplate =
    '<img src="'.$siteUrl.'/upload/%s.jpg" alt="%s - %s" width="228" height="368" class="aligncenter size-full" />
    <strong>Автор: </strong>%s
    <strong>Название: </strong>%s
    <strong>Жанр: </strong>%s
    <strong>Язык книги: </strong>Русский
    <strong>Формат: </strong>FB2, ePub, pdf, txt и другие
    %s
    <a class="button green" href="%s">Скачать!</a>';
?>