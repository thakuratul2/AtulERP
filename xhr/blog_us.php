<?php

if($f == 'blog_us'){
   
    // foreach ($_POST as $key => $value) {
    //     echo "<tr>";
    //     echo "<td>";
    //     echo $key;
    //     echo "</td>";
    //     echo "<td>";
    //     echo $value;
    //     echo "</td>";
    //     echo "</tr>";
        
    // }
    $fname = $_POST['firstname'];
    $lname = $_POST['lastname'];
    $email = $_POST['email'];
    $message = $_POST['message'];
    $details = $_POST['details'];
    $query_insert = "INSERT INTO `blog`(`firstname`, `lastname`, `email`, `message`, `details`) VALUES ('$fname','$lname','$email','$message','$details') ";
    print_r($query_insert);
    $data_show = mysqli_query($sqlConnect,$query_insert);


    header("Content-type: application/json");
    if (!empty($errors)) {
        echo json_encode(array(
            'errors' => $errors
        ));
    } else if (!empty($data_)) {
        echo json_encode($data_);
    } else {
        echo json_encode($data);
    }
    exit();
   
}


?>