<?php


namespace MUSICAA\models\youtube;


use MUSICAA\lib\database\DatabaseHandler;

class Video extends \MUSICAA\models\AbstractModel
{
    public $id;
    public $playlistId;
    public $link;
    public $name;
    public $img;

    public static $tableName = 'video';
    public static $primaryKey = 'id';
    public static $uniqueKey = 'playlistId';
    public static $timeCol = '';
    public static $tableSchema = [
        'id'            =>  self::DATA_TYPE_STR,
        'playlistId'    =>  self::DATA_TYPE_STR,
        'name'          =>  self::DATA_TYPE_STR,
        'link'          =>  self::DATA_TYPE_STR,
        'img'           =>  self::DATA_TYPE_STR
    ];

    public function createTable()
    {
        DatabaseHandler::factory()->exec('
            CREATE TABLE video(
                id VARCHAR(50) NOT NULL PRIMARY KEY,
                playlistId VARCHAR(50) NOT NULL,
                link TEXT NOT NULL,
                name VARCHAR(50) NOT NULL,
                img VARCHAR(250) NOT NULL,
                FOREIGN KEY (playlistId) REFERENCES playlists(id)
            )
        ');
    }

}