<?php
function calculateTotalCompanyGainsOrLosses() {
    global $conn;

    $sql = "SELECT t.TransactionType, t.Quantity, t.PricePerShare, s.ShareID, sp.Price AS CurrentPrice
            FROM Transactions t
            JOIN Shares s ON t.ShareID = s.ShareID
            JOIN (SELECT ShareID, Price FROM SharePrices ORDER BY PriceDate DESC LIMIT 1) sp ON s.ShareID = sp.ShareID";
    $result = $conn->query($sql);

    $totalGainsOrLosses = 0;

    while ($row = $result->fetch_assoc()) {
        $quantity = $row['Quantity'];
        $pricePerShare = $row['PricePerShare'];
        $currentPrice = $row['CurrentPrice'];

        if ($row['TransactionType'] === 'purchase') {
            $totalGainsOrLosses += ($currentPrice - $pricePerShare) * $quantity;
        } else if ($row['TransactionType'] === 'sale') {
            $totalGainsOrLosses -= ($currentPrice - $pricePerShare) * $quantity;
        }
    }

    return $totalGainsOrLosses;
}
?>
