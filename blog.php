<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $cat_name; ?> - Taste of Namkeen</title>
    <?php include('head.php'); ?>
</head>
<body>
<div class="layer"></div>
<div id="preloader"><div data-loader="circle-side"></div></div>

<?php include('navbar.php'); ?>
<section class="process-section">
    <div class="container">
        <h2 class="text-center fw-bold text-warning mb-5">Namkeen Making Process</h2>

        <div class="row process-step">
            <div class="col-lg-6 content">
                <h3>1. Selection of Ingredients</h3>
                <p>Carefully selecting the highest quality ingredients such as besan, spices, and fresh edible oil.</p>
            </div>
            <div class="col-lg-6 text-center">
                <img src="img/machine1.jpg" alt="Ingredient Machine" class="process-img">
            </div>
        </div>

        <div class="row process-step flex-row-reverse">
            <div class="col-lg-6 content">
                <h3>2. Mixing the Dough</h3>
                <p>Ingredients are mixed into a uniform dough using automated machines.</p>
            </div>
            <div class="col-lg-6 text-center">
                <img src="img/AdobeStock_1454426519_Preview.jpeg" alt="Mixing Machine" class="process-img">
            </div>
        </div>

        <div class="row process-step">
            <div class="col-lg-6 content">
                <h3>3. Frying the Namkeen</h3>
                <p>The dough is shaped and fried in fresh oil using modern fryers at controlled temperatures.</p>
            </div>
            <div class="col-lg-6 text-center">
                <img src="https://gungunwala.com/wp-content/uploads/2025/07/Bijnor-CNF.png" alt="Frying Machine" class="process-img">
            </div>
        </div>

        <div class="row process-step flex-row-reverse">
            <div class="col-lg-6 content">
                <h3>4. Seasoning and Cooling</h3>
                <p>Namkeen is seasoned and cooled naturally in temperature-controlled rooms.</p>
            </div>
            <div class="col-lg-6 text-center">
                <img src="img/AdobeStock_546145174_Preview.jpeg" alt="Seasoning Machine" class="process-img">
            </div>
        </div>

        <div class="row process-step">
            <div class="col-lg-6 content">
                <h3>5. Hygienic Packaging</h3>
                <p>Packed using fully automatic packaging machines in air-tight pouches.</p>
            </div>
            <div class="col-lg-6 text-center">
                <img src="img/machine5.jpg" alt="Packaging Machine" class="process-img">
            </div>
        </div>

    </div>
</section>
  <?php include('footer.php');?>
    <?php include('js.php');?>
</body>

</html>