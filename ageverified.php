<?php
// © VeriMe Redirect API Demo v1.09 2014 Telecom2 – Confidential. May Not Be Reproduced Without Prior Permission

require("utils.php");

$security_error = false;

// If No AV param Query String
if (!isset($_GET["av"])) {
    // Log Error
    error_log(__FILE__.": av parameter not found on URL [".
            $_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']."]");
    $security_error = true;
} 
else {
	// Check AV supplied on Query String
	$av = $_GET["av"];
	if ($av !== "true") {
		if (!isset($_GET["av"])) {
			// Log Error
			error_log(__FILE__.": av parameter not [true]");
			$security_error = true;	
		}
	}
}


// If No SHA256 Hash on Query String
if (!isset($_GET["sc"])) {
    // Log Error
    error_log(__FILE__.": sc parameter not found on URL [".
            $_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']."]");
    $security_error = true;
} 
// Get Secure Hash supplied on Query String
$supplied_secure_hash = $_GET["sc"];

// Calculate Secure Hash independently 
$calculated_secure_hash = calculateSecureHash($_SERVER['QUERY_STRING'], $AV_SECRET_KEY, "sc");
if ($calculated_secure_hash === FALSE) {
    $security_error = true;
}

// Compare Hashes
if (strcasecmp($supplied_secure_hash, $calculated_secure_hash) !== 0) {
    // Log Error
    error_log(__FILE__.": Supplied Secure Hash [".$supplied_secure_hash.
            "] does not match Calculated Secure Hash [".$calculated_secure_hash.
            "] on URL [".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']."]"); 
    $security_error = true;
}


// Check AV Timestamp is within a time threshold
// ENSURE YOUR SERVER CLOCK IS SYNCHRONIZED (e.g. USING NTP)
if (!isset($_GET["ts"])) {
    // Log Error
    error_log(__FILE__.": ts parameter not found on URL [".
            $_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']."]");
    $security_error = true;
} 
// Get timstamp of AV
$timestamp = $_GET["ts"];
if (!isTimestampWithinThreshold($timestamp)) {
    // Log Error
    error_log(__FILE__.": ts parameter [".$timestamp."] not within acceptable Time Threshold. Current time: [".time()."]");
    $security_error = true;	
}


// If no Transaction ID received
// (should never happen)
if (!isset($_GET["tid"])) {
    // Log Error
    error_log(__FILE__.": tid parameter not found on URL [".
            $_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']."]");
    $tid = "";
} 
else {
    // Transaction ID should be stored for your records
    $tid = $_GET["tid"];
}    
?>
<html>
<head>
<title>VeriMe Redirect API DEMO - Age-Verified</title>
<style type="text/css">
<!--
body {
    font-family: Arial, Helvetica, sans-serif;
}
-->
</style>
</head>
<body>
    <?php
        // IF AGE-VERIFIED
        if (!$security_error) {
            echo "<span style=\"color:green;font-weight:bold\">Thank you. You are Age-Verified.</span>";
        } 
        // IF SECURITY ERROR
        else {
            echo "<span style=\"color:red;font-weight:bold\">Sorry. We cannot establish Age-Verification.</span>";
        } 
    ?>
</body>
</html>