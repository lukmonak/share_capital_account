<?php
include 'db.php';
include 'functions.php';

$data = json_decode(file_get_contents("php://input"));

$shareholderID = $data->shareholderID;
$report = getShareholderReport($shareholderID);

header('Content-Type: application/json');
echo json_encode($report);
?>
