<?php  

class Database{

    private static $dbHost = "localhost";
    private static $dbName = "BestieBurger";
    private static $dbUser = "root";
    private static $dbPassword = "root";
    
    private static $connexion = NULL;

    public static function connect(){

        try{
            self::$connexion = new PDO("mysql:host=" . self::$dbHost . ";dbname=" . self::$dbName,self::$dbUser,self::$dbPassword);
        }catch(PDOException $e){
            die($e->getMessage());
        }
        return self::$connexion;

    }

    public static function disconnect(){

        self::$connexion = NULL;
        
    }

}

Database::connect();
?>