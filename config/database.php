<?php

//  - file used for connecting to the database.
/*
    - The code below shows the database credentials and a method to get a database connection using PDO. If you're not yet familiar with PDO, please learn from our PHP OOP CRUD Tutorial first.
    
    - Create api folder. Open api folder. Create config folder. Open config folder. Create database.php file. Place the following code inside it.
*/


class Database{
 
    // specify your own database credentials
    private $host = "localhost";
    private $db_name = "hossamapi";
    private $username = "root";
    private $password = "";
    public $conn;
 
    // get the database connection
    public function getConnection(){
 
        $this->conn = null;
 
        try{
            $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name, $this->username, $this->password);
            $this->conn->exec("set names utf8");
        }catch(PDOException $exception){
            echo "Connection error: " . $exception->getMessage();
        }
 
        return $this->conn;
    }
}
?>