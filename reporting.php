<?php
include 'db.php';

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
?>
