<?php
//error_reporting( E_ALL );
error_reporting( E_ERROR );

define( 'APP_PATH', realpath( __DIR__ . '/../application' ) );

// load requirements:
function __autoload( $className )
{
    $dirs = array(
        realpath( __DIR__ . '/classes' ),
        realpath( APP_PATH . '/libraries/' )
    );

    foreach ($dirs as $d_no => $dir) {
        $path = $dir . '/' . $className . '.php';
        
        if (file_exists( $path )) {
            require_once $path;
        }
    }
}

function config_item( $config_file )
{
    $file_path = APP_PATH . '/config/' . $config_file . '.php';

    if ( ! file_exists( $file_path )) {
        exit( 'Api Error: failed to load [' . $config_file . ']' );
    }

    require_once $file_path;

    if ( ! isset( $config )) {
        exit( 'Api Error: File ' . $config_file . ' does not contain a \'$config\'' );
    }

    return $config[$config_file];
}

Subscriptions_Lib::loadConfig();

// initiate:
$api = new API;
$api->processApi();
