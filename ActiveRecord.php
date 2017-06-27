<?php
Class ActiveRecord
{
	private $tableName;
	private $primaryKey;
	public $metaData = []; //key => type
	private static $instance = null;
	
	
	public static function model()
	{
		if(is_null(self::$insrabce)) {
	 		self::$instance = new self($session['pdo']);
		}
	
		return self::$instance;
	}
	
	
	private function __construct(PDO $pdo)
	{
		$query = $this->prepare("SHOW COLUMNS FROM " . $this->tableName);
		$query->execute();
		$tableFieldArr = $query->fetchAll();
	
		foreach($tableFieldArr as $tableField) {
			$this->metaData[$tableField['field']] = $tableField['type'];
		}
		
		return $this;
	}


}