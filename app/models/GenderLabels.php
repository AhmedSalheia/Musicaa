<?php


namespace MUSICAA\models;


use MUSICAA\lib\database\DatabaseHandler;

class GenderLabels extends AbstractModel
{
    public $id;
    public $male;
    public $female;
    public $ratherNotToSay;
    public $custom;

    public static $tableName = 'genderlabels';
    public static $primaryKey = 'id';
    public static $uniqueKey = '';
    public static $timeCol = '';
    public static $tableSchema = [
        'id'            =>  self::DATA_TYPE_STR,
        'male'          =>  self::DATA_TYPE_STR,
        'female'        =>  self::DATA_TYPE_STR,
    'ratherNotToSay' =>  self::DATA_TYPE_STR,
        'custom'        =>  self::DATA_TYPE_STR
    ];

    public function createTable()
    {
        DatabaseHandler::factory()->exec('
            CREATE TABLE genderlabels(
                id VARCHAR(2) NOT NULL PRIMARY KEY,
                male VARCHAR(20) NOT NULL,
                female VARCHAR(20) NOT NULL,
                ratherNotToSay VARCHAR(20) NOT NULL,
                custom VARCHAR(20) NOT NULL
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
            $senddata->male = $datum->male;
            $senddata->female = $datum->female;
            $senddata->ratherNotToSay = $datum->ratherNotToSay;
            $senddata->custom = $datum->custom;

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