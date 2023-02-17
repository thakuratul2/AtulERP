<?php 
if ($f == 'request_verification') {
    if (!empty($_GET['id']) && !empty($_GET['type'])) {
        if (Br_RequestVerification($_GET['id'], $_GET['type']) === true) {
            $data = array(
                'status' => 200,
                'html' => Br_GetVerificationButton($_GET['id'], $_GET['type'])
            );
        }
    }
    header("Content-type: application/json");
    echo json_encode($data);
    exit();
}
