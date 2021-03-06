<?php


namespace MUSICAA\models;


use MUSICAA\lib\database\DatabaseHandler;

class Devices extends AbstractModel
{
    public $id;
    public $name;
    public $UUID;
    public $OS;
    public $is_primary="n";

    public static $tableName = 'devices';
    public static $primaryKey = 'id';
    public static $uniqueKey = 'UUID';
    public static $timeCol = '';
    public static $tableSchema = [
        'id'            =>  self::DATA_TYPE_INT,
        'name'          =>  self::DATA_TYPE_STR,
        'UUID'          =>  self::DATA_TYPE_STR,
        'OS'            =>  self::DATA_TYPE_INT,
        'is_primary'    =>  self::DATA_TYPE_STR
    ];

    public function createTable()
    {
        DatabaseHandler::factory()->exec('
            CREATE TABLE devices(
                id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
                name VARCHAR(50) NOT NULL,
                UUID VARCHAR(50) NOT NULL UNIQUE,
                OS INT NOT NULL,
                is_primary ENUM("y","n"),
                FOREIGN KEY (OS) REFERENCES os(id)
            )
        ');
    }
}