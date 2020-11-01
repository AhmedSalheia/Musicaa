<?php


namespace MUSICAA\models;


use MUSICAA\lib\database\DatabaseHandler;

class Tracker extends AbstractModel
{
    public $id;
    public $loginId;
    public $colChanged;
    public $changedFrom;
    public $changedTo;
    public $timeChanged;

    public static $tableName = 'tracker';
    public static $primaryKey = 'id';
    public static $uniqueKey = '';
    public static $timeCol = 'timeChanged';
    public static $tableSchema = [
        'id'            =>  self::DATA_TYPE_INT,
        'loginId'       =>  self::DATA_TYPE_INT,
        'colChanged'    =>  self::DATA_TYPE_STR,
        'changedFrom'   =>  self::DATA_TYPE_STR,
        'changedTo'     =>  self::DATA_TYPE_STR,
        'timeChanged'   =>  self::DATA_TYPE_STR
    ];

    public function createTable()
    {
        DatabaseHandler::factory()->exec('
            CREATE TABLE tracker(
                id INT AUTO_INCREMENT NOT NULL PRIMARY KEY,
                loginId INT NOT NULL,
                colChanged VARCHAR(50) NOT NULL,
                changedFrom VARCHAR(100) NOT NULL,
                changedTo VARCHAR(100) NOT NULL,
                timeChanged DATETIME DEFAULT now()
            )
        ');
    }
}