<?php

namespace App\libraries;

include_once __DIR__ . "/../config/config.php";
include_once __DIR__ . "/../utils/routeTo.php";
include_once __DIR__ . "/../utils/includeFile.php";
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
                if(is_array($dataItem)) {
                    extract($dataItem);
                }
                else {
                    extract(compact('dataItem'));
                }
            }
        }
    }

    public function render(string $path, $data = [])
    {
        if (!empty($this->views['directory'])) {
            $extension = '.' . preg_replace('#(\.)$#', '', $this->views['extension']);
            $path = $this->views['directory'] . $path . $extension;
            $path = preg_replace('#(?<!:)(\\{1,}|\/{2,})+#', '/', $path);

            if (file_exists($path)) {
                $this->extractData($data);

                extract([
                    'PROJECT_ROOT' => PROJECT_ROOT,
                    'URLROOT' => URLROOT,
                    'base' => $this->views['directory'],
                    'routeTo' => 'routeTo',
                    'includeFile' => 'includeFile',
                    'fetch' => 'fetch'
                ]);

                include_once __DIR__ . "/../views/layouts/header.php";
                include_once $path;
            } else {
                throw new \Exception("Sorry, the file does not exist");
            }
        } else {
            throw new \Exception("Sorry, the directory is not set");
            // throw new \Exception("Sorry, the directory is not set");
        }
    }

    public function send(mixed $data)
    {
        if(is_array($data)) {
            var_dump($data);
            return;
        }
        echo $data;
    }

    public function redirect(string $route) {
        header_register_callback(function() use ($route) {
            $newRoute = PROJECT_ROOT . $route;
            header("Location: $newRoute");
        });
    }

    public function refreshPage(int $seconds = 0) {
        header_register_callback(function() use ($seconds) {
            header("Refresh: $seconds");
        });
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
