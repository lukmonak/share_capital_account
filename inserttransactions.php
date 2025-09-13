<?php
include 'db.php';

function insertTransaction($shareholderID, $shareID, $transactionType, $quantity, $pricePerShare, $userID) {
    global $conn;
    
    $sql = "INSERT INTO Transactions (ShareholderID, ShareID, TransactionType, Quantity, PricePerShare, TransactionDate) 
            VALUES (?, ?, ?, ?, ?, NOW())";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iisid", $shareholderID, $shareID, $transactionType, $quantity, $pricePerShare);
    
    if ($stmt->execute()) {
        $description = "$transactionType of $quantity shares at $pricePerShare per share";
        logAudit("Transactions", $transactionType, $userID, $description);
        echo "Transaction successfully recorded.";
    } else {
        echo "Error: " . $stmt->error;
    }
    
    $stmt->close();
}

function logAudit($tableName, $operation, $userID, $description) {
    global $conn;
    
    $sql = "INSERT INTO AuditLog (TableName, Operation, UserID, Description) 
            VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssis", $tableName, $operation, $userID, $description);
    
    if (!$stmt->execute()) {
        echo "Error logging audit: " . $stmt->error;
    }
    
    $stmt->close();
}
?>
