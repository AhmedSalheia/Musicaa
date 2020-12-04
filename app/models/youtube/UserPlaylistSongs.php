<?php


namespace MUSICAA\models\youtube;


use MUSICAA\lib\database\DatabaseHandler;

class UserPlaylistSongs extends \MUSICAA\models\AbstractModel
{
    public $id;
    public $playlistId;
    public $songId;

    public static $tableName = 'userPlaylistSongs';
    public static $primaryKey = 'id';
    public static $uniqueKey = '';
    public static $timeCol = '';
    public static $tableSchema = [
        'id'            =>  self::DATA_TYPE_STR,
        'playlistId'    =>  self::DATA_TYPE_STR,
        'songId'        =>  self::DATA_TYPE_STR
    ];

    public function createTable()
    {
        DatabaseHandler::factory()->exec('
            CREATE TABLE userPlaylistSongs(
                id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
                playlistId VARCHAR(20) NOT NULL,
                songId VARCHAR(50) NOT NULL,
                FOREIGN KEY (playlistId) REFERENCES userPlaylists(id),
                FOREIGN KEY (songId) REFERENCES video(id)
            )
        ');
    }
}