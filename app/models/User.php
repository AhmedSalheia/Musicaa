<?php


namespace MUSICAA\models;


use MUSICAA\lib\database\DatabaseHandler;

class User extends AbstractModel
{
    public $id;
    public $firstname;
    public $middlename="";
    public $lastname;
    public $phone;
    public $email;
    public $password;
    public $country;
    public $gender;
    public $verified = 'n';
    public $lastModified;

    public static $tableName = 'user';
    public static $primaryKey = 'id';
    public static $uniqueKey = 'email';
    public static $timeCol = 'lastModified';
    public static $tableSchema = [
        'id'            =>  self::DATA_TYPE_STR,
        'firstname'     =>  self::DATA_TYPE_STR,
        'middlename'    =>  self::DATA_TYPE_STR,
        'lastname'      =>  self::DATA_TYPE_STR,
        'phone'         =>  self::DATA_TYPE_STR,
        'email'         =>  self::DATA_TYPE_STR,
        'password'      =>  self::DATA_TYPE_STR,
        'country'       =>  self::DATA_TYPE_STR,
        'gender'        =>  self::DATA_TYPE_INT,
        'verified'      =>  self::DATA_TYPE_STR,
        'lastModified'  =>  self::DATA_TYPE_STR
    ];

    public function createTable()
    {
        DatabaseHandler::factory()->exec('
            CREATE TABLE user(
                id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
                firstname VARCHAR(15) NOT NULL, 
                middlename VARCHAR(15) NOT NULL, 
                lastname VARCHAR(15) NOT NULL,
                phone VARCHAR(20) NOT NULL,
                email VARCHAR(50) NOT NULL UNIQUE,
                password TEXT NOT NULL,
                country VARCHAR(2) NOT NULL,
                gender INT NOT NULL,
                verified ENUM("n","y") DEFAULT "n",
                lastModified DATETIME DEFAULT now(),
                FOREIGN KEY (gender) REFERENCES genders(id)
            )
        ');
    }

}