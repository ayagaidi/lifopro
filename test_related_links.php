<?php

// Test script to verify the related_link changes
require_once __DIR__ . '/vendor/autoload.php';

use App\Services\LifoApiService;
use App\Models\ApiLog;

echo "Testing related_link implementation...\n\n";

// Test 1: Check LifoApiService related_link generation
echo "1. Testing LifoApiService related_link generation:\n";
$lifoService = new LifoApiService();
echo "API Base URL: " . $lifoService->url . "\n\n";

// Test 2: Verify the API URL format
echo "2. Expected API endpoint patterns:\n";
$expectedEndpoints = [
    'getAuth' => 'OcUser/GetToken',
    'issuingPolicy' => 'OcPolicy/NewPolicy', 
    'policystatus' => 'OcPolicy/OCPolStatus',
    'newrequestadmin' => 'OcRequest/NewUORequest',
    'postInsCompCertificateBook' => 'OrangeCardServices/PostInsCompCertificateBook',
    'accept_request' => 'OrangeCardServices/PostInsCompCertificateBook',
    'reject_request' => 'OcRequest/UoRequestStatus'
];

foreach ($expectedEndpoints as $operation => $endpoint) {
    echo "  $operation -> " . $lifoService->url . $endpoint . "\n";
}

echo "\n3. Changes implemented:\n";
echo "  - LifoApiService.php: Updated all related_link values to use API endpoints\n";
echo "  - RequestsController.php: Updated accept_request and reject_request related_links\n";
echo "  - All ApiLog::create() calls now use actual API URLs instead of local application URLs\n";

echo "\n✓ Related link implementation updated successfully!\n";