<?php

/**
 * Main application config file.
 * Please create config.php file from this example otherwise application will not work
 */

ini_set("display_errors", "On");
error_reporting(E_ALL);
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

$mainConfig = [
    "database" => [
        "host"        => "",
        "username"    => "",
        "password"    => "",
        "dbname"      => ""
    ],
    "viewsDir"     => __DIR__ ."/../views/",
    'api' => [
        'save_data_url' => '', // please provide https protocol for live version. Ex. https://api.example.com/save/ or https://example.com/api/save/,
        'get_organizations_url' => '' // please provide https protocol for live version. Ex. https://api.example.com/organizations/ or https://example.com/api/organizations/
    ]
];
define("CONFIG", $mainConfig);
return $mainConfig;