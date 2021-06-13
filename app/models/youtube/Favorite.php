<?php


namespace MUSICAA\models\youtube;


use MUSICAA\lib\database\DatabaseHandler;
use MUSICAA\models\AbstractModel;

class Favorite extends AbstractModel
{
    public $id;
    public $userId;

    public static $tableName = 'favorites';
    public static $primaryKey = 'id';
    public static $uniqueKey = 'userId';
    public static $timeCol = '';
    public static $tableSchema = [
        'id'            =>  self::DATA_TYPE_STR|self::DATA_TYPE_NULL,
        'userId'        =>  self::DATA_TYPE_STR
    ];

    public function createTable()
    {
        DatabaseHandler::factory()->exec('
            CREATE TABLE favorites(
                id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
                userId INT NOT NULL UNIQUE,
                FOREIGN KEY (userId) REFERENCES user(id)
            )
        ');
    }
}