<?php


namespace MUSICAA\models;


use MUSICAA\lib\database\DatabaseHandler;

class Settings extends AbstractModel
{
    public function __construct()
    {

    }

    public $loginId;
    public $permissions=7;
    public $additional_screen=1;
    public $theme=1;
    public $auto_update=1;

    public static $tableName = 'settings';
    public static $primaryKey = 'id';
    public static $uniqueKey = '';
    public static $timeCol = '';
    public static $tableSchema = [
        'id'            =>  self::DATA_TYPE_STR,
        'data'          =>  self::DATA_TYPE_STR,
        'lastModified'  =>  self::DATA_TYPE_STR
    ];

    public function createTable()
    {
        DatabaseHandler::factory()->exec('
            CREATE TABLE settings(
                loginId INT NOT NULL PRIMARY KEY,
                data TEXT NOT NULL,
                FOREIGN KEY (loginId) REFERENCES login(id)
            )
        ');
    }
}