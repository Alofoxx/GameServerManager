<?php
  // This block of code is used to undo magic quotes.  Magic quotes are a terrible
  // feature that was removed from PHP as of PHP 5.4.  However, older installations 
  // of PHP may still have magic quotes enabled and this code is necessary to 
  // prevent them from causing problems.  For more information on magic quotes: 
  // http://php.net/manual/en/security.magicquotes.php 
  if(function_exists('get_magic_quotes_gpc') && get_magic_quotes_gpc()) 
  { 
    function undo_magic_quotes_gpc(&$array) 
    { 
      foreach($array as &$value) 
      { 
        if(is_array($value)) 
        { 
          undo_magic_quotes_gpc($value); 
        } 
        else 
        { 
          $value = stripslashes($value); 
        } 
      } 
    } 
    undo_magic_quotes_gpc($_POST); 
    undo_magic_quotes_gpc($_GET); 
    undo_magic_quotes_gpc($_COOKIE); 
  } 