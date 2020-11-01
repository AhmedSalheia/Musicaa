<?php


namespace MUSICAA\models;


use MUSICAA\lib\database\DatabaseHandler;

class Status extends AbstractModel
{
    public $id;
    public $loginId;
    public $status;
    public $from_time;
    public $to_time = '0000-00-00 00:00:00';

    public static $tableName = 'status';
    public static $primaryKey = 'id';
    public static $uniqueKey = '';
    public static $timeCol = '';
    public static $tableSchema = [
        'id'            =>  self::DATA_TYPE_INT,
        'loginId'       =>  self::DATA_TYPE_INT,
        'status'        =>  self::DATA_TYPE_STR,
        'from_time'     =>  self::DATA_TYPE_STR,
        'to_time'       =>  self::DATA_TYPE_STR
    ];

    public function createTable()
    {
        DatabaseHandler::factory()->exec('
            CREATE TABLE status(
                id INT AUTO_INCREMENT NOT NULL PRIMARY KEY,
                loginId INT NOT NULL, 
                status ENUM("Active","Background") NOT NULL,
                from_time DATETIME NOT NULL,
                to_time DATETIME NOT NULL,
                FOREIGN KEY (loginId) REFERENCES login(id)
            )
        ');
    }

}