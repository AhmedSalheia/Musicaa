<?php


namespace MUSICAA\models;


use MUSICAA\lib\database\DatabaseHandler;

class Genders extends AbstractModel
{
    public $id;
    public $gender;

    public static $tableName = 'genders';
    public static $primaryKey = 'id';
    public static $uniqueKey = 'gender';
    public static $timeCol = '';
    public static $tableSchema = [
        'gender'        =>  self::DATA_TYPE_STR
    ];

    public function createTable()
    {
        DatabaseHandler::factory()->exec('
            CREATE TABLE genders(
                id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
                gender VARCHAR(20) NOT NULL
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
            $senddata->gender = $datum->gender;

            if(!$senddata->save())
            {
                echo '<br><span style="color:red;">Error Adding '. $senddata->id . ' To '.self::$tableName.' Table In The Database</span><br>';
                $bool = false;
            }
        }

        if ($bool === true)
        {
            echo '<br><span style="color:green;">Finished Adding Data To '.self::$tableName.' Table In The Database</span>'."<br>";
        }
    }
}