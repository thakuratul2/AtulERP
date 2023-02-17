<?php 
if ($f == 'send_mails') {
    if ($br['config']['emailNotification'] == 0) {
        $data = array(
            'status' => 200
        );
        header("Content-type: application/json");
        echo json_encode($data);
        exit();
    }
    $send = Br_SendMessageFromDB();
    if ($send) {
        $data = array(
            'status' => 200
        );
    }
    header("Content-type: application/json");
    echo json_encode($data);
    exit();
}
