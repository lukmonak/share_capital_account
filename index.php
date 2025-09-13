<?php
include 'functions.php';

if(isset($_POST['add'])) {
    addShareholder($_POST['firstName'], $_POST['lastName'], $_POST['Email'], $_POST['phoneNumber'], $_POST['Address']); 
}
if(isset($_POST['insert'])) {
    insertTransaction($_POST['shareholderID'],$_POST['shareID'],$_POST['transactionType'],$_POST['quantity'],$_POST['pricePerShare']);
}

if(isset($_POST['adddividend'])) {
    declareDividend($_POST['ShareholderID'], $_POST['Amount']);
}



?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Share Capital Management</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h1 class="text-center my-4">Share Capital Management</h1>
        
        <!-- Shareholder Management -->
        <div class="card mb-4">
            <div class="card-header">Add New Shareholder</div>
            <div class="card-body">
                <form id="addShareholderForm" method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                    <div class="form-group">
                        <label for="firstName">First Name</label>
                        <input type="text" name="firstName" class="form-control" id="firstName" required>
                    </div>
                    <div class="form-group">
                        <label for="lastName">Last Name</label>
                        <input type="text" name="lastName" class="form-control" id="lastName" required>
                    </div>
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" name="Email" class="form-control" id="email" required>
                    </div>
                    <div class="form-group">
                        <label for="phone">Phone Number</label>
                        <input type="text" name="phoneNumber" class="form-control" id="phone" required>
                    </div>
                    <div class="form-group">
                        <label for="address">Address</label>
                        <input type="text" name = "Address" class="form-control" id="address" required>
                    </div>
                    <button type="submit" name="add" class="btn btn-primary">Add Shareholder</button>
                </form>
            </div>
        </div>

        <!-- Transaction Management -->
        <div class="card mb-4">
            <div class="card-header">Record Transaction</div>
            <div class="card-body">
                <form id="transactionForm" method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                    <div class="form-group">
                        <label for="shareholderID">Shareholder ID</label>
                        <input type="number" name="shareholderID" class="form-control" id="shareholderID" required>
                    </div>
                    <div class="form-group">
                        <label for="shareID">Share ID</label>
                        <input type="number" name="shareID" class="form-control" id="shareID" required>
                    </div>
                    <div class="form-group">
                        <label for="transactionType">Transaction Type</label>
                        <select name="transactionType" class="form-control" id="transactionType" required>
                            <option value="purchase">Purchase</option>
                            <option value="sale">Sale</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="quantity">Quantity</label>
                        <input type="number" name="quantity" class="form-control" id="quantity" required>
                    </div>
                    <div class="form-group">
                        <label for="pricePerShare">Price Per Share</label>
                        <input type="number" name="pricePerShare" step="0.01" class="form-control" id="pricePerShare" required>
                    </div>
                    <button type="submit" name="insert" class="btn btn-primary">Record Transaction</button>
                </form>
            </div>
        </div>
        <!-- Dividend Management -->
        <div class="card mb-4">
            <div class="card-header bg-warning text-dark">Declare Dividend</div>
                <div class="card-body">
                    <form id="declareDividendForm" method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="dividendShareholderID">Shareholder ID</label>
                                <input type="number" name="ShareholderID" class="form-control" id="dividendShareholderID" required>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="dividendAmount">Amount</label>
                                <input type="number" name="Amount" step="0.01" class="form-control" id="dividendAmount" required>
                            </div>
                        </div>
                        <button type="submit" name="adddividend" class="btn btn-warning">Declare Dividend</button>
                    </form>
                </div>
            </div>
        <!-- Reports -->
        <div class="card mb-4">
            <div class="card-header">Generate Report</div>
            <div class="card-body">
                <form id="reportForm" method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                    <div class="form-group">
                        <label for="reportShareholderID">Shareholder ID</label>
                        <input type="number" name="reportShareholderID" class="form-control" id="reportShareholderID" required>
                    </div>
                    <button type="submit" name = "report" class="btn btn-primary">Generate Report</button>
                </form>
                <div id="reportOutput" class="mt-4">
                    <?php
                        if(isset($_POST['report'])) {

                            displayShareholderReport($_POST['reportShareholderID']);

                            $shareholderID = ($_POST['reportShareholderID']);
                            $totalGainsOrLosses = calculateGainsOrLosses($_POST['reportShareholderID']);
                            echo "Total gains or losses for shareholder $shareholderID:".number_format($totalGainsOrLosses,2)."<br>";

                            // Calculate total shares held by each shareholder
                            $totalSharesHeld = calculateTotalSharesHeld($_POST['reportShareholderID']);
                            echo "Total shares held by shareholder $shareholderID: $totalSharesHeld<br>";

                            // Calculate total gains or losses for the company
                            $totalCompanyGainsOrLosses = calculateTotalCompanyGainsOrLosses();
                            echo "Total gains or losses for the company:<i class='mdi mdi-currency-ngn'></i>".number_format($totalCompanyGainsOrLosses,2)."<br>";
                            // echo "<pre>";
                            getDividendReport($_POST['reportShareholderID']);
                            // echo "</pre>";
                        }
                    ?>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <!-- <script src="script.js"></script></body> -->
</html>
