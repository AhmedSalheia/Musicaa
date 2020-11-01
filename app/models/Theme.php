<?php


namespace MUSICAA\models;


use MUSICAA\lib\database\DatabaseHandler;

class Theme extends AbstractModel
{
    public $id;
    public $name;

    public static $tableName = 'themes';
    public static $primaryKey = 'id';
    public static $uniqueKey = 'name';
    public static $timeCol = '';
    public static $tableSchema = [
        'id'            =>  self::DATA_TYPE_INT,
        'name'    =>  self::DATA_TYPE_STR
    ];

    public function createTable()
    {
        DatabaseHandler::factory()->exec('
            CREATE TABLE themes(
                id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
                name VARCHAR(10) NOT NULL
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
            $senddata->name = $datum->name;

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