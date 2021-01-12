<?php


namespace MUSICAA\models;


use MUSICAA\lib\database\DatabaseHandler;

class Languages extends AbstractModel
{
    public $id;
    public $name;
    public $full_name;

    public static $tableName = 'languages';
    public static $primaryKey = 'id';
    public static $uniqueKey = 'name';
    public static $timeCol = '';
    public static $tableSchema = [
        'id'            =>  self::DATA_TYPE_INT,
        'name'          =>  self::DATA_TYPE_STR,
        'full_name'     =>  self::DATA_TYPE_STR
    ];

    public function createTable()
    {
        DatabaseHandler::factory()->exec('
            CREATE TABLE languages(
                id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
                name VARCHAR(2) NOT NULL,
                full_name VARCHAR(20) NOT NULL
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
            $senddata->full_name = $datum->full_name;

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
