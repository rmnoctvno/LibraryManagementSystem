<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reports</title>
    <!-- Bootstrap 5 CSS -->
    <!-- Custom Styles -->
    <style media="screen">
        body {
            background-color: hwb(0 81% 19%);
            font-family: Arial, sans-serif;
        }
        .paul{
            text-decoration: none;
            padding-top: 0px;
            text-decoration: none;
            color: #303F9F;
            margin-left: 10px;
            font-weight: 500;
        } 

        h2 {
            color: #303F9F;
            margin-bottom: 15px;
        }

        .card {
            border: none;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            overflow: hidden;
        }

        .card-header {
            background-color: #303F9F;
            color: white;
            font-weight: bold;
            font-size: 1.1rem;
        }

        .action-link {
            text-decoration: none;
            color: #303F9F;
            margin-left: 10px;
            font-weight: 500;
        }

        .action-link:hover {
            text-decoration: underline;
        }
        .paul:hover {
            text-decoration: underline;
        }

        .report-item {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            border-bottom: 1px solid #f0f0f0;
        }

        .report-item:last-child {
            border-bottom: none;
        }
    </style>
</head>

<body>
    <div class="container mt-4">
        <h2 style="font-weight: bold;">Reports</h2>

        <div class="row g-4">
            <!-- Book Report -->
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">Book Report</div>
                    <div class="card-body">
                        <div class="report-item">
                            <span>Inventory Report</span>
                            <a class="paul" href="#" class="action-link">Print</a>
                        </div>
                        <div class="report-item">
                            <span>Most Borrowed Book</span>
                            <a class="paul" href="#" class="action-link">Print</a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Users Report -->
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">Users Report</div>
                    <div class="card-body">
                        <div class="report-item">
                            <span>Active Users Report</span>
                            <div>
                                <a href="#" class="action-link">View</a>
                                <a href="#" class="action-link">Print</a>
                            </div>
                        </div>
                        <div class="report-item">
                            <span>Transaction History by User</span>
                            <div>
                                <a href="#" class="action-link">View</a>
                                <a href="#" class="action-link">Print</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Transactions Report -->
            <div class="col-12">
                <div class="card">
                    <div class="card-header">Transactions Report</div>
                    <div class="card-body">
                        <div class="report-item">
                            <span>Daily/Weekly/Monthly Statistics</span>
                            <div>
                                <a href="#" class="action-link">View</a>
                                <a href="#" class="action-link">Print</a>
                            </div>
                        </div>
                        <div class="report-item">
                            <span>User Fines/Overdues</span>
                            <div>
                                <a href="#" class="action-link">View</a>
                                <a href="#" class="action-link">Print</a>
                            </div>
                        </div>
                        <div class="report-item">
                            <span>Pending Fines</span>
                            <div>
                                <a href="#" class="action-link">View</a>
                                <a href="#" class="action-link">Print</a>
                            </div>
                        </div>
                        <div class="report-item">
                            <span>Issued Books List</span>
                            <div>
                                <a href="#" class="action-link">View</a>
                                <a href="#" class="action-link">Print</a>
                            </div>
                        </div>
                        <div class="report-item">
                            <span>Overdue Books Report</span>
                            <div>
                                <a href="#" class="action-link">View</a>
                                <a href="#" class="action-link">Print</a>
                            </div>
                        </div>
                        <div class="report-item">
                            <span>Returned Books Summary</span>
                            <div>
                                <a href="#" class="action-link">View</a>
                                <a href="#" class="action-link">Print</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS (Optional for interactive elements) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>