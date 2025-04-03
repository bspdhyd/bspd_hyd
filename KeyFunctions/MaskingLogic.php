<?php 

function maskPhoneNumber($phoneNumber) {
    
    $length = strlen($phoneNumber);
    
    if ($length > 5) {
    // Get the last 5 digits
    $lastFiveDigits = substr($phoneNumber, -5);

    // Create the masked part
    $maskedPart = str_repeat('X', $length - 5);

    // Combine the masked part with the last 5 digits
    return $maskedPart . $lastFiveDigits;
    } 
    else {$maskedPart = str_repeat('X', $length);
    return $maskedPart; }
}

function maskEmail($email) {
    // Split the email into username and domain
    list($username, $domain) = explode('@', $email);
    
    // Get the length of the username
    $usernameLength = strlen($username);
    
    // Mask the username
    if ($usernameLength > 4) {
        $maskedUsername = $username[0] . $username[1] . str_repeat('*', $usernameLength - 2) . $username[$usernameLength - 2]. $username[$usernameLength - 1];
    } else {
        // If the username is too short to mask in the middle, just show it as is
        $maskedUsername = $username;
    }

    // Combine the masked username with the domain
    return $maskedUsername . '@' . $domain;
}

?>