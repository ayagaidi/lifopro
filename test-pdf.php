<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

$kernel->bootstrap();

// Create a mock request
$request = Illuminate\Http\Request::create('/print-card', 'GET', [
    'card_number' => 'LBY/369025',
    'beneficiary_name' => 'محمد',
    'beneficiary_address' => '',
    'vehicle_type' => 'كيا',
    'chassis_number' => '565656',
    'plate_number' => '555555',
    'engine_number' => '-',
    'insurance_start_date' => '2026-01-01 12:00:00',
    'insurance_end_date' => '',
    'total_premium' => '59.990',
    'test' => 'true'
]);

// Handle the request
$response = $kernel->handle($request);

// Check the response
if ($response->getStatusCode() === 200) {
    echo "✅ PDF generated successfully\n";
    
    // Check if it's a PDF
    $contentType = $response->headers->get('Content-Type');
    echo "Content-Type: " . $contentType . "\n";
    
    if (strpos($contentType, 'application/pdf') !== false) {
        echo "✅ Response is a valid PDF\n";
        
        // Save the PDF to file
        $filename = 'test-insurance-card.pdf';
        file_put_contents($filename, $response->getContent());
        echo "✅ PDF saved as: " . $filename . "\n";
        echo "File size: " . filesize($filename) . " bytes\n";
        
        // Check file size
        if (filesize($filename) > 0) {
            echo "✅ PDF contains content\n";
        }
    } else {
        echo "❌ Response is not a PDF\n";
        echo "Response body snippet:\n";
        echo substr($response->getContent(), 0, 200) . "...\n";
    }
} else {
    echo "❌ Request failed with status code: " . $response->getStatusCode() . "\n";
    echo $response->getContent() . "\n";
}

$kernel->terminate($request, $response);
