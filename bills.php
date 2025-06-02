<?php include "db.php"; ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>All Bills - Dattakrupa Enterprise</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

  <style>
    body {
      background: url('./image/background.jpg') no-repeat center center fixed;
      background-size: cover;
    }
    .container {
      background: rgba(255, 255, 255, 0.95);
      border-radius: 10px;
      padding: 30px;
      box-shadow: 0 0 15px rgba(0,0,0,0.2);
    }
    .logo {
      height: 60px;
      margin-right: 15px;
    }
    .header {
      display: flex;
      align-items: center;
      justify-content: center;
      margin-bottom: 30px;
    }
    .header h1 {
      margin: 0;
      font-weight: bold;
    }
  </style>
</head>
<body class="p-4">
<a href="dashboards.php" class="btn btn-primary go-back">← Go Back</a>
<div class="container">
  <div class="header">
    <img src="./image/logoDE.jpeg" alt="Logo" class="logo">
    <h1>Dattakrupa Enterprise</h1>
  </div>

  <h3 class="mb-4">All Bills</h3>

  <form method="GET" class="row g-3 mb-4">
    <div class="col-md-4">
      <input type="text" name="customer" class="form-control" placeholder="Customer Name" value="<?= $_GET['customer'] ?? '' ?>">
    </div>
    
    <div class="col-md-3">
      <input type="date" name="from" class="form-control" value="<?= $_GET['from'] ?? '' ?>">
    </div>
    <div class="col-md-3">
      <input type="date" name="to" class="form-control" value="<?= $_GET['to'] ?? '' ?>">
    </div>
    <div class="col-md-2">
      <button type="submit" class="btn btn-primary w-100">Search</button>
    </div>
  </form>

  <?php
    $where = "WHERE 1";
    if (!empty($_GET['customer'])) {
        $customer = $conn->real_escape_string($_GET['customer']);
        $where .= " AND customer_name LIKE '%$customer%'";
    }
    if (!empty($_GET['from'])) {
        $from = $_GET['from'];
        $where .= " AND DATE(invoice_date) >= '$from'";
    }
    if (!empty($_GET['to'])) {
        $to = $_GET['to'];
        $where .= " AND DATE(invoice_date) <= '$to'";
    }

    $sql = "SELECT * FROM invoices $where ORDER BY invoice_date DESC";
    $result = $conn->query($sql);
  ?>

  <table class="table table-hover table-bordered bg-white">
    <thead class="table-dark">
      <tr>
        <th>Id</th>
        <th>Customer</th>
        <th>Phone</th>
        <th>Date</th>
        <th>Total (₹)</th>
        <th colspan="3">Action</th>
      </tr>
    </thead>
    <tbody>
      <?php if ($result->num_rows > 0): $i = 1; ?>
        <?php while($row = $result->fetch_assoc()): ?>
        <tr>
          <td><?= $i++ ?></td>
          <td><?= htmlspecialchars($row['customer_name']) ?></td>
          <td><?= htmlspecialchars($row['mobile_number']) ?></td>
          <td><?= date("d-M-Y H:i", strtotime($row['invoice_date'])) ?></td>
          <td><?= number_format($row['total_amount'], 2) ?></td>
          <td><a href="views_bill.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-info">View</a></td>
          <td><a href="edit_bills.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-warning">Edit</a></td>
          <td><a href="delete_bill.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this bill?')">Delete</a></td>
        </tr>
        <?php endwhile; ?>
      <?php else: ?>
        <tr><td colspan="7" class="text-center">No bills found.</td></tr>
      <?php endif; ?>
    </tbody>
  </table>
</div>
</body>
</html>