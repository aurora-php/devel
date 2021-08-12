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
    public static function identify(string $class, array $roots): string|bool
    {
        $file = '';
        
        if (preg_match('/^[a-z0-9]+\\\(([a-z0-9]+)(\\\.+|))$/i', $class, $match)) {
            // new namespaced library
            $file = strtolower($match[2]) . '/libs/' . str_replace('\\', '/', $match[1]) . '.php';
        }

        foreach ($roots as $r) {
            if (file_exists($r . '/' . $file)) {
                $file = $r . '/' . $file;
                break;
            }
        }

        return (!$file ? false : $file);
    }
    
    public static function register(array $roots)
    {
        spl_autoload_register(function ($class) use ($roots) {
            if (($file = self::identify($class, $roots)) !== false) {
                require($file);
            }
        }, true, true);
    }
}
