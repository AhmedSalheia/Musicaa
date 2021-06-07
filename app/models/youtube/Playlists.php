<?php


namespace MUSICAA\models\youtube;


use MUSICAA\lib\database\DatabaseHandler;
use MUSICAA\lib\traits\Helper;

class Playlists extends \MUSICAA\models\AbstractModel
{
    public $id;
    public $channelId;
    public $name;
    public $img;

    public static $tableName = 'playlists';
    public static $primaryKey = 'id';
    public static $uniqueKey = 'channelId';
    public static $timeCol = '';
    public static $tableSchema = [
        'id'            =>  self::DATA_TYPE_STR,
        'channelId'     =>  self::DATA_TYPE_STR,
        'name'          =>  self::DATA_TYPE_STR,
        'img'           =>  self::DATA_TYPE_STR
    ];

    public function createTable()
    {
        DatabaseHandler::factory()->exec('
            CREATE TABLE playlists(
                id VARCHAR(50) NOT NULL PRIMARY KEY,
                channelId VARCHAR(50) NOT NULL,
                name VARCHAR(50) NOT NULL,
                img VARCHAR(250) NOT NULL,
                FOREIGN KEY (channelId) REFERENCES channels(id)
            )
        ');
    }

    public static function getMainPlaylist($channelId)
    {
        $playlist = self::get('SELECT * FROM playlists WHERE channelId="'.$channelId.'" AND name="Main Playlist"');
        if ($playlist === false)
        {
            $channel = Channels::getByPK($channelId);

            $playlist = new self();
            $playlist->id = Helper::randName(35);
            $playlist->name = "Main Playlist";
            $playlist->img = $channel->img;
            $playlist->channelId = $channel->id;

            if ($playlist->save('upd') === false)
            {
                return false;
            }
        }else
        {
            $playlist = $playlist[0];
        }

        return $playlist;
    }
}