<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CEU Orders - Export Demo</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- DataTables CSS -->
    <link href="https://cdn.datatables.net/1.13.8/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.bootstrap5.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            min-height: 100vh;
        }
        
        .main-container {
            background: white;
            border-radius: 15px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
            margin: 20px;
            padding: 30px;
        }
        
        .page-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 25px;
            border-radius: 12px;
            margin-bottom: 30px;
            text-align: center;
        }
        
        .export-section {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 25px;
            border-left: 4px solid #667eea;
        }
        
        .export-btn {
            margin: 5px;
            padding: 10px 20px;
            border-radius: 8px;
            font-weight: 500;
            transition: all 0.3s ease;
        }
        
        .btn-excel { background: #217346; border-color: #217346; color: white; }
        .btn-excel:hover { background: #1a5d38; transform: translateY(-2px); }
        
        .btn-pdf { background: #d63384; border-color: #d63384; color: white; }
        .btn-pdf:hover { background: #b02a5b; transform: translateY(-2px); }
        
        .btn-csv { background: #0d6efd; border-color: #0d6efd; color: white; }
        .btn-csv:hover { background: #0b5ed7; transform: translateY(-2px); }
        
        .btn-print { background: #6f42c1; border-color: #6f42c1; color: white; }
        .btn-print:hover { background: #59359a; transform: translateY(-2px); }
        
        .table-container {
            background: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        
        .table thead th {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            font-weight: 600;
            padding: 15px 10px;
        }
        
        .table tbody td {
            padding: 12px 10px;
            vertical-align: middle;
        }
        
        .table tbody tr:hover {
            background-color: #f8f9fa;
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="main-container">
            <div class="page-header">
                <h1 class="mb-2">
                    <i class="fas fa-shopping-cart me-3"></i>
                    CEU Orders Management
                </h1>
                <p class="mb-0 opacity-75">Complete order management with working export functionality</p>
            </div>
            
            <!-- Export Section -->
            <div class="export-section">
                <h5 class="mb-3">
                    <i class="fas fa-download me-2 text-primary"></i>
                    Export Data
                </h5>
                <div class="d-flex flex-wrap">
                    <button type="button" class="btn export-btn btn-excel" id="exportExcel">
                        <i class="fas fa-file-excel me-2"></i>Export to Excel
                    </button>
                    <button type="button" class="btn export-btn btn-pdf" id="exportPdf">
                        <i class="fas fa-file-pdf me-2"></i>Export to PDF
                    </button>
                    <button type="button" class="btn export-btn btn-csv" id="exportCsv">
                        <i class="fas fa-file-csv me-2"></i>Export to CSV
                    </button>
                    <button type="button" class="btn export-btn btn-print" id="exportPrint">
                        <i class="fas fa-print me-2"></i>Print Table
                    </button>
                </div>
            </div>
            
            <!-- Table Container -->
            <div class="table-container">
                <table id="ordersTable" class="table table-striped table-hover mb-0">
                    <thead>
                        <tr>
                            <th>S.No</th>
                            <th>Order ID</th>
                            <th>Order Date</th>
                            <th>Total Amount</th>
                            <th>Product Name</th>
                            <th>Quantity</th>
                            <th>Billing Name</th>
                            <th>Phone</th>
                            <th>Email</th>
                            <th>Address</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Data will be populated here -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.8/js/dataTables.bootstrap5.min.js"></script>
    
    <!-- DataTables Buttons -->
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.bootstrap5.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.print.min.js"></script>

    <script>
        $(document).ready(function() {
            console.log('Initializing CEU Orders page...');
            
            // Show loading message
            showLoadingMessage();
            
            // Use a proxy approach to handle CORS issues
            loadAPIData();
            
            function loadAPIData() {
                console.log('Attempting to load API data via proxy...');
                
                // Use local PHP proxy to avoid CORS issues
                $.ajax({
                    url: 'api_proxy.php',
                    method: 'GET',
                    dataType: 'json',
                    timeout: 30000,
                    success: function(response) {
                        console.log('Proxy response:', response);
                        
                        if (response.error) {
                            console.error('API Error via proxy:', response.error);
                            showErrorMessage();
                            return;
                        }
                        
                        if (response && Array.isArray(response) && response.length > 0) {
                            processAPIData(response);
                        } else {
                            console.log('No data received from proxy');
                            showNoDataMessage();
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('Proxy request failed:', error, 'Status:', status);
                        console.log('Falling back to direct API call...');
                        
                        // Fallback to direct API call
                        fetchDirectAPI();
                    }
                });
            }
            
            function fetchDirectAPI() {
                $.ajax({
                    url: 'https://ceutrainers.com/api/order_details.php',
                    method: 'GET',
                    dataType: 'json',
                    timeout: 15000,
                    success: function(response) {
                        console.log('Direct API success:', response);
                        if (response && Array.isArray(response) && response.length > 0) {
                            processAPIData(response);
                        } else {
                            showNoDataMessage();
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('Direct API failed:', error);
                        showErrorMessage();
                    }
                });
            }
            
            function processAPIData(apiData) {
                console.log('Processing API data:', apiData.length, 'records');
                
                // Convert API data to DataTable format
                const tableData = apiData.slice(0, 100).map((order, index) => [ // Limit to first 100 records for performance
                    index + 1,
                    order.order_id || 'N/A',
                    order.order_date ? order.order_date.split(' ')[0] : 'N/A', // Show only date part
                    order.total_amount ? `$${parseFloat(order.total_amount).toFixed(2)}` : '$0.00',
                    (order.product_name || 'N/A').substring(0, 50) + (order.product_name && order.product_name.length > 50 ? '...' : ''), // Truncate long names
                    order.product_quantity || '1',
                    order.billing_name || 'N/A',
                    order.billing_phone || 'N/A',
                    order.billing_email || 'N/A',
                    (order.billing_address || 'N/A').substring(0, 30) + (order.billing_address && order.billing_address.length > 30 ? '...' : '') // Truncate long addresses
                ]);
                
                initializeDataTable(tableData);
            }
            
            function showLoadingMessage() {
                $('#ordersTable tbody').html(`
                    <tr>
                        <td colspan="10" class="text-center py-5">
                            <div class="spinner-border text-primary" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                            <br><br>
                            <h5 class="text-primary">Loading Orders Data...</h5>
                            <p class="text-muted">Fetching data from API, please wait...</p>
                        </td>
                    </tr>
                `);
            }
            
            function showNoDataMessage() {
                $('#ordersTable tbody').html(`
                    <tr>
                        <td colspan="10" class="text-center py-5">
                            <i class="fas fa-inbox fa-4x text-muted mb-3"></i>
                            <br>
                            <h5 class="text-muted">No Orders Found</h5>
                            <p class="text-muted">No order data is currently available from the API.</p>
                        </td>
                    </tr>
                `);
            }
            
            function showErrorMessage() {
                $('#ordersTable tbody').html(`
                    <tr>
                        <td colspan="10" class="text-center py-5">
                            <i class="fas fa-exclamation-triangle fa-4x text-danger mb-3"></i>
                            <br>
                            <h5 class="text-danger">Error Loading Data</h5>
                            <p class="text-muted">Unable to fetch data from the API. Please check your connection and try again.</p>
                            <button class="btn btn-primary btn-sm" onclick="location.reload()">
                                <i class="fas fa-refresh me-2"></i>Retry
                            </button>
                        </td>
                    </tr>
                `);
            }
            
            function initializeDataTable(apiData) {
                console.log('Initializing DataTable with API data:', apiData.length, 'records');
                
                // Initialize DataTable with export buttons
                const table = $('#ordersTable').DataTable({
                    data: apiData,
                    responsive: true,
                    pageLength: 10,
                    lengthMenu: [[10, 25, 50, 100], [10, 25, 50, 100]],
                    order: [[1, 'desc']],
                    dom: 'Bfrtip',
                    buttons: [
                        {
                            extend: 'excelHtml5',
                            text: '<i class="fas fa-file-excel"></i> Excel',
                            title: 'CEU Orders Report - ' + new Date().toLocaleDateString(),
                            className: 'btn btn-success btn-sm d-none',
                            exportOptions: {
                                columns: ':visible'
                            }
                        },
                        {
                            extend: 'pdfHtml5',
                            text: '<i class="fas fa-file-pdf"></i> PDF',
                            title: 'CEU Orders Report',
                            className: 'btn btn-danger btn-sm d-none',
                            exportOptions: {
                                columns: ':visible'
                            },
                            customize: function(doc) {
                                doc.content[1].table.widths = ['5%', '10%', '10%', '10%', '25%', '5%', '15%', '10%', '10%'];
                                doc.styles.tableHeader.fontSize = 8;
                                doc.defaultStyle.fontSize = 7;
                            }
                        },
                        {
                            extend: 'csvHtml5',
                            text: '<i class="fas fa-file-csv"></i> CSV',
                            title: 'CEU Orders Report - ' + new Date().toLocaleDateString(),
                            className: 'btn btn-primary btn-sm d-none',
                            exportOptions: {
                                columns: ':visible'
                            }
                        },
                        {
                            extend: 'print',
                            text: '<i class="fas fa-print"></i> Print',
                            title: 'CEU Orders Report',
                            className: 'btn btn-info btn-sm d-none',
                            exportOptions: {
                                columns: ':visible'
                            }
                        }
                    ],
                    language: {
                        search: "Search Orders:",
                        lengthMenu: "Show _MENU_ orders per page",
                        info: "Showing _START_ to _END_ of _TOTAL_ orders",
                        emptyTable: "No order data available",
                        zeroRecords: "No matching orders found"
                    },
                    columnDefs: [
                        { targets: [3], className: 'text-end' }, // Amount column
                        { targets: [5], className: 'text-center' } // Quantity column
                    ]
                });
                
                console.log('DataTable initialized successfully with', apiData.length, 'records');
                setupExportButtons(table);
            }
            
            function setupExportButtons(table) {
                console.log('Setting up export buttons...');
                
                // Custom export button handlers
                $('#exportExcel').on('click', function() {
                    console.log('Excel export clicked');
                    table.button('.buttons-excel').trigger();
                    showExportMessage('Excel');
                });
                
                $('#exportPdf').on('click', function() {
                    console.log('PDF export clicked');
                    table.button('.buttons-pdf').trigger();
                    showExportMessage('PDF');
                });
                
                $('#exportCsv').on('click', function() {
                    console.log('CSV export clicked');
                    table.button('.buttons-csv').trigger();
                    showExportMessage('CSV');
                });
                
                $('#exportPrint').on('click', function() {
                    console.log('Print clicked');
                    table.button('.buttons-print').trigger();
                    showExportMessage('Print');
                });
                
                console.log('Export buttons configured successfully');
            }
            
            function showExportMessage(type) {
                // Create a temporary success message
                const message = $(`
                    <div class="alert alert-success alert-dismissible fade show position-fixed" 
                         style="top: 20px; right: 20px; z-index: 9999; min-width: 300px;">
                        <i class="fas fa-check-circle me-2"></i>
                        <strong>Success!</strong> ${type} export initiated.
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                `);
                
                $('body').append(message);
                
                // Auto-remove after 3 seconds
                setTimeout(() => {
                    message.fadeOut(300, function() {
                        $(this).remove();
                    });
                }, 3000);
            }
        });
    </script>
</body>
</html>
