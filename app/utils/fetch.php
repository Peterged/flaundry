<?php 
    function fetch(string $route, array $options = [])
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
?>