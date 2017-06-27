<?php
Class Terms
{
    public $term_id;
    public $name;

private $pdo;

    public function tableName()
{
    return 'wp_terms';
}


public function __construct(PDO $pdo)
{
    $this->pdo = $pdo;
}


    public function findAuthor ($name, $sName = '')
{
  $query = $this->pdo->prepare();
  $sql = "SELECT * FROM " . $this->tableName() . " WHERE name LIKE '%%s%'";
  $query->bindValue($name, PDO::PARAM_STR);

  if($sName != ''){
      $sql .= " AND name LIKE '%%s%'";
      $query->bindValue($sName, PDO::PARAM_STR);
  }
    $author = $query->execute($sql);

  return $author;
}


}