<?php
function addShareholder($firstName, $lastName, $email, $phoneNumber, $address, $userID) {
    global $conn;

    $sql = "INSERT INTO Shareholders (FirstName, LastName, Email, PhoneNumber, Address) 
            VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssss", $firstName, $lastName, $email, $phoneNumber, $address);

    if ($stmt->execute()) {
        $description = "Added shareholder $firstName $lastName";
        logAudit("Shareholders", "INSERT", $userID, $description);
        echo "Shareholder successfully added.";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}
?>
