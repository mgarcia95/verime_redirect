<?php
// © VeriMe Redirect API Demo v1.09 2014 Telecom2 – Confidential. May Not Be Reproduced Without Prior Permission

date_default_timezone_set('Europe/London');

ini_set('log_errors', true);
ini_set('error_log', dirname(__FILE__).'/logs/error_'.date("Y_m_d").'.log');

// SPECIFY API CLIENT ID AND SECRET KEY
$AV_CLIENT_ID = "1";
$AV_SECRET_KEY = "1fe82037-2e08-49b0-8187-a6d7559205bb";

// HOW OLD CAN THE AV TIMESTAMP BE BEFORE REDIRECT REQUEST IS CONSIDERED EXPIRED
// ENSURE YOUR SERVER CLOCK IS SYNCHRONIZED (e.g. USING NTP)
$TIME_THRESHOLD_SECS = 30;

// Calculate Secure Hash
// Returns FALSE on error
// Hash is always returned as Lower Case
function calculateSecureHash($query_string, $secret_key, $exclude_param = "sc") {
    // Check Query Qtring is not empty
    if (strlen($query_string) === 0) {
        error_log(__FILE__.": Unable to calculate Secure Hash for empty Query String");
        return FALSE;
    }
    
    // Get Array of K,V (Automatically URL Decodes values)
    parse_str($query_string, $query_array);
    if (empty($query_array)) {
        error_log(__FILE__.": Unable to calculate Secure Hash for invalid Query String [".$query_string."]");
        return FALSE;        
    }
    
    // Exclude Parameter from Calculation 
    // Useful if Query String already contains calculated Secure Hash
    if (strlen($exclude_param) !== 0) {
        unset($query_array[$exclude_param]);
    }
    
    // Sort Params alphabetically by key
    if (!ksort($query_array)) {
        error_log(__FILE__.": Unable to calculate Secure Hash. Can't ksort array for Query String [".$query_string."]");
        RETURN FALSE;
    }
    
    // Calculate Secure Hash
    $secure_hash = strtolower(hash('sha256', $secret_key.":".implode($query_array).":".$secret_key));
    return $secure_hash;
}

// Check AV Timestamp is within a time threshold
// ENSURE YOUR SERVER CLOCK IS SYNCHRONIZED (e.g. USING NTP)
function isTimestampWithinThreshold($suppliedTS) {
	global $TIME_THRESHOLD_SECS;
	
	// Current Time
	$currentTS = time();
	
	// Check supplied timestamp is numeric
	if (!is_numeric($suppliedTS)) {
		return false;	
	}
	// Convert supplied timestamp to int
	$suppliedTS = $suppliedTS+0;
	
	// If timestamps are the same
	if ($suppliedTS === $currentTS) {
		return true;	
	}
	// If supplied timestamp is earlier than current time
	if ($suppliedTS < $currentTS) {
		// If outside threshold
		if (($currentTS - $suppliedTS) >= $TIME_THRESHOLD_SECS) {
			return false;
		}
		else {
			return true;
		} 
	}
	
	// If supplied timestamp is later than current time
	// If outside threshold
	if (($suppliedTS - $currentTS) >= $TIME_THRESHOLD_SECS) {		
		return false;
	}
	else {
		return true;
	} 
}
?>
