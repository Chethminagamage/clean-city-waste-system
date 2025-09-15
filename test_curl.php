<?php
echo "Testing cURL connectivity to Google APIs...\n";

// Test basic cURL
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "https://www.googleapis.com");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 10);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // For testing only
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false); // For testing only

$result = curl_exec($ch);
$error = curl_error($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($error) {
    echo "cURL Error: " . $error . "\n";
} else {
    echo "SUCCESS: HTTP " . $httpCode . "\n";
    echo "Response length: " . strlen($result) . " bytes\n";
}

// Test PHP's built-in DNS
echo "\nTesting gethostbyname()...\n";
$ip = gethostbyname('www.googleapis.com');
echo "Resolved IP: " . $ip . "\n";

// Test with different DNS
echo "\nTesting with Google DNS (8.8.8.8)...\n";
$context = stream_context_create([
    'http' => [
        'timeout' => 10,
    ]
]);

try {
    $headers = get_headers('https://www.googleapis.com', 1, $context);
    echo "Headers retrieved successfully!\n";
    print_r($headers[0] ?? 'No status');
} catch (Exception $e) {
    echo "Stream context error: " . $e->getMessage() . "\n";
}
?>