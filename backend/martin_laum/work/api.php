<?php
header("Content-Type:application/json");
require('db.php');
// Check if the incoming request is a GET.
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Check if request contains org name.
    if (isset($_GET['org_name']) && $_GET['org_name']!="") {
    	$org_name = $_GET['org_name'];
        // Retrieve all organisations and relations by the organisation name provided in the URL.
        $query = "SELECT organizations.org_name as org_name, 'parent' as relationship_type FROM organization_info
        JOIN organizations ON organization_info.org_relation_id = organizations.id
        WHERE organization_info.org_id IN (SELECT id FROM organizations WHERE org_name = '$org_name')
        UNION
        SELECT organizations.org_name as org_name, 'daughter' as relationship_type FROM organization_info
        JOIN organizations ON organization_info.org_id = organizations.id
        WHERE organization_info.org_relation_id IN (SELECT id FROM organizations WHERE org_name = '$org_name')
        UNION
        SELECT organizations.org_name as org_name, 'sister' as relationship_type FROM organization_info AS or1
        JOIN organization_info AS or2 ON or1.org_relation_id = or2.org_relation_id
        JOIN organizations ON or2.org_id = organizations.id
        WHERE or1.org_id IN (SELECT id FROM organizations WHERE org_name = '$org_name') AND organizations.org_name <> '$org_name'
        ORDER BY org_name ASC
        LIMIT 10";
        //  Currently offset works like a page number. If you pass offset 10 it will find record 90 to 100, which means limit 10 offset 90.
        if (isset($_GET['offset']) && $_GET['offset'] !== "") {
            $offset = (int)$_GET['offset'];
            $offset = ($offset - 1) * 10;

            $query .= " OFFSET " . $offset;
        }
        // Retrieve the results of the above SQL query
        $result = mysqli_query($con, $query);
    	if(mysqli_num_rows($result)>0){
            while($row = mysqli_fetch_assoc($result)) {
                $organizations[] = $row;
            }
            echo json_encode($organizations);

    	mysqli_close($con);
    	}else{
            // In case no records are found in DB return this.
            $response['response'] = "No Record Found!";
            $json_response = json_encode($response);
            echo $json_response;
    		}
        }
        function response($row){
            if (!empty ($row)){
                $response['org_name'] = $row['org_name'];
                $response['relationship_type'] = $row['relationship_type'];

                $json_response = json_encode($response);
    	        echo $json_response;
            }
    	    else{
                // Misc. error return.
                echo json_encode('How did you end up here?');

        }
    }
}
// Check if incoming request is a POST.
else if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'));
    if (isset($data) && $data!="") {
        $org = $data;
        org_insert($org, 0);
    }
}
// Insert new organisations from JSON post request with proper relations.
function org_insert($org, $relation_id) {
    $con = mysqli_connect("localhost","root","","api_db");
    if (is_string($org)){
        $org = json_decode($org);
    }
    $exist = 'SELECT org.* FROM organizations as org WHERE org_name="'.$org->org_name.'" order by id desc LIMIT 1';
    $result = mysqli_query($con, $exist);
    $row=mysqli_fetch_array($result);
    if(!empty($row['id'])){
        $new_relation_id=$row['id'];
    }
    else{
        // Check if any relations are present in the JSON and insert those.
        $query = "INSERT INTO organizations (org_name) VALUES ('$org->org_name') ON DUPLICATE KEY UPDATE org_name = VALUES(org_name)"; 
        $result = mysqli_query($con, $query);
        if (mysqli_affected_rows($con)){
            $new_relation_id = mysqli_insert_id($con);
        }
    }
    // Insert relation(s) if any exist.
    if ($new_relation_id && $relation_id > 0) {
        $relation_query = "INSERT INTO organization_info (org_id, org_relation_id) VALUES ($new_relation_id, $relation_id) ON DUPLICATE KEY UPDATE org_id = VALUES(org_id)";
        $relation_result = mysqli_query($con, $relation_query);
    }
    if ($new_relation_id && isset($org->daughters) && count($org->daughters)>0) {
        foreach ($org->daughters as $daughter)
        {
            if (!is_null($daughter) && !empty($daughter))
            {
                org_insert($daughter, $new_relation_id);
            }
        }
    }
}
?>
