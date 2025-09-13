<?php
include 'db.php';
include 'functions.php';

$data = json_decode(file_get_contents("php://input"));

$shareholderID = $data->shareholderID;
$amount = $data->amount;
$userID = 2; // Assume current user ID is 1 for demo purposes

declareDividend($shareholderID, $amount, $userID);
?>
