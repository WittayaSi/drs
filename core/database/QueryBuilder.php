<?php

class QueryBuilder
{
    protected $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function selectAll($table)
    {
        try{
            $statement = $this->pdo->prepare('select * from '. $table);
            $statement->execute();
            return $statement->fetchAll(PDO::FETCH_CLASS);
        }catch(Exception $e){
            die($e->getMessage());
        }

    }

    public function selectWhere($table, $column, $indicator)
    {
        try{
            $statement = $this->pdo->prepare("select * from ".$table." where ".$column." = '".$indicator."'");
            //die(var_dump($statement));
            $statement->execute();
            return $statement->fetchAll(PDO::FETCH_CLASS);
        }catch(Exception $e){
            die($e->getMessage());
        }
    }

    public function selectSql($sql)
    {
        try{
            $statement = $this->pdo->prepare($sql);
            $statement->execute();
            return $statement->fetchAll(PDO::FETCH_CLASS);
        }catch(Exception $e){
            die($e->getMessage());
        }
    }

    public function insert($table, $parameters)
    {
        //insert into %s (%s) values (%s)

        $sql = sprintf(
            'insert into %s (%s) values (%s)',
            $table,
            implode(', ', array_keys($parameters)),
            ':'.implode(', :', array_keys($parameters))
        );

        //die(var_dump($this->pdo->prepare($sql)));
        try
        {
            $statement = $this->pdo->prepare($sql);
            $statement->execute($parameters);
        }
        catch(Exception $e)
        {
            die($e->getMessage());
        }


        //die(var_dump($sql));
    }
}
