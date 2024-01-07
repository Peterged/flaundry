<?php

$data = [
    '_METHOD' => 'PUT',
    '_PARAMS' => [
        'id' => 1
    ],
    '_BODY' => [
        'name' => 'John Doe',
        'job' => 'Web Developer'
    ]
];

extract($data);

include 'profile.php';
