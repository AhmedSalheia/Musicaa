<?php


namespace MUSICAA\models;


use MUSICAA\lib\database\DatabaseHandler;

class Verification extends AbstractModel
{
    public $id;
    public $userId;
    public $verification;

    public static $tableName = 'verification';
    public static $primaryKey = 'id';
    public static $uniqueKey = 'userId';
    public static $timeCol = '';
    public static $tableSchema = [
        'id'            =>  self::DATA_TYPE_INT,
        'userId'        =>  self::DATA_TYPE_INT,
        'verification'  =>  self::DATA_TYPE_STR
    ];

    public function createTable()
    {
        DatabaseHandler::factory()->exec('
            CREATE TABLE verification(
                id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
                userId INT NOT NULL UNIQUE,
                verification VARCHAR(6) NOT NULL,
                FOREIGN KEY (userId) REFERENCES user(id)
            )
        ');
    }
}