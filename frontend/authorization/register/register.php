<!DOCTYPE html>
<html lang="en">

<head>
    <?php include_once "../../head.html"; ?>
</head>

<body>
    <div class="main-container">
        <div class="mt-8 main">
            <div class="card row px-15">
                <div class="my-3 title">Registreren</div>
                <?php if (isset($_GET['error'])) echo "<div class='col-12 text-center general-error'>" . urldecode($_GET['error']) . "</div>"; ?>
                <form class="p-2 register-form" action="/backend/register/register.php" method="POST" enctype=”multipart/form-data”>
                    <div class="col-12 d-flex justify-content-between">
                        <div class="w-49 sub-title">Voornaam</div>
                        <div class="w-49 sub-title">Achternaam</div>
                    </div>
                    <div class="col-12 d-flex justify-content-between">
                        <input class="w-49 input" onfocusout="validateName(this); checkForm()" type="text" name="name" placeholder="Voornaam">
                        <input class="w-49 input" onfocusout="validateSurname(this); checkForm()" type="text" name="surname" placeholder="Achternaam">
                    </div>
                    <div class="col-12 mb-3 d-flex justify-content-between">
                        <div class="w-49">
                            <div class="error"></div>
                        </div>
                        <div class="w-49">
                            <div class="error"></div>
                        </div>
                    </div>
                    <div class="sub-title">Email-adres</div>
                    <input class="col-12 input" onfocusout="validateEmail(this); checkForm()" type="text" name="email" placeholder="Email">
                    <div class="col-12 mb-3">
                        <div class="error"></div>
                    </div>
                    <div class="sub-title">Wachtwoord</div>
                    <input class="col-12 input" onfocusout="validatePassword(this); checkForm()" type="password" name="password" placeholder="Wachtwoord">
                    <div class="col-12 mb-3">
                        <div class="error"></div>
                    </div>
                    <div class="sub-title">Wachtwoord herhalen</div>
                    <input class="col-12 input" onfocusout="validateConfirmPassword(); checkForm()" type="password" name="confirmPassword" placeholder="Wachtwoord herhalen">
                    <div class="col-12 mb-3">
                        <div class="error"></div>
                    </div>
                    <div class="terms">Door te registreren gaat u akkoord met onze <a href="#">Servicevoorwaarden</a>.</div>
                    <div class="submit-container mt-5">
                        <button class="submit mb-5" type="submit" name="submit" disabled>Maak een gratis account</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script type="text/javascript" src="register.js"></script>
</body>

</html>