<?php


namespace MUSICAA\models;


use MUSICAA\lib\database\DatabaseHandler;

class Tracker extends AbstractModel
{
    public $id;
    public $userId;
    public $action;
    public $at;
    public $user_rel="n";
    public $timeChanged;

    public static $tableName = 'tracker';
    public static $primaryKey = 'id';
    public static $uniqueKey = '';
    public static $timeCol = 'timeChanged';
    public static $tableSchema = [
        'id'            =>  self::DATA_TYPE_INT,
        'userId'        =>  self::DATA_TYPE_INT,
        'action'        =>  self::DATA_TYPE_STR,
        'at'            =>  self::DATA_TYPE_STR,
        'user_rel'      =>  self::DATA_TYPE_STR,
        'timeChanged'   =>  self::DATA_TYPE_STR
    ];

    public function createTable()
    {
        DatabaseHandler::factory()->exec('
            CREATE TABLE tracker(
                id INT AUTO_INCREMENT NOT NULL PRIMARY KEY,
                userId INT NOT NULL,
                action VARCHAR(50) NOT NULL,
                at VARCHAR(50) NOT NULL,
                user_rel ENUM("y","n") NOT NULL,
                timeChanged DATETIME DEFAULT now(),
                FOREIGN KEY (userId) REFERENCES user(id)
            )
        ');
    }
}