<?php

namespace App\routers;

class UserRouter extends \App\libraries\PHPExpress
{
    public function __construct()
    {
        $this->get('/users', 'UserController@index');
        $this->get('/users/create', 'UserController@create');
        $this->post('/users', 'UserController@store');
        $this->get('/users/{id}', 'UserController@show');
        $this->get('/users/{id}/edit', 'UserController@edit');
        $this->put('/users/{id}', 'UserController@update');
        $this->delete('/users/{id}', 'UserController@destroy');
        // fetch('/users/{id}', {
        //     method: 'PUT',
        //     body: JSON.stringify(data),
        //     headers: {
        //         'Content-Type': 'application/json'
        //     }
        // })
        // .then(response => response.json())
        // .then(data => console.log(data))
        // .catch(error => console.error(error));

        // $_PARAMS = $this->getRouteParams('/users/{id}');
        // $_PARAMS will be accessible to the .php files in the views folder as a global variable
        $data = [
            'name' => 'John Doe',
            'job' => 'Web Developer'
        ];
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'http://example.com/users/{id}');
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data, JSON_THROW_ON_ERROR));
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));

        $result = curl_exec($ch);

        if ($result === false) {
            echo 'Error: ' . curl_error($ch);
        }

        curl_close($ch);
    }
}
