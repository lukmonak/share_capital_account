<?php
include 'db.php';
include 'functions.php';

$data = json_decode(file_get_contents("php://input"));

$shareholderID = $data->shareholderID;
$shareID = $data->shareID;
$transactionType = $data->transactionType;
$quantity = $data->quantity;
$pricePerShare = $data->pricePerShare;
$userID = 1; // Assume current user ID is 1 for demo purposes

insertTransaction($shareholderID, $shareID, $transactionType, $quantity, $pricePerShare, $userID);
?>
