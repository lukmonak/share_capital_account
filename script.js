document.addEventListener("DOMContentLoaded", function () {
    const baseUrl = ''; // Update with your server URL if needed

    // Handle Add Shareholder Form Submission
    document.getElementById("addShareholderForm").addEventListener("submit", function (e) {
        e.preventDefault();

        const data = {
            firstName: document.getElementById("firstName").value,
            lastName: document.getElementById("lastName").value,
            email: document.getElementById("email").value,
            phone: document.getElementById("phone").value,
            address: document.getElementById("address").value
        };

        fetch(`${baseUrl}/add_shareholder.php`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(data)
        })
        .then(response => response.text())
        .then(data => {
            alert('Shareholder added successfully!');
            document.getElementById("addShareholderForm").reset();
        })
        .catch(error => console.error('Error:', error));
    });

    // Handle Record Transaction Form Submission
    document.getElementById("transactionForm").addEventListener("submit", function (e) {
        e.preventDefault();

        const data = {
            shareholderID: document.getElementById("shareholderID").value,
            shareID: document.getElementById("shareID").value,
            transactionType: document.getElementById("transactionType").value,
            quantity: document.getElementById("quantity").value,
            pricePerShare: document.getElementById("pricePerShare").value
        };

        fetch(`${baseUrl}/record_transaction.php`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(data)
        })
        .then(response => response.text())
        .then(data => {
            alert('Transaction recorded successfully!');
            document.getElementById("transactionForm").reset();
        })
        .catch(error => console.error('Error:', error));
    });

    // Handle Declare Dividend Form Submission
    document.getElementById("declareDividendForm").addEventListener("submit", function (e) {
        e.preventDefault();

        const data = {
            shareholderID: document.getElementById("dividendShareholderID").value,
            amount: document.getElementById("dividendAmount").value
        };

        fetch(`${baseUrl}/declare_dividend.php`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(data)
        })
        .then(response => response.text())
        .then(data => {
            alert('Dividend declared successfully!');
            document.getElementById("declareDividendForm").reset();
        })
        .catch(error => console.error('Error:', error));
    });

    // Handle Generate Report Form Submission
    document.getElementById("reportForm").addEventListener("submit", function (e) {
        e.preventDefault();

        const data = {
            shareholderID: document.getElementById("reportShareholderID").value
        };

        fetch(`${baseUrl}/generate_report.php`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(data)
        })
        .then(response => response.json())
        .then(data => {
            const reportOutput = document.getElementById("reportOutput");
            reportOutput.innerHTML = `<pre>${JSON.stringify(data, null, 2)}</pre>`;
        })
        .catch(error => console.error('Error:', error));
    });
});
