<?php

/**
 * Search for organization siblings API endpoint
 * It is required to get organization name in POST request
 */

include __DIR__ . "/../../../application/Load.php";
$system = new Load();
$system->setMysqlConnection();

$mysqli = $system->getDbConnection();

$response = [];
if (isset($_POST['data'])){
    $data_array = json_decode($_POST['data'],true);
    if (isset($data_array['name'])){
        // TODO: try to catch exception inside the function if name does not exist in DB or any other error
        // get all organization siblings in array
        $siblings_array = $system->getOrganizationSiblings($data_array['name']);

        // sort organizations by name
        usort($siblings_array, function($a, $b) {
            return $a['org_name'] <=> $b['org_name'];
        });

        // apply paging to $siblings_array and return first 100 results by default
        $total_siblings = count($siblings_array);
        $page = !empty($data_array['page']) ? (int) $data_array['page'] : 1;
        $limit = !empty($data_array['limit']) ? (int) $data_array['limit'] : 100;
        $total_pages = ceil( $total_siblings / $limit);
        $page = max($page, 1);
        $page = min($page, $total_pages);
        $offset = ($page - 1) * $limit;
        if ($offset < 0 ) $offset = 0;

        $response['siblings'] = array_slice($siblings_array, $offset, $limit);
    }else{
        $response['error'] = 'POST data is empty';
    }
}else{
    $response['error'] = 'POST data is empty';
}

echo json_encode($response);
