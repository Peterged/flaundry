<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?= $PROJECT_ROOT ?>/public/css/api_test.css">
    <title>API Test</title>
</head>

<body>
    <pre>
    <?php
    print_r($data);
    ?>
    </pre>
    <div class="box">
        <h1>API Test</h1>

        <?php
        if (isset($data)) {
            foreach ($data as $key => $value) {
                $id = $value['id'];
                $name = $value['name'];
                $email = $value['email'];
                $age = $value['age'];

                echo <<<EOL
                        <span>[ $id ] : $name | $email | $age</span><br>
                    EOL;
            }
        }

        $newData = fetch($URLROOT . '/api/users/robertos');
        if(!empty($newData)) {
            foreach ($newData as $key => $value) {
                $id = $value['id'];
                $name = $value['name'];
                $email = $value['email'];
                $age = $value['age'];

                echo <<<EOL
                        <span>[ $id ] : $name | $email | $age</span><br>
                    EOL;
            }
        }
        ?>
    </div>
</body>

</html>