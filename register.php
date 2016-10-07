<?php 
  
  // Basic includes and requirements
  require 'configuration.php';
  require 'includes/class.mysql.connection.php';
  require 'includes/class.remove.magicquotes.php';
  require 'includes/class.session.start.php';
  
  // Check if the forum has been submitted or not
  if(!empty($_POST)) 
  { 
    // Check if a username has been entered
    if(empty($_POST['username'])) 
    { 
      die("Please enter a username."); 
    } 
    // Check if a password has been entered
    if(empty($_POST['password'])) 
    { 
      die("Please enter a password."); 
    } 
    // Check if a email address has been entered and is valid
    if(!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) 
    { 
      die("Invalid E-Mail Address"); 
    } 
    // Begin query to check if user/display name is in use
    $query = " 
      SELECT 
        1 
      FROM
        users 
      WHERE 
        username = :username 
    "; 
    
    // Use tokens for query
    $query_params = array( 
      ':username' => $_POST['username'] 
    ); 

    // Run the query with a try/catch to catch errors
    try 
    {  
      $stmt = $db->prepare($query); 
      $result = $stmt->execute($query_params); 
    } 
    catch(PDOException $ex) 
    { 
      die("Failed to run query!"); 
    }
    // Fetch the row should return either as true or false
    $row = $stmt->fetch(); 

    // If row returns true the username is in use
    if($row) 
    { 
      die("This username is already in use"); 
    }

    // Begin query to check if email is in use
    $query = " 
      SELECT 
        1 
      FROM
        users 
      WHERE 
        email = :email 
    "; 
    
    // Use tokens for query
    $query_params = array( 
      ':email' => $_POST['email'] 
    ); 

    // Run the query with a try/catch to catch errors
    try 
    {  
      $stmt = $db->prepare($query); 
      $result = $stmt->execute($query_params); 
    } 
    catch(PDOException $ex) 
    { 
      die("Failed to run query!"); 
    }
    // Fetch the row should return either as true or false
    $row = $stmt->fetch(); 

    // If row returns true the email is in use
    if($row) 
    { 
      die("This email address is already in use"); 
    } 

    // Begin query to insert new user into the database
    $query = " 
      INSERT INTO users ( 
        username, 
        password, 
        salt, 
        email 
      ) VALUES ( 
        :username, 
        :password, 
        :salt, 
        :email 
      ) 
    "; 
    
    // Create a salt
    $salt = dechex(mt_rand(0, 2147483647)) . dechex(mt_rand(0, 2147483647)); 

    // Hash the password with a salt 
    $password = hash('sha256', $_POST['password'] . $salt); 

    // Hash the salted password with a salt 65536 more times
    for($round = 0; $round < 65536; $round++) 
    { 
      $password = hash('sha256', $password . $salt); 
    } 
    
    //
    $query_params = 
      array(
        ':username' => $_POST['username'],
        ':email' => $_POST['email'],
        ':salt' => $salt,                        
        ':password' => $password
      ); 
      try 
      { 
          $stmt = $db->prepare($query); 
          $result = $stmt->execute($query_params); 
      } 
      catch(PDOException $ex) 
      { 
        die("Failed to run query: " . $ex->getMessage()); 
      } 
      
      // Redirect user to login.php
      header("Location: login.php"); 

      // If header is canceled or stopped somehow display die.
      die("Redirecting to login.php"); 
    } 
     
?> 

<!DOCTYPE html>
<html lang="en">
  <head>
    <title>Game Server Manager</title>
    <meta charset="UTF-8">
  </head>
  <body>
    <h1>Register</h1> 
    <form action="register.php" method="post"> 
      Username:<br /> 
      <input type="text" name="username" value="" /> 
      <br /><br /> 
      E-Mail:<br /> 
      <input type="text" name="email" value="" /> 
      <br /><br /> 
      Password:<br /> 
      <input type="password" name="password" value="" /> 
      <br /><br /> 
      <input type="submit" value="Register" /> 
    </form>
  </body>
</html>