<?php
include 'db.php';
include 'functions.php';

$data = json_decode(file_get_contents("php://input"));

$firstName = $data->firstName;
$lastName = $data->lastName;
$email = $data->email;
$phone = $data->phone;
$address = $data->address;
$userID = 1; // Assume current user ID is 1 for demo purposes

addShareholder($firstName, $lastName, $email, $phone, $address, $userID);
?>
