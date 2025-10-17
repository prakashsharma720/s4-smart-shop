<?php
$conn = new mysqli("localhost", "root", "", "s4shopdb");
$result = $conn->query("SELECT * FROM categories");
?>
<h2>All Categories</h2>
<?php while($row = $result->fetch_assoc()) { ?>
    <div style="margin-bottom:20px;">
        <img src="uploads/<?php echo $row['image']; ?>" width="100"><br>
        <?php echo $row['name']; ?>
    </div>
<?php } ?>
