<?php

/**
 * This page allows customer to POST data with predefined organizations siblings
 * The result will be stored to database via API endpoint.
 * API endpoint URLs can be found in /application/config/config.php
 * Please find database structure in /application/db/diagram.mwb (open with Mysql Workbench)
 * All HTML views are to be stored to viewsDir path set in config.php file
 */

include __DIR__ . "/../application/Load.php";
$system = new Load();

if (isset($_POST['json'])){
    $organizations_array = json_decode($_POST['json'],true);
    if (!$organizations_array || empty($organizations_array)){
        // TODO: pass error message to html or throw Exception
        die('Json in not valid. Please try again');
    }
    $response = $system->sendApiRequest($system->getConfig()['api']['save_data_url'], $_POST['json']);
    if ($response){
        die($response['error']);
    }

    // TODO: pass success message to getView function
    die('Success');
}

// load html view
echo $system->getView('index');

