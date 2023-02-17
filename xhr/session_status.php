<?php 
if ($f == 'session_status') {
    if ($br['loggedin'] == false) {
        $data = array(
            'status' => 200
        );
    }
    header("Content-type: application/json");
    echo json_encode($data);
    exit();
}
