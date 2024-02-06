<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?= PROJECT_ROOT ?>/public/css/global.css">
    <title>Error!</title>
</head>
<body>
    <?php
        if(!isset($error)) {
            $route = routeTo('/');
            header("Location: $route");
        }
    ?>
    <style>
        * {
            box-sizing: border-box;
        }
        body {
            margin: 0;
            padding: 0;
            min-height: 100vh;
            overflow-y: auto;
        }
        .container {
            width: 100%;
            height: 100vh;
            overflow-x: hidden;
            overflow-y: auto;

            display: grid;
            place-items: center;

            background-color: #121212;
            color: rgba(255, 255, 255, 0.5);
        }

        .container .error-box {
            display: flex;
            align-items: baseline;
            justify-content: center;
            flex-direction: column;
            gap: 1rem;
            padding-block: 1rem;
        }

        .container .error-box .error-box-message {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .container .error-box .error-content {
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
        }

        .container .error-box .error-content .error-text {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }

        .container .error-box .error-content .error-text li {
            list-style-type: circle;
            clear: both;
        }

        .container .error-box .error-content .error-text li span {
            color: rgba(255, 255, 255 0.3);
            font-size: 0.85rem;
            font-weight: 300;
            float: right;
        }

        .container .error-box .error-content .error-text li br {
            display: none
        }

        .container .error-box span {
            height: 100%;
        }

        .container .error-box p,
        .container .error-box span {
            font-size: 1.4rem;
            font-weight: 300;
        }

        .container .error-box .error-test {
            width: 100%;
        }
        .container .error-box .error-test p.style-test span {
            float: right;
        }
    </style>
    <div class="container">

        <div class="error-box">
            <!-- <div class="error-test">
                <p class="style-test">Kreshna<span>12:19</span></p>
            </div> -->
            <div class="error-object">
                <?php
                    echo "<pre>";
                    // print_r($error);
                    echo "</pre>";
                ?>
            </div>
            <div class="error-box-message">
                <p>Error</p>
                <span>|</span>
                <p>Exception</p>
            </div>
            <div class="error-content">
                <div class="error-title">
                    <h1><?= $error->getMessage() ?></h1>
                </div>

                <ol class="error-text">
                <?php
                    // echo "<pre>";
                    $trace = $error->getTrace();
                    for($i = 0; $i < count($trace); $i++) {
                        echo "<li>";
                        echo $trace[$i]['file'] ?? $trace[$i]['line'], "<br>";
                        echo "<span> at line ";
                        echo $trace[$i]['line'];
                        echo "</span>";
                        echo "</li>";
                    }

                    // echo "</pre>";
                ?>
                </ol>
            </div>
            <?php
                // $wow = throw new \Exception($error);
            ?>
            <p></p>
            <p>

            </p>
            <p>
            </p>
        </div>
    </div>
</body>
</html>
