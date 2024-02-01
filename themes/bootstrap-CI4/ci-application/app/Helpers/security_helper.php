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
    $date = new DateTime();
    $now = $date->getTimestamp() * 1000; // number of miliseconds
    $maxSessionDuration = 24 * 60 * 60 * 1000; // 24 hours
    $endTime = $now + $maxSessionDuration;
    return base64_encode($saltPhrase . " " . $userId . " " . $endTime . " " . $ipAddress);
}

function check_token($token, $userId, $ipAddress = ""){
    $array = explode(' ', base64_decode($token));
    if(sizeof($array) != 4){
        // Wrong number of arguments
        return FALSE;
    }
    if($array[0] != md5("Security")){
        // Wrong security pass
        return FALSE;
    }
    if($array[1] != $userId){
        // Wrong userId
        return FALSE;
    }
    if($array[3] != $ipAddress){
        // Wrong ip address
        return FALSE;
    }
    $date = new DateTime();
    $now = $date->getTimestamp() * 1000; // number of miliseconds
    if($now > $array[2]){
        // Token expired
        return FALSE;
    }
    return TRUE;

}

?>