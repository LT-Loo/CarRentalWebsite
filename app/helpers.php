<?php
function customSort($a, $b) {
    // Convert both strings to lowercase for case-insensitive comparison
    $aLower = strtolower($a);
    $bLower = strtolower($b);

    // Get positions of 'f' in both strings
    $aPos = strpos($aLower, 'f');
    $bPos = strpos($bLower, 'f');

    // Compare positions of 'f'
    if ($aPos === false) {
        $aPos = PHP_INT_MAX; // Assign a high value if 'f' is not found
    }
    if ($bPos === false) {
        $bPos = PHP_INT_MAX; // Assign a high value if 'f' is not found
    }

    // If 'f' appears at the same position, compare based on the original order
    if ($aPos === $bPos) {
        return strcmp($a, $b);
    }

    // Otherwise, compare positions of 'f'
    return $aPos - $bPos;
}

?>