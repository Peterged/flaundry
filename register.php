<?php

$dataUser = [
    'dataUsers' => 'blabla',
    '_METHOD' => 'PUT',
    '_PARAMS' => [
        'id' => 'KreshnaDhana09',
        'nama' => 'Robert Dananjaya'
    ],
    '_BODY' => [
        'name' => 'John Doe',
        'job' => 'Web Developer'
    ]
];

extract($dataUser);

include 'profile.php';
