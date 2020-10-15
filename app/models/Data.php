<?php


namespace MUSICAA\models;


use MUSICAA\lib\database\DatabaseHandler;

class Data extends AbstractModel
{
    public $id;
    public $data;
    public $lastModified;

    public static $tableName = 'data';
    public static $primaryKey = 'id';
    public static $uniqueKey = '';
    public static $timeCol = 'lastModified';
    public static $tableSchema = [
        'id'            =>  self::DATA_TYPE_STR,
        'data'          =>  self::DATA_TYPE_STR,
        'lastModified'  =>  self::DATA_TYPE_STR
    ];

    public function createTable()
    {
        DatabaseHandler::factory()->exec('
            CREATE TABLE data(
                id VARCHAR(20) NOT NULL PRIMARY KEY,
                data TEXT NOT NULL,
                lastModified DATETIME DEFAULT now()
            )
        ');
    }

    public function addToTable(array $data)
    {
        $bool = true;
        $data = json_decode(json_encode($data));
        foreach ($data as $datum)
        {
            $senddata = new self();
            $senddata->id = $datum->id;
            $senddata->data = $datum->data;

            if(!$senddata->save())
            {
                echo 'Error Adding '. $senddata->id . ' To '.self::$tableName.' Table In The Database'."\n";
                $bool = false;
            }
        }

        if ($bool === true)
        {
            echo 'Finished Adding Data To '.self::$tableName.' Table In The Database'."\n";
        }
    }
}