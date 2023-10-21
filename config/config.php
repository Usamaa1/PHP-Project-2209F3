<?php


define('server','mysql:host=localhost;dbname=coffee-blend-2209f3');
define('user','root');
define('password','');


try
{
    $connection = new PDO(server,user,password);
}
catch(PDOException $e)
{
    echo $e->getMessage();
}






?>