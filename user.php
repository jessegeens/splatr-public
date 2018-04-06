<?php

require_once('dbcred.php');

class User{

  private $email;
  private $id;
  private $totalInvestment;
  private $totalProfit;
  private $connection;

  public function __construct($sqlconnection, $email)
  {
    $this->setEmail($email);
    $this->setConnection($sqlconnection);
    $this->setId($this->getConnection()->query("SELECT ID FROM userdata WHERE email = '".$this->getEmail()."'")->fetch_assoc()["ID"]);
  }

  private function getConnection(){
    return $this->connection;
  }

  private function setConnection($connection){
    $this->connection = $connection;
  }

  public function getEmail(){
    return $this->email;
  }

  private function setEmail($email){
    $this->email = $email;
  }

  public function getId(){
    return $this->id;
  }

  private function setId($id){
    $this->id = $id;
  }

  public function getTotalInvestment()
  {
    $totalInvestment = 0;
    $query_result = $this->getConnection()->query("SELECT * FROM currencies WHERE email = '".$this->getEmail()."'");
    while($row = $query_result->fetch_assoc()) {
          $totalInvestment += $row['buyprice'];
    }
    return $totalInvestment;
  }

  public function getFavorites()
  {
    $query_result = $this->getConnection()->query("SELECT favorites FROM userdata WHERE email = '".$this->getEmail()."'");
    $favoritesArray = explode(";", $query_result->fetch_assoc()["favorites"]);
    return $favoritesArray;
  }

}
?>
