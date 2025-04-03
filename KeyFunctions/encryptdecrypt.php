 
<?php 
function EncryptDetails($link, $value, $type){ 
   $ciphering = "AES-128-CTR";   // Store the cipher method 
   $options = 0; 
   $encryption_iv = '1234567891011121';   // Non-NULL Initialization Vector for encryption 
   $encryption_key = $type;  // Store the encryption key
   $encryptvalue = openssl_encrypt($value, $ciphering, $encryption_key, $options, $encryption_iv); // Use openssl_encrypt() function to encrypt the data 
   return $encryptvalue;
}

function DecryptDetails($link, $value, $type){ 
   $decryption_iv = '1234567891011121';        // Non-NULL Initialization Vector for decryption 
   $decryption_key = $type;
   $ciphering = "AES-128-CTR"; 
   $options = 0; 
   $decryptvalue=openssl_decrypt ($value, $ciphering, $decryption_key, $options, $decryption_iv);  // Use openssl_decrypt() function to decrypt the data 
   return $decryptvalue;
}
?> 
