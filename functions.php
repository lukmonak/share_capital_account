<?php
include 'db.php';

function insertTransaction($shareholderID, $shareID, $transactionType, $quantity, $pricePerShare) {
    global $conn;
    
    $sql = "INSERT INTO Transactions (ShareholderID, ShareID, TransactionType, Quantity, PricePerShare, TransactionDate) 
            VALUES (?, ?, ?, ?, ?, NOW())";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iisid", $shareholderID, $shareID, $transactionType, $quantity, $pricePerShare);
    
    if ($stmt->execute()) {
        $description = "$transactionType of $quantity shares at $pricePerShare per share";
        logAudit("Transactions", $transactionType, mysqli_insert_id($conn), $description);
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

function addShareholder($firstName, $lastName, $email, $phoneNumber, $address) {
    global $conn;

    $sql = "INSERT INTO Shareholders (FirstName, LastName, Email, PhoneNumber, Address) 
            VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssss", $firstName, $lastName, $email, $phoneNumber, $address);

    if ($stmt->execute()) {
        $description = "Added shareholder $firstName $lastName";
        logAudit("Shareholders", "INSERT", mysqli_insert_id($conn), $description);
        echo "Shareholder successfully added.";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}

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

function calculateGainsOrLosses($shareholderID) {
    global $conn;

    $sql = "SELECT t.TransactionType, t.Quantity, t.PricePerShare, s.ShareID, sp.Price AS CurrentPrice
            FROM Transactions t
            JOIN Shares s ON t.ShareID = s.ShareID
            JOIN (SELECT ShareID, Price FROM SharePrices ORDER BY PriceDate DESC LIMIT 1) sp ON s.ShareID = sp.ShareID
            WHERE t.ShareholderID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $shareholderID);
    $stmt->execute();
    $result = $stmt->get_result();

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

    $stmt->close();

    return $totalGainsOrLosses;
}

function purchaseAdditionalShares($shareholderID, $shareID, $quantity, $pricePerShare, $userID) {
    global $conn;

    insertTransaction($shareholderID, $shareID, "purchase", $quantity, $pricePerShare, $userID);

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

function getShareholderReport($shareholderID) {
    global $conn;

    $sql = "SELECT t.TransactionType, t.Quantity, t.PricePerShare, s.ShareName
            FROM Transactions t
            JOIN Shares s ON t.ShareID = s.ShareID
            WHERE t.ShareholderID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $shareholderID);
    $stmt->execute();
    $result = $stmt->get_result();

    $report = [];
    while ($row = $result->fetch_assoc()) {
        $report[] = $row;
    }

    $stmt->close();

    return $report;
}

function displayShareholderReport($shareholderID) {
    $report = getShareholderReport($shareholderID);

    echo "<h2>Shareholder Report</h2>";
    echo "<table border='1'>
            <tr>
                <th>Share Name</th>
                <th>Transaction Type</th>
                <th>Quantity</th>
                <th>Price Per Share</th>
            </tr>";

    foreach ($report as $row) {
        echo "<tr>
                <td>{$row['ShareName']}</td>
                <td>{$row['TransactionType']}</td>
                <td>{$row['Quantity']}</td>
                <td>{$row['PricePerShare']}</td>
              </tr>";
    }

    echo "</table>";
}


function declareDividend($shareholderID, $amount) {
    global $conn;
    
    $date = date('Y-m-d');
    $stmt = $conn->prepare("INSERT INTO dividends (ShareholderID, Amount, Date) VALUES (?, ?, ?)");
    $stmt->bind_param("ids", $shareholderID, $amount, $date);
    $stmt->execute();
    $stmt->close();
    $description = "Declared dividend for shareholder $shareholderID vhg aycts SAR  A[[ LQM U9 NS9UN SUF' ";
    logAudit("dividends", "INSERT", mysqli_insert_id($conn), $description);
}

function getDividendReport($shareholderID) {
    global $conn;

    $stmt = $conn->prepare("SELECT * FROM Dividends WHERE ShareholderID = ?");
    $stmt->bind_param("i", $shareholderID);
    $stmt->execute();
    $result = $stmt->get_result();

    echo "<h2>Dividend Report</h2>";
    echo "<table border='1'>
            <tr>
                <th>DIvidendID</th>
                <th>ShareholderID</th>
                <th>Amount</th>
                <th>Date</th>
            </tr>";
    
    $dividends = [];
    while ($row = $result->fetch_assoc()) {
        $dividends[] = $row;
        echo "<tr>
        <td>{$row['DividendID']}</td>
        <td>{$row['ShareholderID']}</td>
        <td>{$row['Amount']}</td>
        <td>{$row['Date']}</td>
      </tr>";
    }
    echo "</table>";
    $stmt->close();
    return $dividends;
}


?>
