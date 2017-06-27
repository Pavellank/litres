<?php
Class ActiveRecord
{

private $tableName;
private $primaryKey;

public $metaData =[]; //key => type
Private static $instance = null;


Public static model()
{
if(is_null(self::$insrabce)) {
 self::$instance = new self($session['pdo']);
}

Return self::$instance;
}


Private function __construct(PDO $pdo)
{

$q = $this->prepare("SHOW COLUMNS FROM " . $this->tableName);
$q->execute();
$table_fields = $q->fetchAll();

Foreach($table_fields as $tableField)
{
$this->metaData[$tableField['field'] = $tableField['type'];
}
}

Return $this;

}
}