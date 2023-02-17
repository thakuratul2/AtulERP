<?php
$response_data = array(
    'api_status' => 400
);

$required_fields =  array(
                        'paypal',
                        'bank'
                    );
if (!empty($_POST['type']) && in_array($_POST['type'], $required_fields)) {
    if ($_POST['type'] == 'paypal') {
        if (empty($_POST['paypal_email'])) {
            $error_code    = 5;
            $error_message = 'paypal_email can not be empty';
        }
        elseif (!filter_var($_POST['paypal_email'], FILTER_VALIDATE_EMAIL)) {
            $error_code    = 6;
            $error_message = 'invalid email';
        }
        elseif (empty($_POST['amount']) || !is_numeric($_POST['amount'])) {
            $error_code    = 7;
            $error_message = 'amount can not be empty';
        }
        elseif (Br_IsUserPaymentRequested($br['user']['user_id']) === true) {
            $error_code    = 8;
            $error_message = 'you have pending request';
        } 
        elseif (($br['user']['balance'] < $_POST['amount'])) {
            $error_code    = 9;
            $error_message = $br['lang']['invalid_amount_value_your'] . ''.Br_GetCurrency($br['config']['ads_currency']) . $br['user']['balance'];
        } 
        elseif ($br['config']['m_withdrawal'] > $_POST['amount']) {
            $error_code    = 10;
            $error_message = $br['lang']['invalid_amount_value_withdrawal'] . ' '.Br_GetCurrency($br['config']['ads_currency']) . $br['config']['m_withdrawal'];
        }
        else{
            $userU  = Br_UpdateUserData($br['user']['user_id'], array(
                        'paypal_email' => $_POST['paypal_email']
                    ));
            $insert_payment = Br_RequestNewPayment($br['user']['user_id'], $_POST['amount'],$insert_array);
            if ($insert_payment) {
                $update_balance = Br_UpdateBalance($br['user']['user_id'], $_POST['amount'], '-');
                $response_data['message'] = $br['lang']['you_request_sent'];
                $response_data['api_status'] = 200;
            }
            else{
                $error_code    = 11;
                $error_message = 'something went wrong';
            }
        }
    }
    if ($_POST['type'] == 'bank') {
        if (empty($_POST['iban']) || empty($_POST['country']) || empty($_POST['full_name']) || empty($_POST['swift_code']) || empty($_POST['address'])) {
            $error_code    = 5;
            $error_message = 'please check details';
        }
        elseif (empty($_POST['amount']) || !is_numeric($_POST['amount'])) {
            $error_code    = 7;
            $error_message = 'amount can not be empty';
        }
        elseif (Br_IsUserPaymentRequested($br['user']['user_id']) === true) {
            $error_code    = 8;
            $error_message = 'you have pending request';
        } 
        elseif (($br['user']['balance'] < $_POST['amount'])) {
            $error_code    = 9;
            $error_message = $br['lang']['invalid_amount_value_your'] . ''.Br_GetCurrency($br['config']['ads_currency']) . $br['user']['balance'];
        } 
        elseif ($br['config']['m_withdrawal'] > $_POST['amount']) {
            $error_code    = 10;
            $error_message = $br['lang']['invalid_amount_value_withdrawal'] . ' '.Br_GetCurrency($br['config']['ads_currency']) . $br['config']['m_withdrawal'];
        }
        else{
            $insert_array = array();
            if ($br['config']['bank_withdrawal_system'] == 1 && !empty($_POST['iban']) && !empty($_POST['country']) && !empty($_POST['full_name']) && !empty($_POST['swift_code']) && !empty($_POST['address'])) {
                $insert_array['iban'] = Br_Secure($_POST['iban']);
                $insert_array['country'] = Br_Secure($_POST['country']);
                $insert_array['full_name'] = Br_Secure($_POST['full_name']);
                $insert_array['swift_code'] = Br_Secure($_POST['swift_code']);
                $insert_array['address'] = Br_Secure($_POST['address']);
                $userU          = Br_UpdateUserData($br['user']['user_id'], array(
                                        'paypal_email' => ''
                                    ));
            }
            $insert_payment = Br_RequestNewPayment($br['user']['user_id'], $_POST['amount'],$insert_array);
            if ($insert_payment) {
                $update_balance = Br_UpdateBalance($br['user']['user_id'], $_POST['amount'], '-');
                $response_data['message'] = $br['lang']['you_request_sent'];
                $response_data['api_status'] = 200;
            }
        }
    }
}
else{
    $error_code    = 4;
    $error_message = 'type can not be empty';
}