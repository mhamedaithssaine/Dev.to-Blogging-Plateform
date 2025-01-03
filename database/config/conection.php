<?php 
namespace Database\config;
use PDO;
use Dotenv\Dotenv;
use PDOException;
class  conection {
    
   private $conn;
  
    public function __construct(){
      $dotenv = Dotenv::createImmutable(__DIR__ . '/../..');
   $dotenv->load();
      $host = $_ENV['username']; 
      $dbname = $_ENV['db_name'];
      $username = $_ENV['host']; 
      $password = $_ENV['password'];
      
      try {
         $this->conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
         $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
         echo "success '$dbname'.";

    } catch (PDOException $e) {
      echo "Erreur de connexion : " . $e->getMessage();
  }
}


   public function getPDO()
   {
         return $this->conn ;
   }

}
