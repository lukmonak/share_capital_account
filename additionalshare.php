<?php
function purchaseAdditionalShares($shareholderID, $shareID, $quantity, $pricePerShare, $userID) {
    global $conn;

    // Insert new transaction
    insertTransaction($shareholderID, $shareID, "purchase", $quantity, $pricePerShare, $userID);

    // Update issued shares
    $sql = "UPDATE Shares SET IssuedShares = IssuedShares + ? WHERE ShareID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $quantity, $shareID);

    if ($stmt->execute()) {
        $description = "Purchased additional $quantity shares at $pricePerShare per share for ShareID $shareID";
        logAudit("Shares", "UPDATE", $userID, $description);
        echo "Additional shares successfully purchased.";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}
?>
