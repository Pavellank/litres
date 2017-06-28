<?php

Class ActiveRecord implements ActiveRecordInterface
{
    public $tableName;
    private $primaryKey;
    public $metaData = array(); //key => type
    private static $instance = null;

    /**
     * @var $pdo PDO
     */
    private $pdo;


    /**
     * @return ActiveRecord
     */
    public static function model()
    {
        if(is_null($this->pdo)){
        				$this->pdo = $_SESSION['pdo'];
        }
        $className = get_called_class();
        if (is_null(self::$instance[$className])) {
            $classObj =  new $className();
            $classObj->tableName = $classObj::tableName();
            $query = $classObj->pdo->prepare("SHOW COLUMNS FROM " . $classObj->tableName);
            $query->execute();
            $tableFieldArr = $query->fetchAll();

            foreach ($tableFieldArr as $tableField) {
                $classObj->metaData[$tableField['Field']] = $tableField['Type'];
            }
            self::$instance[$className] = $classObj;
        }

        return self::$instance[$className];
    }

    public function setAttributes($attributeArr){
        foreach (array_keys($this->metaData) as $attributeName) {
            if(isset($attributeArr[$attributeName])){
                $this->$attributeName = $attributeArr[$attributeName];
            }
        };
    }


    public function findByPK($pk)
    {
        $query = $this->pdo->prepare('SELECT * FROM ' . $this->tableName . ' WHERE ' . $this->primaryKey . ' = %s');
        $query->bindValue($pk, PDO::PARAM_INT);
        $result = $query->fetchAll();

        return $this->__toModel($result);
    }


    public function findByAttribute(array $attribute, $sign = '=', $condition = 'AND')
    {
        $sign       = $sign == 'LIKE' ? 'LIKE' : '=';
        $condition  = $condition == 'AND' ? 'AND' : 'OR';

        $sql = 'SELECT * FROM ' . $this->tableName . ' WHERE ';
        $where = array();
        foreach ($attribute as $attributeName => $valueArr) {
            foreach ($valueArr as $item) {
                $where[] = $attributeName . ' ' . $sign . ' ' . ($sign == 'LIKE' ? '%' . $item . '%' : $item);
            }
        }
        $whereStr = implode($condition, $where);

        $result = $this->pdo->query($sql . $whereStr)->fetchAll();

        return $this->__toModel($result);
    }


    public function findAll()
    {
        $result = $this->pdo->query('SELECT * FROM ' . $this->tableName)->fetchAll();

        return $this->__toModel($result);
    }


    private function __toModel(array $rowArr)
    {
        $return = array();

        foreach ($rowArr as $row) {
            $model = clone $this;
            $model->setAttributes($row);

            $return[] = $model;
        }

        return $return;
    }
}