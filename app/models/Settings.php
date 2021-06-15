<?php


namespace MUSICAA\models;


use MUSICAA\lib\database\DatabaseHandler;

class Settings extends AbstractModel
{
    public $loginId;
//    public $permissions;
//    public $additional_screen;
    public $language;
    public $theme;
//    public $auto_update;

    public static $tableName = 'settings';
    public static $primaryKey = 'loginId';
    public static $uniqueKey = '';
    public static $timeCol = '';
    public static $tableSchema = [
        'loginId'           =>  self::DATA_TYPE_INT,
//        'permissions'       =>  self::DATA_TYPE_INT,
        'theme'             =>  self::DATA_TYPE_INT,
        'language'          =>  self::DATA_TYPE_INT,
//        'additional_screen' =>  self::DATA_TYPE_INT,
//        'auto_update'       =>  self::DATA_TYPE_INT
    ];

    public function createTable()
    {
        //permissions INT NOT NULL,
        //
        //additional_screen INT NOT NULL,
        //auto_update INT NOT NULL,
        DatabaseHandler::factory()->exec('
            CREATE TABLE settings(
                loginId INT NOT NULL PRIMARY KEY,
                theme INT NOT NULL,
                language INT NOT NULL,
                FOREIGN KEY (loginId) REFERENCES login(id),
                FOREIGN KEY (theme) REFERENCES themes(id),
                FOREIGN KEY (language) REFERENCES languages(id)
            )
        ');
    }
}