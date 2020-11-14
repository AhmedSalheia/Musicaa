<?php


namespace MUSICAA\models\youtube;


use MUSICAA\lib\database\DatabaseHandler;

class Channels extends \MUSICAA\models\AbstractModel
{
    public $id;
    public $name;
    public $img;

    public static $tableName = 'channels';
    public static $primaryKey = 'id';
    public static $uniqueKey = '';
    public static $timeCol = '';
    public static $tableSchema = [
        'id'            =>  self::DATA_TYPE_STR,
        'name'          =>  self::DATA_TYPE_STR,
        'img'           =>  self::DATA_TYPE_STR
    ];

    public function createTable()
    {
        DatabaseHandler::factory()->exec('
            CREATE TABLE channels(
                id VARCHAR(50) NOT NULL PRIMARY KEY,
                name VARCHAR(50) NOT NULL,
                img VARCHAR(250) NOT NULL
            )
        ');
    }
}