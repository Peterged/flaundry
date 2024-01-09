<?php 
    function streamFetch(string $route, array $options = [])
    {
        $method = $options['method'] ?? 'GET';
        $body = $options['body'] ?? null;
        $headers = $options['headers'] ?? "Content-Type: application/json";
    
        $context = stream_context_create(array(
            'http' => array(
                'method' => $method,
                'header' => $headers,
                'content' => $body
            )
        ));
        
        $response = file_get_contents($route, false, $context);
        $users = json_decode($response, true);
        return $users;
    }

    function simpleFetch(string $route) {
        $response = file_get_contents($route);
        $users = json_decode($response, true);
        return $users;
    }

    function fetch(string $route, array $data = null) {
        $method = $data['method'] ?? 'GET';
        $body = $data['body'] ?? null;
        $headers = $data['headers'] ?? "Content-Type: application/json";
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $route);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $method);
        curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($body, JSON_THROW_ON_ERROR));
        curl_setopt($curl, CURLOPT_HTTPHEADER, array($headers));
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($curl);
        curl_close($curl);
        $users = json_decode($response, true);
        return $users;
    }
?>