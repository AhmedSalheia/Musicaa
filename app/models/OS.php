<?php


namespace MUSICAA\models;


use MUSICAA\lib\database\DatabaseHandler;

class OS extends AbstractModel
{
    public $id;
    public $OS;

    public static $tableName = 'os';
    public static $primaryKey = 'id';
    public static $uniqueKey = 'OS';
    public static $timeCol = '';
    public static $tableSchema = [
        'id'            =>  self::DATA_TYPE_INT,
        'OS'          =>  self::DATA_TYPE_STR
    ];

    public function createTable()
    {
        DatabaseHandler::factory()->exec('
            CREATE TABLE os(
                id INT NOT NULL PRIMARY KEY,
                OS VARCHAR(15) NOT NULL UNIQUE
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
            $senddata->OS = $datum->OS;

            if(!$senddata->save())
            {
                echo '<span style="color:red;">Error Adding '. $senddata->id . ' To '.self::$tableName.' Table In The Database</span>'."<br>";
                $bool = false;
            }
        }

        if ($bool === true)
        {
            echo '<span style="color:green;">Finished Adding Data To '.self::$tableName.' Table In The Database</span>'."<br>";
        }
    }
}