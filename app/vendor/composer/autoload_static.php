<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInitef4979532b25573e19a699ead027e0e2
{
    public static $prefixLengthsPsr4 = array (
        'S' => 
        array (
            'Services\\' => 9,
        ),
        'R' => 
        array (
            'Repositories\\' => 13,
        ),
        'M' => 
        array (
            'Models\\' => 7,
        ),
        'F' => 
        array (
            'Firebase\\JWT\\' => 13,
        ),
        'E' => 
        array (
            'Exceptions\\' => 11,
        ),
        'C' => 
        array (
            'Controllers\\' => 12,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Services\\' => 
        array (
            0 => __DIR__ . '/../..' . '/services',
        ),
        'Repositories\\' => 
        array (
            0 => __DIR__ . '/../..' . '/repositories',
        ),
        'Models\\' => 
        array (
            0 => __DIR__ . '/../..' . '/models',
        ),
        'Firebase\\JWT\\' => 
        array (
            0 => __DIR__ . '/..' . '/firebase/php-jwt/src',
        ),
        'Exceptions\\' => 
        array (
            0 => __DIR__ . '/../..' . '/models/exceptions',
        ),
        'Controllers\\' => 
        array (
            0 => __DIR__ . '/../..' . '/controllers',
        ),
    );

    public static $prefixesPsr0 = array (
        'B' => 
        array (
            'Bramus' => 
            array (
                0 => __DIR__ . '/..' . '/bramus/router/src',
            ),
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInitef4979532b25573e19a699ead027e0e2::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInitef4979532b25573e19a699ead027e0e2::$prefixDirsPsr4;
            $loader->prefixesPsr0 = ComposerStaticInitef4979532b25573e19a699ead027e0e2::$prefixesPsr0;
            $loader->classMap = ComposerStaticInitef4979532b25573e19a699ead027e0e2::$classMap;

        }, null, ClassLoader::class);
    }
}