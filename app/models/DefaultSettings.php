<?php


namespace MUSICAA\models;


use MUSICAA\lib\database\DatabaseHandler;

class DefaultSettings extends AbstractModel
{
    public $os;
    public $permissions;
    public $theme;
    public $language;
    public $additional_screen;
    public $auto_update;

    public static $tableName = 'defaultSettings';
    public static $primaryKey = 'os';
    public static $uniqueKey = '';
    public static $timeCol = '';
    public static $tableSchema = [
        'os'                =>  self::DATA_TYPE_INT,
        'permissions'       =>  self::DATA_TYPE_INT,
        'theme'             =>  self::DATA_TYPE_INT,
        'language'          =>  self::DATA_TYPE_INT,
        'additional_screen' =>  self::DATA_TYPE_INT,
        'auto_update'       =>  self::DATA_TYPE_INT
    ];

    public function createTable()
    {
        DatabaseHandler::factory()->exec('
            CREATE TABLE defaultSettings(
                os INT NOT NULL PRIMARY KEY,
                permissions INT NOT NULL,
                theme INT NOT NULL,
                language INT NOT NULL,
                additional_screen INT NOT NULL,
                auto_update INT NOT NULL,
                FOREIGN KEY (os) REFERENCES os(OS),
                FOREIGN KEY (theme) REFERENCES themes(id),
                FOREIGN KEY (language) REFERENCES languages(id),
            )
        ');
    }
}