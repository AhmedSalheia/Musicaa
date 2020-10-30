<?php


namespace MUSICAA\models;


use MUSICAA\lib\database\DatabaseHandler;

class Devices extends AbstractModel
{
    public $id;
    public $name;
    public $UUID;
    public $OS;

    public static $tableName = 'devices';
    public static $primaryKey = 'id';
    public static $uniqueKey = 'UUID';
    public static $timeCol = '';
    public static $tableSchema = [
        'id'            =>  self::DATA_TYPE_INT,
        'name'          =>  self::DATA_TYPE_STR,
        'UUID'          =>  self::DATA_TYPE_STR,
        'OS'            =>  self::DATA_TYPE_INT
    ];

    public function createTable()
    {
        DatabaseHandler::factory()->exec('
            CREATE TABLE devices(
                id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
                name VARCHAR(50) NOT NULL,
                UUID TEXT NOT NULL UNIQUE,
                OS INT NOT NULL,
                FOREIGN KEY (OS) REFERENCES os(id)
            )
        ');
    }
}