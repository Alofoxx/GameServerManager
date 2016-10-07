<?php 
  
  // Basic includes and requirements
  require 'configuration.php';
  require 'includes/class.mysql.connection.php';
  require 'includes/class.remove.magicquotes.php';
  require 'includes/class.session.start.php';
     
  if(!empty($_POST)) 
  { 
    if(empty($_POST['username'])) 
    { 
            die("Please enter a username."); 
        } 
         
        if(empty($_POST['password'])) 
        { 
            die("Please enter a password."); 
        } 
        if(!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) 
        { 
            die("Invalid E-Mail Address"); 
        } 
        $query = " 
            SELECT 
                1 
            FROM users 
            WHERE 
                username = :username 
        "; 
        $query_params = array( 
            ':username' => $_POST['username'] 
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
         
        $row = $stmt->fetch(); 
         
        if($row) 
        { 
            die("This username is already in use"); 
        } 
         
        $query = " 
            SELECT 
                1 
            FROM users 
            WHERE 
                email = :email 
        "; 
         
        $query_params = array( 
            ':email' => $_POST['email'] 
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
         
        $row = $stmt->fetch(); 
         
        if($row) 
        { 
            die("This email address is already registered"); 
        }

        $query = " 
            INSERT INTO users ( 
                username,
                email,                
                password, 
                salt
            ) VALUES ( 
                :username,
                :email,                
                :password, 
                :salt 
            ) 
        "; 

        $salt = dechex(mt_rand(0, 2147483647)) . dechex(mt_rand(0, 2147483647)); 
         
        $password = hash('sha256', $_POST['password'] . $salt); 
         
        for($round = 0; $round < 65536; $round++) 
        { 
            $password = hash('sha256', $password . $salt); 
        } 
         
        $query_params = array( 
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
         
        header("Location: login.php"); 

        die("Redirecting to login.php"); 
    } 
     
?> 

<!DOCTYPE html>
<html lang="en">
  <head>
    <title>Game Server Manager</title>
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