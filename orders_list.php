<?php
$conn = new mysqli("localhost","root","","s4shopdb");
if($conn->connect_error) die("Connection failed: ".$conn->connect_error);

// Fetch orders with product names
$sql = "
    SELECT o.*, p.name AS product_name
    FROM orders o
    LEFT JOIN products p ON o.product_id = p.id
    ORDER BY o.created_at DESC
";
$result = $conn->query($sql);
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Orders List</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="p-4">
<div class="container">
<h3>Orders (latest first)</h3>
<table class="table table-striped table-bordered">
<thead>
<tr>
<th>#</th>
<th>Name</th>
<th>Address</th>
<th>Email</th>
<th>Phone</th>
<th>Product</th>
<th>Weight</th>
<th>Qty</th>
<th>Total</th>
<th>Created At</th>
</tr>
</thead>
<tbody>
<?php if($result && $result->num_rows>0): 
    while($row = $result->fetch_assoc()): ?>
<tr>
<td><?= htmlspecialchars($row['id']) ?></td>
<td><?= htmlspecialchars($row['name']) ?></td>
<td><?= htmlspecialchars($row['address']) ?></td>
<td><?= htmlspecialchars($row['email']) ?></td>
<td><?= htmlspecialchars($row['phone']) ?></td>
<td><?= htmlspecialchars($row['product_name'] ?? 'Unknown') ?></td>
<td><?= htmlspecialchars($row['weight']) ?></td>
<td><?= htmlspecialchars($row['quantity']) ?></td>
<td>₹ <?= htmlspecialchars($row['total']) ?></td>
<td><?= htmlspecialchars($row['created_at']) ?></td>
</tr>
<?php endwhile; else: ?>
<tr><td colspan="10" class="text-center">No orders found.</td></tr>
<?php endif; ?>
</tbody>
</table>
</div>
</body>
</html>
<?php $conn->close(); ?>
