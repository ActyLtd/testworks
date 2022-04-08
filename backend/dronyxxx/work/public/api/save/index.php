<?php

/**
 * Save provided organizations array API endpoint
 */

include __DIR__ . "/../../../application/Load.php";
$system = new Load();
$system->setMysqlConnection();

$mysqli = $system->getDbConnection();

$response = [];
if (isset($_POST['data'])){
    $organizations_array = json_decode($_POST['data'],true);
    if (!empty($organizations_array)){
        // we clean the DB with previous inserts as it was not said in spec how to handle this
        $system->cleanDatabase();
        // now insert new organizations and relations
        $system->insertOrganizations(json_decode($_POST['data'],true));
    }else{
        $response['error'] = 'POST data is empty';
    }
}else{
    $response['error'] = 'POST data is empty';
}

// send back the JSON formatted response
echo json_encode($response);
