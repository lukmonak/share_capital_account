<?php
function calculateTotalSharesHeld($shareholderID) {
    global $conn;

    $sql = "SELECT SUM(CASE WHEN TransactionType = 'purchase' THEN Quantity ELSE -Quantity END) AS TotalSharesHeld
            FROM Transactions
            WHERE ShareholderID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $shareholderID);
    $stmt->execute();
    $result = $stmt->get_result();

    $totalSharesHeld = 0;
    if ($row = $result->fetch_assoc()) {
        $totalSharesHeld = $row['TotalSharesHeld'];
    }

    $stmt->close();

    return $totalSharesHeld;
}
?>
