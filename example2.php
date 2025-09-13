<?php
include 'db.php';
include 'functions.php';

// Calculate total shares held by each shareholder
$shareholderID = 1;
$totalSharesHeld = calculateTotalSharesHeld($shareholderID);
echo "Total shares held by shareholder $shareholderID: $totalSharesHeld<br>";

// Calculate total gains or losses for the company
$totalCompanyGainsOrLosses = calculateTotalCompanyGainsOrLosses();
echo "Total gains or losses for the company: $totalCompanyGainsOrLosses<br>";
?>
