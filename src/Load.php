<?php


class Load
{
    public static $registrated = [];
    public static $required = [];

    public static function register(string $name, string $dir)
    {
        self::$registrated[$name] = rtrim($dir, '/') . '/';
    }

    public static function __callStatic($name, $args)
    {
        if (! $dir = self::$registrated[$name] ?? false) {
            return null;
        }

        if (count($args) > 1) {
            return self::concat(...array_map(function ($file) { return $dir . $file . '.php'; }, $args));
        }

        if (! in_array($file = $dir . $args[0] . '.php', self::$required)) {
            self::$required[] = $file;
            return require $file;
        }

        return true;
    }

    public static function concat(...$files)
    {

        foreach ($files as $file) {
            if (! in_array($file, self::$required)) {
                self::$required[] = $file;
                require $file;
            }
        }

        return true;
    }
}