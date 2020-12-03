<?php


namespace MUSICAA\models\youtube;


use MUSICAA\lib\database\DatabaseHandler;

class Ids extends \MUSICAA\models\AbstractModel
{
	public $userId;
	public $ids;

	public static $tableName = 'ids';
	public static $primaryKey = 'userId';
	public static $uniqueKey = '';
	public static $timeCol = '';
	public static $tableSchema = [
		'userId'  =>  self::DATA_TYPE_STR,
		'ids'     =>  self::DATA_TYPE_STR
	];

	public function createTable()
	{
		DatabaseHandler::factory()->exec('
            CREATE TABLE ids(
                userId INT NOT NULL PRIMARY KEY,
                ids MEDIUMTEXT NOT NULL
            )
        ');
	}
}