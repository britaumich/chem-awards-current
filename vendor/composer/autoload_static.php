<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit803ff46400f9af384475a37263e839c0
{
    public static $files = array (
        '5255c38a0faeba867671b61dfda6d864' => __DIR__ . '/..' . '/paragonie/random_compat/lib/random.php',
        'decc78cc4436b1292c6c0d151b19445c' => __DIR__ . '/..' . '/phpseclib/phpseclib/phpseclib/bootstrap.php',
    );

    public static $prefixLengthsPsr4 = array (
        'p' => 
        array (
            'phpseclib\\' => 10,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'phpseclib\\' => 
        array (
            0 => __DIR__ . '/..' . '/phpseclib/phpseclib/phpseclib',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
        'Jumbojett\\OpenIDConnectClient' => __DIR__ . '/..' . '/jumbojett/openid-connect-php/src/OpenIDConnectClient.php',
        'Jumbojett\\OpenIDConnectClientException' => __DIR__ . '/..' . '/jumbojett/openid-connect-php/src/OpenIDConnectClient.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit803ff46400f9af384475a37263e839c0::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit803ff46400f9af384475a37263e839c0::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit803ff46400f9af384475a37263e839c0::$classMap;

        }, null, ClassLoader::class);
    }
}