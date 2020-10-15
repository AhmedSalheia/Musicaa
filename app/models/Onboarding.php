<?php


namespace MUSICAA\models;


use MUSICAA\lib\database\DatabaseHandler;

class Onboarding extends AbstractModel
{
    public $id;
    public $img;
    public $title;
    public $details;
    public $lastModified;

    public static $tableName = 'onboarding';
    public static $primaryKey = 'id';
    public static $uniqueKey = '';
    public static $timeCol = 'lastModified';
    public static $tableSchema = [
        'id'            =>  self::DATA_TYPE_STR,
        'img'           =>  self::DATA_TYPE_STR,
        'title'         =>  self::DATA_TYPE_STR,
        'details'       =>  self::DATA_TYPE_STR,
        'lastModified'  =>  self::DATA_TYPE_STR
    ];

    public function createTable()
    {
        DatabaseHandler::factory()->exec('
            CREATE TABLE onboarding(
                id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
                img VARCHAR(30) NOT NULL UNIQUE,
                title VARCHAR(30) NOT NULL UNIQUE,
                details VARCHAR(250) NOT NULL,
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

            $senddata->img = $datum->img;
            $senddata->title = $datum->title;
            $senddata->details = $datum->details;

            if(!$senddata->save())
            {
                echo '<br>Error Adding '. $senddata->title . ' To '.self::$tableName.' Table In The Database<br>';
                $bool = false;
            }
        }

        if ($bool === true)
        {
            echo '<br>Finished Adding Data To '.self::$tableName.' Table In The Database<br>';
        }
    }
}