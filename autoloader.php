<?php

namespace OWC\OpenPub\Base;

class Autoloader
{

    /**
     * Autoloader constructor.
     * PSR autoloader
     */
    public function __construct()
    {
        spl_autoload_register(function ($className) {
            $baseDir = __DIR__.'/src/';
            $namespace = str_replace("\\", "/", __NAMESPACE__);
            $className = str_replace("\\", "/", $className);
            $class = $baseDir.(empty($namespace) ? "" : $namespace."/").$className.'.php';
            $class = str_replace('/OWC/OpenPub/Base/OWC/OpenPub/Base/', '/Base/', $class);
            if (file_exists($class)) {
                require_once($class);
            }
        });
    }
}
