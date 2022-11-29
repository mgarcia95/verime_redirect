<?php
// © VeriMe Redirect API Demo v1.09 2014 Telecom2 – Confidential. May Not Be Reproduced Without Prior Permission

require("utils.php");

// SPECIFY AGE-VERIFIED AND NOT-AGE-VERIFIED URLS BELOW
$AGE_VERIFIED_URL = "http://127.0.0.1/verime_redirect/ageverified.php?av=true";
$NOT_AGE_VERIFIED_URL = "http://127.0.0.1/verime_redirect/NOTageverified.php?av=false";

// Construct Query String
// Add Any Additional Parameters here (e.g. user id etc)
$QUERY_STRING = "cid=".$AV_CLIENT_ID."&av=".urlencode($AGE_VERIFIED_URL)."&nav=".urlencode($NOT_AGE_VERIFIED_URL);
        
// SHA256 Hash to append to Redirect Query String
$SECURE_HASH = calculateSecureHash($QUERY_STRING, $AV_SECRET_KEY);

if ($SECURE_HASH !== FALSE) {
    // Redirect to VeriMe
    header("Location: https://verime.net/ageverify/indexdemo_nocaptcha.php?".$QUERY_STRING."&sc=".$SECURE_HASH);
    exit();
}
?>
<html>
<head>
<title>VeriMe Redirect API DEMO - Error</title>
<style type="text/css">
<!--
body {
    font-family: Arial, Helvetica, sans-serif;
}
-->
</style>
</head>
<body>
    <span style="color:red;font-weight:bold">Sorry. There was an error. Please try later.</span>
</body>
</html>
