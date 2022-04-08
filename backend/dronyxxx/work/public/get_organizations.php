<?php

/**
 * This page allows customer to search for one organization siblings. Organization name must be specified as string
 * API endpoint URLs can be found in /application/config/config.php
 * Please find database structure in /application/db/diagram.mwb (open with Mysql Workbench)
 * All HTML views are to be stored to viewsDir path set in config.php file
 */

include __DIR__ . "/../application/Load.php";
$system = new Load();

if (!empty($_POST) && (!isset($_POST['organization_name']) || !$_POST['organization_name'])){
    // TODO: pass error message to html or throw Exception
    die('Organization name is empty');
}elseif (!empty($_POST) && isset($_POST['organization_name']) && $_POST['organization_name']){
    $page_to_show = 1;
    $limit = 100;
    if (isset($_POST['page'])) $page_to_show = $_POST['page'];
    if (isset($_POST['limit'])) $limit = $_POST['limit'];

    $response = $system->sendApiRequest($system->getConfig()['api']['get_organizations_url'], json_encode(['name' => $_POST['organization_name'], 'page' => $page_to_show, 'total' => $limit]));

    if (isset($response['error'])){
        // TODO: pass error message to html
        die($response['error']);
    }

    die(json_encode($response['siblings'])); // just to show the result
}
// TODO: pass $response['siblings'] formatted result into html
// TODO: set paging into html page
echo $system->getView('organizations');

