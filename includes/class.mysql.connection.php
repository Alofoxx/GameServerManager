<?php
  // Tell the database we want to use UTF8 
  $options = array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'); 
     
  // Try catch statement for database connection
  try 
  { 
    $db = new PDO("mysql:host={$host};dbname={$dbname};charset=utf8", $username, $password, $options); 
  } 
  catch(PDOException $ex) 
  { 
    die("Failed to connect to the database!"); 
  } 
     
  // This statement configures PDO to throw an exception when it encounters 
  // an error.  This allows us to use try/catch blocks to trap database errors. 
  $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); 
     
  // This statement configures PDO to return database rows from your database using an associative 
  // array.  This means the array will have string indexes, where the string value 
  // represents the name of the column in your database. 
  $db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC); 
?>