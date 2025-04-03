<?php
function generateComplexOTP($length) {
    
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ!@#$%^&*()_+';
    $otp = '';
    for ($i = 0; $i < $length; $i++) {
        $otp .= $characters[mt_rand(0, strlen($characters) - 1)];
    }
    return $otp;
}
?>
