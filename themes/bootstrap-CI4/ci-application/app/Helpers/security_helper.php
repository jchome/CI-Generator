<?php

/**
 * Return the encrypted password to store in database
 */
function generateHash($password) {
    if (defined("CRYPT_BLOWFISH") && CRYPT_BLOWFISH) {
        $salt = '$2y$11$' . substr(md5(uniqid(rand(), true)), 0, 22);
        return crypt($password, $salt);
    }
}

/**
 * Check that the password is good
 */
function verify($password, $hashedPassword) {
    return crypt($password, $hashedPassword) == $hashedPassword;
}

/**
 * Generate a string that contains the user id and a timout to limit duration of session
 */
function generateToken($userId, $ipAddress = ""){
    $saltPhrase = md5("Security");
    $now = strtotime("+0 sec");
    return urlencode(base64_encode($saltPhrase . " " . $userId . " " . $now . " " . $ipAddress));
}

function computeTokenExpirationDate(){
    return date('Y-m-d H:i:s', strtotime("+1 hour") ); // 1 hour of session
}

function check_token($token, $user, $ipAddress = ""){
    $array = explode(' ', base64_decode(urldecode($token)));
    if(sizeof($array) != 4){
        // Wrong number of arguments
        return 'Wrong number of arguments';
    }
    if($array[0] != md5("Security")){
        // Wrong security pass
        return 'Wrong security pass';
    }
    if($array[1] != $user->id){
        // Wrong userId
        return "Wrong userId: $array[1] != $user->id";
    }
    if($array[3] != $ipAddress){
        // Wrong ip address
        return "Wrong ip address: $array[3] - $ipAddress";
    }
    $startTime = $array[2];
    $now = strtotime("+0 sec");
    $expirationTime = strtotime($user->expiration_token);
    
    if($startTime < $now && $now < $expirationTime){
        return "";
    }else{
        return "Wrong test: $startTime < $now < $expirationTime";
    }

}

?>