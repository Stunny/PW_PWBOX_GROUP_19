<?php
/**
 * Created by PhpStorm.
 * User: avoge
 * Date: 05/04/2018
 * Time: 10:53
 */

return [
        'settings' => [
            'determineRouteBeforeAppMiddleware' => true,
            'displayErrorDetails' => true,
            'addContentLengthHeader' => false,
            'database' => [
                'dbname' => 'PWBOX',
                'user' => 'homestead',
                'password' => 'secret',
                'host' => 'localhost',
                'driver' => 'pdo_mysql'
            ]
        ]
];