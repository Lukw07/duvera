<?php

// autoload_real.php @generated by Composer

class ComposerAutoloaderInitca0baa0e93a3f2e22632e7b687163040
{
    private static $loader;

    public static function loadClassLoader($class)
    {
        if ('Composer\Autoload\ClassLoader' === $class) {
            require __DIR__ . '/ClassLoader.php';
        }
    }

    /**
     * @return \Composer\Autoload\ClassLoader
     */
    public static function getLoader()
    {
        if (null !== self::$loader) {
            return self::$loader;
        }

        require __DIR__ . '/platform_check.php';

        spl_autoload_register(array('ComposerAutoloaderInitca0baa0e93a3f2e22632e7b687163040', 'loadClassLoader'), true, true);
        self::$loader = $loader = new \Composer\Autoload\ClassLoader(\dirname(__DIR__));
        spl_autoload_unregister(array('ComposerAutoloaderInitca0baa0e93a3f2e22632e7b687163040', 'loadClassLoader'));

        require __DIR__ . '/autoload_static.php';
        call_user_func(\Composer\Autoload\ComposerStaticInitca0baa0e93a3f2e22632e7b687163040::getInitializer($loader));

        $loader->register(true);

        return $loader;
    }
}
