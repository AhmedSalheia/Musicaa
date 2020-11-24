<?php


namespace MUSICAA\models;


use MUSICAA\lib\database\DatabaseHandler;

class TrackUserData extends AbstractModel
{
    public $trackId;
    public $changedFrom;
    public $changedTo;

    public static $tableName = 'trackuserdata';
    public static $primaryKey = 'trackId';
    public static $uniqueKey = '';
    public static $timeCol = '';
    public static $tableSchema = [
        'trackId'     =>  self::DATA_TYPE_INT,
        'changedFrom'   =>  self::DATA_TYPE_STR,
        'changedTo'     =>  self::DATA_TYPE_STR,
    ];

    public function createTable()
    {
        DatabaseHandler::factory()->exec('
            CREATE TABLE trackuserdata(
                trackId INT NOT NULL PRIMARY KEY,
                changedFrom VARCHAR(100) NOT NULL,
                changedTo VARCHAR(100) NOT NULL,
                FOREIGN KEY (trackId) REFERENCES tracker(id)
            )
        ');
    }

}