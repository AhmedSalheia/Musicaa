<?php


namespace MUSICAA\models;


use MUSICAA\lib\database\DatabaseHandler;

class Login extends AbstractModel
{
    public $id;
    public $userId;
    public $deviceId;

    public static $tableName = 'login';
    public static $primaryKey = 'id';
    public static $uniqueKey = 'userId';
    public static $timeCol = '';
    public static $tableSchema = [
        'id'            =>  self::DATA_TYPE_INT,
        'userId'        =>  self::DATA_TYPE_INT,
        'deviceId'      =>  self::DATA_TYPE_INT
    ];

    public function createTable()
    {
        DatabaseHandler::factory()->exec('
            CREATE TABLE login(
                id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
                userId INT NOT NULL,
                deviceId INT NOT NULL,
                FOREIGN KEY (userId) REFERENCES user(id),
                FOREIGN KEY (deviceId) REFERENCES devices(id)
            )
        ');
    }
}