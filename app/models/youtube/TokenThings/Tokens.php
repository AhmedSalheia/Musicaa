<?php


namespace MUSICAA\models\youtube\TokenThings;


use MUSICAA\lib\database\DatabaseHandler;

class Tokens extends \MUSICAA\models\AbstractModel
{
    public $id;
    public $token;
    public $is_prim="n";

    public static $tableName = 'tokens';
    public static $primaryKey = 'id';
    public static $uniqueKey = '';
    public static $timeCol = '';
    public static $tableSchema = [
        'id'            =>  self::DATA_TYPE_STR,
        'token'         =>  self::DATA_TYPE_STR,
        'is_prim'       =>  self::DATA_TYPE_STR
    ];

    public function createTable()
    {
        DatabaseHandler::factory()->exec('
            CREATE TABLE tokens(
                id INT AUTO_INCREMENT NOT NULL PRIMARY KEY,
                TOKEN TEXT NOT NULL UNIQUE,
                is_prim ENUM("n","y") DEFAULT "n"
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
            $senddata->token = $datum->token;
            $senddata->is_prim = $datum->is_prim;

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
