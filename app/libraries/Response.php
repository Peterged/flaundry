<?php

namespace app\libraries;

include_once __DIR__ . "/../config/config.php";
include_once __DIR__ . "/../utils/routeTo.php";
include_once __DIR__ . "/../utils/fetch.php";

final class Response
{
    public $views;

    public function __construct()
    {
    }

    private function extractData($data)
    {
        if (isset($data)) {
            foreach ($data as $dataItem) {
                extract($dataItem);
            }
        }
    }

    public function render(string $path, $data = [])
    {
        if (!empty($this->views['directory'])) {
            $path = $this->views['directory'] . $path . $this->views['extension'];
            $path = preg_replace('#(?<!:)(\\{1,}|\/{2,})+#', '/', $path);
            
            if (file_exists($path) && !headers_sent()) {
                $this->extractData($data);
                extract([
                    'PROJECT_ROOT' => PROJECT_ROOT,
                    'URLROOT' => URLROOT,
                    'routeTo' => 'routeTo',
                    'fetch' => 'fetch'
                ]);
                require_once $path;
            } else {
                throw new \Exception("Sorry, the file does not exist");
            }
        } else {
            throw new \Exception("Sorry, the directory is not set");
        }
    }

    public function send(mixed $data)
    {
        var_dump($data);
    }

    public function sendFile(string $path)
    {
        $path = preg_replace('#(?<!:)(\\{1,}|\/{2,})+#', '/', $path);

        if (file_exists($path)) {
            require_once $path;
        } else {
            throw new \Exception("Sorry, the file does not exist");
        }
    }

    public function setHeader(string $header, string $value = null)
    {
        header($header . ": " . $value);
    }

    public function setCode(int $code) {
        http_response_code($code);
    }

    protected function sendOutput($data, $httpHeaders = array())
    {

        header_remove('Set-Cookie');

        if (is_array($httpHeaders) && count($httpHeaders)) {

            foreach ($httpHeaders as $httpHeader) {

                header($httpHeader);
            }
        }

        echo $data;

        exit;
    }
}
