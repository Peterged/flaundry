<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Session Control Panel</title>
</head>

<body>
    <style>
        .session-container * {
            font-family: monospace;
        }

        .session-container {
            background-color: #fff;
            min-height: 100vh;
            height: auto;

            width: 100%;
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            display: none;
            align-items: center;
            justify-content: center;
            flex-direction: column;
        }

        .session-container.active {
            display: flex;
        }

        .session-container .session-wrapper {
            width: 100%;
            height: 100%;
            overflow: auto;
            padding: 2rem;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
        }

        .session-container table {
            width: 100%;
            border-collapse: collapse;
        }

        .session-container table tr,
        .session-container table th,
        .session-container table td {
            padding: 0.5rem;
            border: 1px solid rgba(0, 0, 0, 0.25);
            transition: 150ms ease-in-out;
        }

        .session-container table th {
            background-color: #f0f0f0;
        }

        /* .session-container table tr:hover {
    background-color: #f0f0f0;
} */

        .session-container table td a {
            padding: 0.5rem 1rem;
            background-color: #f0f0f0;
            color: white;
            border-radius: 0.5rem;
        }





        .session-container .session-list tr td input[data-input="form"] {
            outline: none;
            border: 1px solid transparent;
            border-radius: 1px;
            width: 100%;
        }

        .session-container .session-list tr td input[data-input="form"]:focus,
        .session-container .session-list tr td input[data-input="form"]:hover {
            border: 1px solid #f0f0f0;
        }

        /* Toggle Button */
        .toggleButton {
            position: fixed;
            top: 1rem;
            right: 1rem;
            z-index: 10005;
        }

        .toggleButton button {
            padding: 0.5rem 1rem;
            background-color: #000;
            color: white;
            border-radius: 0.5rem;
            border: none;
            cursor: pointer;
        }

        .command-buttons {
            display: flex;
            width: 100%;
            position: fixed;
            bottom: 1rem;
            justify-content: center;
            align-items: center;

            z-index: 10000;
        }

        .command-buttons .button-content {
            /* background-color: black; */
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 1rem;
        }

        .command-buttons .button-content a {
            padding: 0.5rem 1rem;
            font-size: 0.7rem;
            color: #121212;
            background-color: transparent;
            border: 1px solid #121212;
            border-radius: 1000px;
            cursor: pointer;
            transition: 150ms ease-in-out;
        }

        .command-buttons .button-content a:hover {
            background-color: #121212;
            color: white;
        }
    </style>
    <div class="command-buttons">
        <div class="button-content">
            <a href="<?= routeTo('/') ?>">HOME</a>
            <a href="<?= routeTo('/debug/session/destroy') ?>">Destroy Session</a>
        </div>
    </div>
    <div id="session-toggle-button" class="toggleButton">
        <button type="button" class="button">TOGGLE SESSION</button>
    </div>
    <div class="session-container active">
        <div class="session-wrapper">
            <table class="session-content">
                <tr>
                    <th colspan="2">Session Content</th>
                </tr>
                <tr>
                    <td>Session ID</td>
                    <td><?php echo '<i>hidden</i>' ?? session_id(); ?></td>
                </tr>
                <tr>
                    <td>Session Name</td>
                    <td><?php echo session_name(); ?></td>
                </tr>
                <tr>
                    <td>Session Status</td>
                    <td><?php echo session_status(); ?></td>
                </tr>
                <tr>
                    <td>Session Save Path</td>
                    <td><?php echo session_save_path(); ?></td>
                </tr>
                <tr>
                    <td>Session Cookie Params</td>
                    <td><?php print_r(session_get_cookie_params()); ?></td>
                </tr>
                <tr>
                    <td>Session Data</td>
                    <td><?php echo "<pre>";
                        print_r($_SESSION);
                        echo "</pre>"; ?></td>
                </tr>
            </table>
            <table class="session-list">
                <tr>
                    <th>Session Name</th>
                    <th>Value</th>
                    <!-- <th colspan="2">Actions</th> -->
                </tr>
                <?php
                if (count($_SESSION)) {
                    $no = 1;
                    foreach ($_SESSION as $key => $value) {
                        echo "<tr data-form-id='$no'>";

                        echo "<td><input data-prev-key='$key' data-input='form' data-id='$no' id='key' name='session_key' placeholder='Key' value='$key'></td>";
                        if (is_array($value)) {
                            $value = json_encode($value);
                        }
                        echo "<td><input data-prev-value='$value' data-input='form' data-id='$no' id='value' name='session_value' placeholder='Value' value='$value'></td>";
                        $no++;
                    }
                }
                ?>
                <tr data-form-id="<?= count($_SESSION) || 1 ?>">
                    <?php
                    $no = count($_SESSION) || 1;
                    echo "<td><input data-input='form' data-id='$no' id='key' name='session_key' placeholder='Type a new Key' value=''></td>";
                    echo "<td><input data-input='form' data-id='$no' id='value' name='session_value' placeholder='Type a value' value=''></td>";
                    ?>
                </tr>
            </table>
        </div>
    </div>
    <script>
        const toggleButton = document.querySelector('#session-toggle-button button');
        const sessionContainer = document.querySelector('.session-container');
        toggleButton.addEventListener('click', (e) => {
            if (sessionContainer.classList.contains('active') && e.target != sessionContainer.querySelector('.session-wrapper')) {
                sessionContainer.classList.remove('active');
            } else if (e.target == toggleButton) {
                sessionContainer.classList.add('active');
            }
        })

        function isJsonString(str) {
            try {
                JSON.parse(str);
            } catch (e) {
                return false;
            }
            return true;
        }

        const form = document.querySelectorAll('.session-container .session-wrapper tr[data-form-id]');
        form.forEach(input => {
            let formId = input.getAttribute('data-form-id');
            let formItems = input.querySelectorAll('input[data-input="form"][data-id="' + formId + '"]');
            formItems.forEach(item => {
                let timeOut, key, value;

                item.addEventListener('change', function() {
                    try {
                        if (timeOut) {
                            clearTimeout(timeOut);
                        }
                        timeOut = setTimeout(() => {
                            // Create an object to hold the form data
                            let formData = {};

                            // For each input in the row, add its value to the formData object
                            formItems.forEach(item => {
                                if(!isJsonString(item.value)) {
                                    item.value = item.value.replace(/(['\'])+/, '"');
                                    item.value = item.value.replace(/([\"])+/, "\$1");
                                }

                                

                                if (item.getAttribute('id') == 'key' && item.getAttribute('data-prev-key') != item.value) {
                                    formData['old_key'] = item.getAttribute('data-prev-key') || '';
                                }

                                formData['old_value'] = item.getAttribute('data-prev-value') || '';
                                formData[item.getAttribute('id')] = item.value;
                            });

                            console.log(formData);

                            fetch('http://localhost/flaundry/api/session', {
                                    method: 'POST',
                                    headers: {
                                        'Content-Type': 'application/json'
                                    },
                                    body: JSON.stringify(formData)
                                })
                                .then(response => response.json())
                                .then(data => {
                                    window.location.reload();
                                    // console.log(formData);
                                })
                                .catch(error => console.error(error));
                        }, 10);
                    } catch (error) {}
                });
            })

        });
    </script>
</body>

</html>