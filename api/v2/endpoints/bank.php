<?php

if (empty($_FILES["thumbnail"])) {
	$error_code    = 3;
    $error_message = 'Please check your details';
}
if (empty($error_code)) {
    $description = !empty($_POST['description']) ? Br_Secure($_POST['description']) : '';
    
    $fileInfo      = array(
        'file' => $_FILES["thumbnail"]["tmp_name"],
        'name' => $_FILES['thumbnail']['name'],
        'size' => $_FILES["thumbnail"]["size"],
        'type' => $_FILES["thumbnail"]["type"],
        'types' => 'jpeg,jpg,png,bmp,gif'
    );
    $media         = Br_ShareFile($fileInfo);
    $mediaFilename = $media['filename'];
    if (!empty($mediaFilename)) {

    	if (!empty($_POST['payment_type']) && $_POST['payment_type'] == 'wallet') {
    		if (empty($_POST['price']) || !is_numeric($_POST['price']) || $_POST['price'] < 1) {
	            $error_code    = 5;
		        $error_message = 'price can not be empty';
	        }
	        else{
	        	$insert_id = Br_InsertBankTrnsfer(array('user_id' => $br['user']['id'],
                                                       'description' => $description,
                                                       'price'       => Br_Secure($_POST['price']),
                                                       'receipt_file' => $mediaFilename,
                                                       'mode'         => 'wallet'));
	        }
    	}
    	else{
    		if (empty($_POST['type']) || !in_array($_POST['type'], array_keys($br['pro_packages_types']))) {
    			$error_code    = 6;
		        $error_message = 'type can not be empty';
    		}
    		else{
    			$pro = $br['pro_packages'][$br['pro_packages_types'][$_POST['type']]];
    			$insert_id = Br_InsertBankTrnsfer(array('user_id' => $br['user']['id'],
		                                               'description' => $description,
		                                               'price'       => $pro['price'],
		                                               'receipt_file' => $mediaFilename,
		                                               'mode'         => Br_Secure($_POST['type'])));
    		}
    	}
        
        if (!empty($insert_id)) {
        	
        	$response_data = array(
			    'api_status' => 200,
			    'message' => "Your request has been successfully sent, we will notify you once it`s approved"
			);
        }
    }
    else{
    	$error_code    = 4;
        $error_message = 'File not supported';
    }
}