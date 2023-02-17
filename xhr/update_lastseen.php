<?php 
if ($f == 'update_lastseen') {
    if (Br_CheckMainSession($hash_id) === true) {
        if (Br_LastSeen($br['user']['user_id']) === true) {
            $data = array(
                'status' => 200
            );
        }
    }
    header("Content-type: application/json");
    echo json_encode($data);
    exit();
}
