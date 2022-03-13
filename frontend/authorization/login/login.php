<!DOCTYPE html>
<html lang="en">

<head>
    <?php include_once "../../head.html"; ?>
</head>

<body>
    <div class="main-container">
        <div class="mt-8 main">
            <div class="card row px-15">
                <div class="my-3 title">Inloggen</div>
                <?php if (isset($_GET['error'])) echo "<div class='col-12 text-center general-error'>" . urldecode($_GET['error']) . "</div>"; ?>
                <form class="p-2 register-form" action="../../../backend/login/login.php" method="POST" enctype=”multipart/form-data”>
                    <div class="sub-title">Email-adres</div>
                    <input class="col-12 input" type="text" name="email" placeholder="Email">
                    <div class="col-12 mb-3">
                    </div>
                    <div class="sub-title">Wachtwoord</div>
                    <input class="col-12 input" type="password" name="password" placeholder="Wachtwoord">
                    <div class="col-12 mb-3">
                        <div class="submit-container mt-5">
                            <button class="submit mb-5" type="submit" name="submit">Inloggen</button>
                        </div>
                </form>
            </div>
        </div>
    </div>
</body>

</html>