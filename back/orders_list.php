<?php
include('../config.php');

// ✅ Update Payment Status & Screenshot via AJAX
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_payment'])) {
    $id = $_POST['order_id'];
    $status = $_POST['payment_status'];
  // echo "<pre>";print_r($_POST);print_r($_FILES);exit;
    // File upload handling
    $screenshotPath = '';
    if (!empty($_FILES['payment_screenshot']['name'])) {
        $targetDir = "uploads/payments/";
        if (!is_dir($targetDir)) mkdir($targetDir, 0777, true);
        $fileName = time() . "_" . basename($_FILES['payment_screenshot']['name']);
        $targetFile = $targetDir . $fileName;

        if (move_uploaded_file($_FILES['payment_screenshot']['tmp_name'], $targetFile)) {
            $screenshotPath = $targetFile;
            $conn->query("UPDATE orders SET payment_screenshot='$screenshotPath' WHERE id=$id");
        }
    }

    // ✅ Update payment status
    $conn->query("UPDATE orders SET payment_status='$status' WHERE id=$id");

    echo "<script>
          setTimeout(function() {
              const modalBody = document.querySelector('#statusModal .modal-body');
              if (modalBody) {
                  modalBody.innerHTML = `
                      <div class='text-center p-4'>
                          <h4 class='text-success mb-2'>
                              <i class=\"bi bi-check-circle-fill\"></i> Payment Updated successfully!
                          </h4>
                          <p>Your changes have been saved.</p>
                      </div>
                  `;
              }

            // Show modal
            const modal = bootstrap.Modal.getOrCreateInstance(document.getElementById('statusModal'));
            modal.show();

            // Optional: redirect after 2 seconds (if needed)
            setTimeout(() => {
                window.location.href = 'orders_list.php'; // change to your desired page
            }, 2000);

        }, 500);
    </script>";
}

