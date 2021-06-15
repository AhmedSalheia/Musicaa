<?php


namespace MUSICAA\models\youtube;


use MUSICAA\lib\database\DatabaseHandler;

class FavoriteSong extends \MUSICAA\models\AbstractModel
{
    public $id;
    public $favoriteId;
    public $videoId;

    public static $tableName = 'favoritesongs';
    public static $primaryKey = 'id';
    public static $uniqueKey = '';
    public static $timeCol = '';
    public static $tableSchema = [
        'favoriteId'    =>  self::DATA_TYPE_STR,
        'videoId'       =>  self::DATA_TYPE_STR
    ];

    public function createTable()
    {
        DatabaseHandler::factory()->exec('
            CREATE TABLE favoritesongs(
                id INT NOT NuLL PRIMARY KEY AUTO_INCREMENT,
                favoriteId INT NOT NULL,
                videoId VARCHAR(50) NOT NULL,
                FOREIGN KEY (favoriteId) REFERENCES favorites(id),
                FOREIGN KEY (videoId) REFERENCES video(id)
            )
        ');
    }

}