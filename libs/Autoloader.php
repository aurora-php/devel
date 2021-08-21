<?php

declare(strict_types=1);

namespace Octris\Devel;

/**
 * Octris autoloader for development purpose.
 *
 * @copyright   copyright (c) 2021-present by Harald Lapp
 * @author      Harald Lapp <harald@octris.org>
 */
class Autoloader
{
    public static function identify(string $class, array $map): string|bool
    {
        $file = '';

        if (preg_match('/^[a-z0-9]+\\\(([a-z0-9]+)(\\\.+|))$/i', $class, $match)) {
            foreach ($map as $prefix => $path) {
                if (strpos($class, $prefix) === 0) {
                    if ($class === $prefix) {
                        $f = $path . '/libs/' . str_replace('\\', '/', $match[1]) . '.php';
                    } else {
                        switch (substr_count($prefix, '\\')) {
                            case 1:
                                $f = $path . '/' . strtolower($match[2]) . '/libs/' . str_replace('\\', '/', $match[1]) . '.php';
                                break;
                            case 2:
                            default:
                                $f = $path . '/libs/' . str_replace('\\', '/', $match[1]) . '.php';
                                break;
                        }
                    }

                    if (file_exists($f)) {
                        $file = $f;
                        break;
                    }
                }
            }
        }

        return (!$file ? false : $file);
    }

    public static function register(array $map): void
    {
        krsort($map);

        spl_autoload_register(function ($class) use ($map) {
            if (($file = self::identify($class, $map)) !== false) {
                require($file);
            }
        }, true, true);
    }
}
