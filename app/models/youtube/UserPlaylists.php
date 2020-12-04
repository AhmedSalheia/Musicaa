<?php


namespace MUSICAA\models\youtube;


use MUSICAA\lib\database\DatabaseHandler;
use MUSICAA\models\AbstractModel;

class UserPlaylists extends AbstractModel
{

    public $id;
    public $name;
    public $userId;
    public $img="defaults\defaultPlaylist.png";

    public static $tableName = 'userPlaylists';
    public static $primaryKey = 'id';
    public static $uniqueKey = '';
    public static $timeCol = '';
    public static $tableSchema = [
        'id'    =>  self::DATA_TYPE_STR,
        'name'  =>  self::DATA_TYPE_STR,
        'userId'=>  self::DATA_TYPE_STR,
        'img'   =>  self::DATA_TYPE_STR
    ];

    public function createTable()
    {
        DatabaseHandler::factory()->exec('
            CREATE TABLE userPlaylists(
                id VARCHAR(20) NOT NULL PRIMARY KEY,
                name VARCHAR(40) NOT NULL,
                userId INT NOT NULL,
                img VARCHAR(60) NOT NUll,
                FOREIGN KEY (userId) REFERENCES user(id)
            )
        ');
    }
}