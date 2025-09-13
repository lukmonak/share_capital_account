<?php
include 'db.php';
include 'functions.php';

// Add a new shareholder
// addShareholder("John", "Doe", "john.doe@example.com", "123-456-7890", "123 Main St", 1);

// Add a new transaction
// insertTransaction(4, 1, "purchase", 100, 10.50, 1);

// Update share price
// updateSharePrice(1, 12.00, 1);

// Calculate gains or losses for a shareholder
$shareholderID = 2;
$totalGainsOrLosses = calculateGainsOrLosses(2);
echo "Total gains or losses for shareholder $shareholderID: $totalGainsOrLosses<br>";

// Purchase additional shares
$shareID = 1;
$quantity = 50;
$pricePerShare = 11.00;
$userID = 1;

purchaseAdditionalShares($shareholderID, $shareID, $quantity, $pricePerShare, $userID);

// Calculate total shares held by each shareholder
$totalSharesHeld = calculateTotalSharesHeld(2);
echo "Total shares held by shareholder $shareholderID: $totalSharesHeld<br>";

// Calculate total gains or losses for the company
$totalCompanyGainsOrLosses = calculateTotalCompanyGainsOrLosses();
echo "Total gains or losses for the company: $totalCompanyGainsOrLosses<br>";

// Generate a shareholder report
displayShareholderReport(3);
?>
