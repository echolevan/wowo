<?php
return array(

    /*
	|--------------------------------------------------------------------------
	| Default FTP Connection Name
	|--------------------------------------------------------------------------
	|
	| Here you may specify which of the FTP connections below you wish
	| to use as your default connection for all ftp work.
	|
	*/

    'default' => 'xmr',

    /*
    |--------------------------------------------------------------------------
    | FTP Connections
    |--------------------------------------------------------------------------
    |
    | Here are each of the FTP connections setup for your application.
    |
    */

    'connections' => array(

        'xmr' => array(
//            'host'   => '',
            'host'   => env('FTP_XMR_HOST'),
            'port'  => 21,
            'username' => env('FTP_XMR_USERNAME'),
            'password'   => env('FTP_XMR_PASSWORD'),
            'passive'   => false,
        ),
    ),
);