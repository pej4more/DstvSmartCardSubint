<?php
// Authentication details
$username = "YourVtpassEmail";
$password = "YourPassword";

// Endpoint URLs
$get_variation_codes_url = "https://vtpass.com/api/service-variations?serviceID=dstv";
$verify_smartcard_url = "https://vtpass.com/api/merchant-verify";
$purchase_url = "https://vtpass.com/api/pay";

// Utility function to make HTTP requests
function make_request($url, $data, $auth) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
    curl_setopt($ch, CURLOPT_USERPWD, $auth);
    $output = curl_exec($ch);
    curl_close($ch);
    return $output;
}

// Get variation codes
$response = make_request($get_variation_codes_url, null, "$username:$password");
$variations = json_decode($response)->content->variations;

// Verify smartcard number
$data = array(
    'billersCode' => '1212121212',
    'serviceID' => 'dstv'
);
$response = make_request($verify_smartcard_url, $data, "$username:$password");
$customer_name = json_decode($response)->content->Customer_Name;
$current_bouquet = json_decode($response)->content->Current_Bouquet;
$renewal_amount = json_decode($response)->content->Renewal_Amount;

// Purchase product
$data = array(
    'billersCode' => '1212121212',
    'variation_code' => $variations[0]->variation_code,
    'amount' => $renewal_amount,
    'phone' => '08123456789',
    'email' => 'customer@example.com',
    'serviceID' => 'dstv'
);
$response = make_request($purchase_url, $data, "$username:$password");
$transaction_id = json_decode($response)->content->transaction_id;
$status = json_decode($response)->content->status;
?>

