<?php

function validate($value, $noChars) {
    $valid = isset($value);
    if ($valid) {
        $valid = hasRequiredLength($value, $noChars);
    }

    return $valid;
}

function hasRequiredLength($value, $noChars) {
    $valid = false;
    $trimmedValue = trim($value);
    if (strlen($trimmedValue) >= $noChars) {
        $valid = true;
    }
    return $valid;
}

?>