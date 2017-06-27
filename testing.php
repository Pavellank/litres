<?php
include("config.php");
include("ActiveRecordInterface.php");
include("ActiveRecord.php");
include("Posts.php");
include("LitresData.php");
$_SESSION['pdo'] = $pdo;

$liresDataArr = LitresData::model()->findAll();
session_destroy();