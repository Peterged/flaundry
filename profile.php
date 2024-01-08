<?php include_once 'app/utils/routeTo.php' ?>


<?php
    
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
</head>

<body>
    <span>PARAMS['ID']: <?php
                        echo $_PARAMS['nama'] . "<br>";
                        ?></span>
    <span>Data User: <?php
                        echo "<pre>";
                        print_r($dataUser);
                        echo "</pre>";
                        ?></span>

    <!-- <form method="POST" action="<?= routeTo('/users/profile-process') ?>">
        <input type="text" name="username" id="username" placeholder="Username">
        <input type="text" name="password" id="password" placeholder="Password">
        <input type="submit" value="SUBMIT">
    </form> -->
</body>

</html>
