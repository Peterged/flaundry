<?php include_once 'app/utils/routeTo.php' ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
</head>
<body>
    <h1>KRESHNA DHANA</h1>
    <span>Umur: 200</span>
    <span>params: <?php 
        echo $_PARAMS['id'];
    ?></span>
    <span>body: <?php 
        echo $_BODY['name'] . ' ' . $_BODY['job'];
    ?></span>

    <form method="POST" action="<?= routeTo('/users/profile-process') ?>">
        <input type="text" name="username" id="username" placeholder="Username">
        <input type="text" name="password" id="password" placeholder="Password">
        <input type="submit" value="SUBMIT">
    </form>
</body>
</html>
