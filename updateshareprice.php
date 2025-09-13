<?php
function updateSharePrice($shareID, $price, $userID) {
    global $conn;

    $sql = "INSERT INTO SharePrices (ShareID, Price, PriceDate) VALUES (?, ?, NOW())";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("id", $shareID, $price);

    if ($stmt->execute()) {
        $description = "Updated share price for ShareID $shareID to $price";
        logAudit("SharePrices", "INSERT", $userID, $description);
        echo "Share price successfully updated.";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}
?>
