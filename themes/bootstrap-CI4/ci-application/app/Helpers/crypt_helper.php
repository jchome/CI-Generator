<?php

function cryptId($value){
    $coded = ($value * 154893) + 24971;
    return base_convert($coded, 10, 8);
}

function decryptKey($hash){
    $hash_10 = base_convert(intval($hash), 8, 10);
    return ($hash_10 - 24971) / 154893;
}

?>