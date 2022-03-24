<!DOCTYPE html>
<html lang="en">

<head>
    <?php include_once "../head.html"; ?>
    <script type="text/javascript" src="shopping-list.js"></script>
</head>

<?php
include_once "../../backend/config.php";
include '../../backend/isLoggedIn.php';

if (!isLoggedIn($conn)) header("location: /index.php");
?>

<body>
    <div class="header"></div>
    <div class="list-container mt-8">
        <div class="d-flex">
            <div class="col-md-9 list">
                <div class="secondary-title">Boodschappenlijst</div>
                <div class="products mt-4"></div>
            </div>
            <div class="ms-3 col-md-3 buy">
                <div class="secondary-title text-center">Informatie</div>
                <div class="list-info mt-4">
                </div>
                <div class="calculate submit mt-3 d-flex justify-content-center align-items-center" style="width: 100% !important">Bestel boodschappen</div>
            </div>
        </div>
    </div>
</body>

</html>