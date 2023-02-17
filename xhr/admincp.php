<?php 
if ($f == 'admincp') {
    // if ($s == 'insert-invitation') {
    //     $data             = array(
    //         'status' => 200,
    //         'html' => ''
    //     );
    //     $br['invitation'] = Br_InsertAdminInvitation();
    //     if ($br['invitation'] && is_array($br['invitation'])) {
    //         $data['html']   = Br_LoadAdminPage('manage-invitation-keys/list');
    //         $data['status'] = 200;
    //     }
    //     header("Content-type: application/json");
    //     echo json_encode($data);
    //     exit();
    // }
    // if ($s == 'rm-invitation' && isset($_GET['id']) && is_numeric($_GET['id'])) {
    //     $data = array(
    //         'status' => 304
    //     );
    //     if (Br_DeleteAdminInvitation('id', $_GET['id'])) {
    //         $data['status'] = 200;
    //     }
    //     header("Content-type: application/json");
    //     echo json_encode($data);
    //     exit();
    // }
    if ($s == 'update-sitemap') {
        $rate = (isset($_POST['rate']) && strlen($_POST['rate']) > 0) ? $_POST['rate'] : false;
        $data = array(
            'status' => 304
        );
        if (Br_GenirateSiteMap($rate)) {
            $data['status'] = 200;
        }
        header("Content-type: application/json");
        echo json_encode($data);
        exit();
    }
    // if ($s == 'rm-user-invitation' && isset($_GET['id']) && is_numeric($_GET['id'])) {
    //     $data = array(
    //         'status' => 304
    //     );
    //     if (Br_DeleteUserInvitation('id', $_GET['id'])) {
    //         $data['status'] = 200;
    //     }
    //     header("Content-type: application/json");
    //     echo json_encode($data);
    //     exit();
    // }
}
