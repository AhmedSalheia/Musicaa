<?php


namespace MUSICAA\models;


use MUSICAA\lib\database\DatabaseHandler;

class TokenMod extends AbstractModel
{
    public $loginId;
    public $modi;

    public static $tableName = 'tokenMod';
    public static $primaryKey = 'loginId';
    public static $uniqueKey = '';
    public static $timeCol = '';
    public static $tableSchema = [
        'loginId'     =>  self::DATA_TYPE_INT,
        'modi'        =>  self::DATA_TYPE_INT
    ];

    public function createTable()
    {
        DatabaseHandler::factory()->exec('
            CREATE TABLE tokenMod(
                loginId INT NOT NULL PRIMARY KEY,
                modi INT NOT NULL,
                FOREIGN KEY (loginId) REFERENCES login(id)
            )
        ');
    }
}