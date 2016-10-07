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
     
  // Throw an exception when an error is encountered.  
  $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); 
     
  // Fetch the rows of the database using an associative array.
  $db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC); 