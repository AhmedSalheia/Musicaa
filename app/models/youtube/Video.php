<?php


namespace MUSICAA\models\youtube;


use MUSICAA\lib\database\DatabaseHandler;

class Video extends \MUSICAA\models\AbstractModel
{
    public $id;
    public $link;

    public static $tableName = 'video';
    public static $primaryKey = 'id';
    public static $uniqueKey = '';
    public static $timeCol = '';
    public static $tableSchema = [
        'id'            =>  self::DATA_TYPE_STR,
        'link'          =>  self::DATA_TYPE_STR
    ];

    public function createTable()
    {
        DatabaseHandler::factory()->exec('
            CREATE TABLE video(
                id VARCHAR(20) NOT NULL PRIMARY KEY,
                link TEXT NOT NULL
            )
        ');
    }

}