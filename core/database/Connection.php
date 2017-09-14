<?php

class Connection
{
    public static function make($config)
    {
        try{
            //return new PDO('sqlsrv:server=localhost\SQLEXPRESS;Database=DRS', '', '');

            return new PDO(
                $config['connection'].';Database='.$config['name'],
                $config['username'],
                $config['password'],
                $config['options']
            );
        }catch(PDOException $e){
            die($e->getMessage());
        }
    }
}
