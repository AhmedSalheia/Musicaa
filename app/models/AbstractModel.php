<?php

namespace MUSICAA\models;

use MUSICAA\lib\database\DatabaseHandler;

class AbstractModel
{
    const DATA_TYPE_BOOL = \PDO::PARAM_BOOL;
    const DATA_TYPE_STR = \PDO::PARAM_STR;
    const DATA_TYPE_INT = \PDO::PARAM_INT;
    const DATA_TYPE_FLOAT = 'anything';
    const DATA_TYPE_NULL = \PDO::PARAM_NULL;

    private function showTableSchema(){
        $sql = '';
        foreach (static::$tableSchema as $columnName => $type){
            $sql .= $columnName . '= :' . $columnName . ',' ;
        }
        return trim($sql,',');
    }

    private static function prepareCols(array $arr)
    {
        return implode(",",$arr);
    }

    private function prepareValue(\PDOStatement &$stmt){
        foreach (static::$tableSchema as $columnName => $type){
            if ($type == 'anything'){
                $sanitized = filter_var($this->$columnName,FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);

                $stmt->bindValue(':'.$columnName,$sanitized);
            }else{
                $stmt->bindValue(':'.$columnName,$this->$columnName, $type);
            }
        }
    }



// Insert To Table:

    private function create(){
        $sql = 'INSERT INTO '.static::$tableName.' SET '.$this->showTableSchema();
        $stmt = DatabaseHandler::factory()->prepare($sql);
        $this->prepareValue($stmt);
        if ($stmt->execute()){
            $id = DatabaseHandler::factory()->lastInsertId();
            $this->{static::$primaryKey} = ($id !== "0")? $id:$this->{static::$primaryKey};
            return true;
        }
        return false;
    }


// Update :

    private function update(){
        $sql = 'UPDATE '.static::$tableName.' SET '.$this->showTableSchema(). ' WHERE '.static::$primaryKey.'= "'.$this->{static::$primaryKey}.'"';
        $stmt = DatabaseHandler::factory()->prepare($sql);
        $this->prepareValue($stmt);
        return $stmt->execute();
    }


// UPDATING OR DELETING :

    public function save($stat=''){
        if (isset(static::$uniqueKey) && static::$uniqueKey != '' && $stat !== 'upd'){

            $get = self::getByUnique($this->{static::$uniqueKey});

            if ($get){
                return $get;
            }
        }

        if (isset(static::$timeCol) && static::$timeCol !== '' && ($this->{static::$timeCol} === '' || $this->{static::$timeCol} === NULL))
        {
            $this->{static::$timeCol} = date('Y-m-d H:i:s');
        }

        return self::getByPk($this->{static::$primaryKey})? $this->update():$this->create();
    }


// DELETE :

    public function delete(){
        $data = $this->{static::$primaryKey};
        if(strpos(strtolower(static::$primaryKey),'id') !== true){
            $data = $this->{static::$primaryKey};
        }

        $sql = 'DELETE FROM '.static::$tableName.' WHERE '.static::$primaryKey.'= "'.$data.'"';
        $stmt = DatabaseHandler::factory()->prepare($sql);
        $stmt->execute();
        return !($stmt->rowCount() === 0);
    }

// SELECTING ALL :
    public static function getAll(array $cols=["*"]){
        $sql = 'SELECT '.(self::prepareCols($cols)).' FROM '.static::$tableName.' ORDER BY '.static::$primaryKey . ' DESC';
        $stmt = DatabaseHandler::factory()->prepare($sql);
        $stmt->execute();
        $results =  $stmt->fetchAll(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, get_called_class());
        return (is_array($results) && !empty($results))?   $results:false;
    }

// SELECTING ONE ELEMENT :

    public static function getByPK($pk,array $cols=["*"]){
        $sql = 'SELECT '.(self::prepareCols($cols)).' FROM '.static::$tableName. ' WHERE '.static::$primaryKey.' = "'.$pk.'"';
        $stmt = DatabaseHandler::factory()->prepare($sql);
        if($stmt->execute() === true){
            if (method_exists(get_called_class(),'__construct')){
                $results = $stmt->fetchAll(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, get_called_class(),array(static::$tableSchema));
            }else{
                $results = $stmt->fetchAll(\PDO::FETCH_CLASS,get_called_class());
            }
            return !empty($results)? array_shift($results) : false;
        }
        return false;
    }

    public static function getByUnique($unique,array $cols=["*"],$return='default'){
        $sql = 'SELECT '.(self::prepareCols($cols)).' FROM '.static::$tableName. ' WHERE '.static::$uniqueKey.' = "'.$unique.'"';
        $stmt = DatabaseHandler::factory()->prepare($sql);
        if($stmt->execute() === true){
            if (method_exists(get_called_class(),'__construct')){
                $results = $stmt->fetchAll(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, get_called_class(),array(static::$tableSchema));
            }else{
                $results = $stmt->fetchAll(\PDO::FETCH_CLASS,get_called_class());
            }
            return !empty($results)? ((count($results) === 1 && strtolower($return)!=='array')? array_shift($results): $results) : false;
        }
        return false;
    }

    public static function getByCol($col,$data,array $cols=["*"],$lim=false,$arr = 'DESC',$order=false){

        $sql = 'SELECT '.(self::prepareCols($cols)).' FROM '.static::$tableName. ' WHERE '.$col.' = "'.$data.'" ORDER By '.(($order === false)? static::$primaryKey:$order).' '.$arr.(($lim != false)? ' LIMIT '.$lim:'');
        $stmt = DatabaseHandler::factory()->prepare($sql);
        if($stmt->execute() === true){
            if (method_exists(get_called_class(),'__construct')){
                $results = $stmt->fetchAll(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, get_called_class(),array(static::$tableSchema));
            }else{
                $results = $stmt->fetchAll(\PDO::FETCH_CLASS,get_called_class());
            }
            return !empty($results)? $results : false;
        }
        return false;
    }

// FOR OTHER SQL QUERYS :

    public static function get($sql,$options = array()){
        $stmt = DatabaseHandler::factory()->prepare($sql);

        if (!empty($options)){
            foreach ($options as $columnName => $type){
                if ($type[0] == 'anything'){
                    $sanitized = filter_var($type[1],FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);

                    $stmt->bindValue(":{$columnName}",$sanitized);
                }else{
                    $stmt->bindValue(":{$columnName}",$type[1], $type[0]);
                }
            }
        }

        $stmt->execute();

        if (method_exists(static::class,'__construct')){
            $result = $stmt->fetchAll(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, static::class, array(static::$tableSchema));
        }else{
            $result = $stmt->fetchAll(\PDO::FETCH_CLASS, static::class);
        }
        return !empty($result)? $result:false;
    }
}
