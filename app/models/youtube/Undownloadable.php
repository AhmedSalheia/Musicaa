<?php


namespace MUSICAA\models\youtube;


use MUSICAA\lib\database\DatabaseHandler;

class Undownloadable extends \MUSICAA\models\AbstractModel
{
    public $id;
    public $playlistId;
    public $name;
    public $img;

    public static $tableName = 'undownloadable';
    public static $primaryKey = 'id';
    public static $uniqueKey = 'playlistId';
    public static $timeCol = '';
    public static $tableSchema = [
        'id'            =>  self::DATA_TYPE_STR,
        'playlistId'    =>  self::DATA_TYPE_STR,
        'name'          =>  self::DATA_TYPE_STR,
        'img'           =>  self::DATA_TYPE_STR
    ];

    public function createTable()
    {
        DatabaseHandler::factory()->exec('
            CREATE TABLE undownloadable(
                id VARCHAR(50) NOT NULL PRIMARY KEY,
                playlistId VARCHAR(50) NOT NULL,
                name VARCHAR(50) NOT NULL,
                img VARCHAR(250) NOT NULL,
                FOREIGN KEY (playlistId) REFERENCES playlists(id)
            )
        ');
    }

}