// Fetch all orders
$result = $conn->query("SELECT * FROM orders ORDER BY id DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Orders List</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  <?php include('head.php'); ?>
  <style>
    .content-wrapper { min-height: 100vh; background: #f8f9fa; padding: 20px; }
    table th, table td { vertical-align: middle; text-align: center; }
    .drop-zone { border: 2px dashed #ccc; border-radius: 10px; padding: 20px; text-align: center; cursor: pointer; transition: 0.3s; }
    .drop-zone.dragover { background-color: #e9f7ef; border-color: #28a745; }
    .drop-zone img { max-width: 100%; margin-top: 10px; border-radius: 8px; }
  </style>
</head>
<body>
  <?php include('navbar.php'); ?>

  <div class="content-wrapper">
    <div class="container-fluid">
      <div class="d-flex justify-content-between align-items-center mb-3">
        <h2 class="mb-0">🛒 Orders List</h2>
      </div>

      <div class="card shadow">
        <div class="card-header bg-dark text-white">
          <i class="fa fa-shopping-cart"></i> Orders
        </div>
        <div class="card-body p-0">
          <div class="table-responsive">
            <table class="table table-bordered table-hover mb-0">
              <thead class="table-dark">
                <tr>
                  <th>#</th>
                  <th>Customer Name</th>
                  <th>Referral Code</th>
                  <th>Product ID</th>
                  <th>Size</th>
                  <th>Qty</th>
                  <th>Total (₹)</th>
                  <th>Payment Status</th>
                  <th>Screenshot</th>
                  <th>Date</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody id="ordersTable">
                <?php if($result->num_rows > 0) {
                  $i=1;
                  while($row = $result->fetch_assoc()){ ?>
                    <tr id="row-<?= $row['id'] ?>">
                      <td><?= $i++ ?></td>
                      <td><?= htmlspecialchars($row['name']) ?></td>
                      <td><?= htmlspecialchars($row['referral_code'] ?? '—') ?></td>
                      <td><?= htmlspecialchars($row['product_id']) ?></td>
                      <td><?= htmlspecialchars($row['size']) ?></td>
                      <td><?= $row['quantity'] ?></td>
                      <td>₹ <?= number_format($row['total'],2) ?></td>
                      <td>
                        <?php
                          $status = ucfirst($row['payment_status'] ?? 'Pending');
                          $badgeClass = $status === 'Received' ? 'success' : ($status === 'Cancelled' ? 'danger' : 'warning');
                        ?>
                        <span class="badge bg-<?= $badgeClass ?>" id="status-<?= $row['id'] ?>"><?= $status ?></span>
                      </td>
                      <td>
                        <?php if(!empty($row['payment_screenshot'])) { ?>
                          <a href="<?= $row['payment_screenshot'] ?>" target="_blank">View</a>
                        <?php } else { echo '<span class="text-muted">No Proof</span>'; } ?>
                      </td>
                      <td><?= $row['created_at'] ?? '' ?></td>
                      <td>
                        <button class="btn btn-sm btn-primary editBtn"
                          data-id="<?= $row['id'] ?>"
                          data-status="<?= $row['payment_status'] ?? 'Pending' ?>"
                          data-bs-toggle="modal" data-bs-target="#paymentModal">
                          Edit
                        </button>
                      </td>
                    </tr>
                <?php } } else { ?>
                    <tr><td colspan="11" class="text-center text-muted">No orders found</td></tr>
                <?php } ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- ✅ Edit Payment Modal -->
  <div class="modal fade" id="paymentModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
      <form id="updateForm" method="POST" enctype="multipart/form-data" class="modal-content">
        <div class="modal-header bg-dark text-white">
          <h5 class="modal-title">Update Payment</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <input type="hidden" name="order_id" id="order_id">
          <div class="mb-3">
            <label class="form-label">Payment Status</label>
            <select name="payment_status" id="payment_status" class="form-select" required>
              <option value="Pending">Pending</option>
              <option value="Paid">Paid</option>
              <option value="Failed">Failed</option>
              <option value="Refunded">Refunded</option>
            </select>
          </div>

          <div class="mb-3">
            <label class="form-label">Upload Payment Screenshot</label>
            <div class="drop-zone" id="dropZone">
              <p>Drag & Drop or Click to Upload</p>
              <input type="file" name="payment_screenshot" id="payment_screenshot" class="form-control" hidden accept="image/*">
              <img id="previewImg" src="#" alt="" style="display:none;">
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" name="update_payment" class="btn btn-success">Update</button>
        </div>
      </form>
    </div>
  </div>
 <div class="modal fade" id="statusModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
            <div class="modal-body text-center p-4">
                <!-- Dynamic content will appear here -->
            </div>
            </div>
        </div>
    </div>

  <script>
    // Fill modal with order details
    document.querySelectorAll('.editBtn').forEach(btn => {
      btn.addEventListener('click', () => {
        document.getElementById('order_id').value = btn.dataset.id;
        document.getElementById('payment_status').value = btn.dataset.status;
      });
    });

    // ✅ AJAX form submission for live update
    // document.getElementById('updateForm').addEventListener('submit', async (e) => {
    //   e.preventDefault();
    //   const formData = new FormData(e.target);
    //   const response = await fetch("", { method: "POST", body: formData });
    //   const result = await response.json();

    //   if (result.success) {
    //     const id = formData.get('order_id');
    //     const status = result.status; // Already capitalized
    //     const badge = document.getElementById('status-' + id);

    //     // Update badge text & color
    //     badge.textContent = status;
    //     badge.className = 'badge bg-' + (status === 'Received' ? 'success' : (status === 'Cancelled' ? 'danger' : 'warning'));

    //     // Hide modal
    //     const modal = bootstrap.Modal.getInstance(document.getElementById('paymentModal'));
    //     modal.hide();
    //   }
    // });

    // ✅ Drag & Drop Upload
    const dropZone = document.getElementById("dropZone");
    const fileInput = document.getElementById("payment_screenshot");
    const previewImg = document.getElementById("previewImg");

    dropZone.addEventListener("click", () => fileInput.click());
    fileInput.addEventListener("change", handleFile);

    dropZone.addEventListener("dragover", e => {
      e.preventDefault();
      dropZone.classList.add("dragover");
    });
    dropZone.addEventListener("dragleave", () => dropZone.classList.remove("dragover"));
    dropZone.addEventListener("drop", e => {
      e.preventDefault();
      dropZone.classList.remove("dragover");
      fileInput.files = e.dataTransfer.files;
      handleFile();
    });

    function handleFile() {
      const file = fileInput.files[0];
      if (file) {
        const reader = new FileReader();
        reader.onload = e => {
          previewImg.src = e.target.result;
          previewImg.style.display = "block";
        };
        reader.readAsDataURL(file);
      }
    }
  </script>

</body>
</html>
