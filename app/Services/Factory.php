<?php
namespace App\Services;

class Factory
{
    public static function factory($provider)
    {
        $file = __DIR__ . '/' . $provider . '.php';

        if (!file_exists($file)) {
            throw new \InvalidArgumentException('Invalid gateway specified.');
        }

        $class = 'App\\Payment\\' . $provider;
        include_once ($file);
        return new $class();
    }
}
