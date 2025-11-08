<?php

require 'vendor/autoload.php';

use Carbon\Carbon;

date_default_timezone_set('Africa/Tripoli');

$start = Carbon::parse('2025-10-20 05:20:00');

$end = $start->copy()->addHours(7 * 24);

echo "Start: " . $start->format('Y-m-d H:i:s') . "\n";

echo "End: " . $end->format('Y-m-d H:i:s') . "\n";

$max = $start->copy()->addHours(90 * 24);

echo "Max 90 days end: " . $max->format('Y-m-d H:i:s') . "\n";

$min7 = $start->copy()->addHours(7 * 24);

echo "Min 7 days end: " . $min7->format('Y-m-d H:i:s') . "\n";
?>
