<?php

  require 'database.php';

  $message = '';

  if (!empty($_POST['email']) && !empty($_POST['password']) && !empty($_POST['cpassword'])) {
    $message = 'todos los datos';
    $records = $conn->prepare('SELECT id, email, password FROM users WHERE email = :email');
    $records->bindParam(':email', $_POST['email']);
    $records->execute();
    $results = $records->fetch(PDO::FETCH_ASSOC);
    if (is_countable($results) && count($results)> 0){
      $message = 'correo ya registrado use otro';
    }else{
      if($_POST['password'] == $_POST['cpassword']){
        $sql = "INSERT INTO users (email, password) VALUES (:email, :password)";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':email', $_POST['email']);
        $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
        $stmt->bindParam(':password', $password);
        if ($stmt->execute()) {
          $message = 'Successfully created new user';
        } else {
          $message = 'Sorry there must have been an issue creating your account';
          }
      }else{
        $message = 'la confirmacion no coincidse';
      }   
    }

    }else{
    $message = 'le isiseron falta datos';
  }
?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>SignUp</title>
    <link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
  </head>
  <body>

    <?php require 'partials/header.php' ?>

    <?php if(!empty($message)): ?>
      <p> <?= $message ?></p>
     
    <?php endif; ?>

    <h1>SignUp</h1>
    <span>or <a href="login.php">Login</a></span>

    <form action="signup.php" method="POST">
      <input name="email" type="text" placeholder="Enter your email">
      <input name="password" type="password" placeholder="Enter your Password">
      <input name="cpassword" type="password" placeholder="Confirm Password">
      <input type="submit" value="Submit">
    </form>

  </body>
</html>
