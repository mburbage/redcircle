<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit388d7f9e770c663a2bda0db283162ab7
{
    public static $files = array (
        '320cde22f66dd4f5d3fd621d3e88b98f' => __DIR__ . '/..' . '/symfony/polyfill-ctype/bootstrap.php',
        '0e6d7bf4a5811bfa5cf40c5ccd6fae6a' => __DIR__ . '/..' . '/symfony/polyfill-mbstring/bootstrap.php',
    );

    public static $prefixLengthsPsr4 = array (
        's' => 
        array (
            'steevanb\\PhpYaml\\' => 17,
        ),
        'T' => 
        array (
            'Twig\\' => 5,
        ),
        'S' => 
        array (
            'Symfony\\Polyfill\\Mbstring\\' => 26,
            'Symfony\\Polyfill\\Ctype\\' => 23,
            'Symfony\\Component\\Yaml\\' => 23,
        ),
        'L' => 
        array (
            'Leafo\\ScssPhp\\' => 14,
        ),
        'K' => 
        array (
            'Konfirmi\\' => 9,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'steevanb\\PhpYaml\\' => 
        array (
            0 => __DIR__ . '/..' . '/steevanb/php-yaml',
        ),
        'Twig\\' => 
        array (
            0 => __DIR__ . '/..' . '/twig/twig/src',
        ),
        'Symfony\\Polyfill\\Mbstring\\' => 
        array (
            0 => __DIR__ . '/..' . '/symfony/polyfill-mbstring',
        ),
        'Symfony\\Polyfill\\Ctype\\' => 
        array (
            0 => __DIR__ . '/..' . '/symfony/polyfill-ctype',
        ),
        'Symfony\\Component\\Yaml\\' => 
        array (
            0 => __DIR__ . '/..' . '/symfony/yaml',
        ),
        'Leafo\\ScssPhp\\' => 
        array (
            0 => __DIR__ . '/..' . '/leafo/scssphp/src',
        ),
        'Konfirmi\\' => 
        array (
            0 => __DIR__ . '/../..' . '/app',
        ),
    );

    public static $prefixesPsr0 = array (
        'T' => 
        array (
            'Twig_' => 
            array (
                0 => __DIR__ . '/..' . '/twig/twig/lib',
            ),
        ),
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit388d7f9e770c663a2bda0db283162ab7::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit388d7f9e770c663a2bda0db283162ab7::$prefixDirsPsr4;
            $loader->prefixesPsr0 = ComposerStaticInit388d7f9e770c663a2bda0db283162ab7::$prefixesPsr0;

        }, null, ClassLoader::class);
    }
}
