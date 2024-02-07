<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?= PROJECT_ROOT ?>/public/css/formAuth.css">
    <title>Register User</title>
</head>

<body>
    <?php
        $displayMessage = App\libraries\Session::getSessionKeyValueAndRemoveOnRefresh('displayMessage');
    ?>
    <div class="container">
        <form method="post" class="wrapper" id="form-login">
            <h1 class="title">Register User</h1>
            <img class="form-image" src="<?= PROJECT_ROOT ?>/public/images/flaundry-logo-icon.png" alt="">

            <!-- NAMA -->
            <div class="input-group">
                <svg width="22" height="22" viewBox="0 0 22 22" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M11 11C12.094 11 13.1432 10.5654 13.9168 9.79182C14.6904 9.01823 15.125 7.96902 15.125 6.875C15.125 5.78098 14.6904 4.73177 13.9168 3.95818C13.1432 3.1846 12.094 2.75 11 2.75C9.90598 2.75 8.85677 3.1846 8.08318 3.95818C7.3096 4.73177 6.875 5.78098 6.875 6.875C6.875 7.96902 7.3096 9.01823 8.08318 9.79182C8.85677 10.5654 9.90598 11 11 11ZM13.75 6.875C13.75 7.60435 13.4603 8.30382 12.9445 8.81954C12.4288 9.33527 11.7293 9.625 11 9.625C10.2707 9.625 9.57118 9.33527 9.05546 8.81954C8.53973 8.30382 8.25 7.60435 8.25 6.875C8.25 6.14565 8.53973 5.44618 9.05546 4.93046C9.57118 4.41473 10.2707 4.125 11 4.125C11.7293 4.125 12.4288 4.41473 12.9445 4.93046C13.4603 5.44618 13.75 6.14565 13.75 6.875ZM19.25 17.875C19.25 19.25 17.875 19.25 17.875 19.25H4.125C4.125 19.25 2.75 19.25 2.75 17.875C2.75 16.5 4.125 12.375 11 12.375C17.875 12.375 19.25 16.5 19.25 17.875ZM17.875 17.8695C17.8736 17.5313 17.6633 16.5137 16.731 15.5815C15.8345 14.685 14.1474 13.75 11 13.75C7.85262 13.75 6.1655 14.685 5.269 15.5815C4.33675 16.5137 4.12775 17.5313 4.125 17.8695H17.875Z" fill="black" />
                </svg>

                <input autofocus type="text" name="nama" id="nama" placeholder="Nama Lengkap" autocomplete="off" required>
            </div>

            <!-- USERNAME -->
            <div class="input-group">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" style="fill: rgba(255, 255, 255, 1);">
                    <path d="M12 2a5 5 0 1 0 5 5 5 5 0 0 0-5-5zm0 8a3 3 0 1 1 3-3 3 3 0 0 1-3 3zm9 11v-1a7 7 0 0 0-7-7h-4a7 7 0 0 0-7 7v1h2v-1a5 5 0 0 1 5-5h4a5 5 0 0 1 5 5v1z"></path>
                </svg>
                <input type="text" name="username" id="username" placeholder="Username" autocomplete="off" required>
            </div>

            <!-- PASSWORD -->
            <div class="input-group">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" style="fill: rgba(255, 255, 255, 1);">
                    <path d="M12 2C9.243 2 7 4.243 7 7v3H6c-1.103 0-2 .897-2 2v8c0 1.103.897 2 2 2h12c1.103 0 2-.897 2-2v-8c0-1.103-.897-2-2-2h-1V7c0-2.757-2.243-5-5-5zm6 10 .002 8H6v-8h12zm-9-2V7c0-1.654 1.346-3 3-3s3 1.346 3 3v3H9z"></path>
                </svg>
                <input type="password" name="password" id="password" placeholder="Password" autocomplete="off" required>
            </div>

            <div class="input-group">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24" fill="currentColor">
                    <path d="M21 13.2422V20H22V22H2V20H3V13.2422C1.79401 12.435 1 11.0602 1 9.5C1 8.67286 1.22443 7.87621 1.63322 7.19746L4.3453 2.5C4.52393 2.1906 4.85406 2 5.21132 2H18.7887C19.1459 2 19.4761 2.1906 19.6547 2.5L22.3575 7.18172C22.7756 7.87621 23 8.67286 23 9.5C23 11.0602 22.206 12.435 21 13.2422ZM19 13.9725C18.8358 13.9907 18.669 14 18.5 14C17.2409 14 16.0789 13.478 15.25 12.6132C14.4211 13.478 13.2591 14 12 14C10.7409 14 9.5789 13.478 8.75 12.6132C7.9211 13.478 6.75911 14 5.5 14C5.331 14 5.16417 13.9907 5 13.9725V20H19V13.9725ZM5.78865 4L3.35598 8.21321C3.12409 8.59843 3 9.0389 3 9.5C3 10.8807 4.11929 12 5.5 12C6.53096 12 7.44467 11.3703 7.82179 10.4295C8.1574 9.59223 9.3426 9.59223 9.67821 10.4295C10.0553 11.3703 10.969 12 12 12C13.031 12 13.9447 11.3703 14.3218 10.4295C14.6574 9.59223 15.8426 9.59223 16.1782 10.4295C16.5553 11.3703 17.469 12 18.5 12C19.8807 12 21 10.8807 21 9.5C21 9.0389 20.8759 8.59843 20.6347 8.19746L18.2113 4H5.78865Z"></path>
                </svg>

                <input type="text" name="id_outlet" id="id_outlet" placeholder="ID Outlet" autocomplete="off" required>
            </div>

            <button class="submit-btn" type="submit" form="form-login" name="submit" value="submit">REGISTER USER</button>
            <p class="bottomDisplayMessage"><?= $displayMessage ?></p>
        </form>
        <div class="goback-btn">
            <button onclick="history.back()">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" style="fill: rgba(255, 255, 255, 1);">
                    <path d="M21 11H6.414l5.293-5.293-1.414-1.414L2.586 12l7.707 7.707 1.414-1.414L6.414 13H21z"></path>
                </svg>
                Return
            </button>
        </div>
    </div>

</body>

</html>
