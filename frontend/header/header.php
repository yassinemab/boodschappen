<div class="header header-shadow">
    <div class="main-container d-flex align-items-center">
        <div class="col-md-2 text-left logo"><a href="/"><img style="width: 60%; height: auto;" src="/assets/logo.png"></div></a>
        <div class="row col-6 d-flex align-items-center">
            <form action="/frontend/search/search.php">
                <input class="search-header" name="query" type="text" placeholder="Zoek een product..." required>
            </form>
            <!-- <div class="col-2"><a href=""><i class="circle-button fa-solid fa-magnifying-glass"></i></a></div> -->
        </div>
        <div class="row col-4 header-content align-items-center">
            <?php
            include_once "../../backend/config.php";
            include '../../backend/isLoggedIn.php';
            if (isLoggedIn($conn) == True) {
                echo "<div class='col-8'>
                        <a class='flex-column d-flex align-items center' href='/frontend/shopping-list/shopping-list.php'>
                            <i class='col-12 text-center fa-regular fa-basket-shopping'><div class='badge'>4</div></i>
                            <div class='mt-1 col-12 text-center header-subtext'>Winkelwagen</div>
                        </a>
                    </div>";
                echo "<div class='col-4'>
                            <a class='flex-column d-flex align-items-center' onclick='logout()'>
                                <i class='col-12 text-center fa-regular fa-user'></i>
                                <div class='mt-1 col-12 text-center header-subtext'>Profiel</div>
                            </a>
                     </div>";
            } else {
                echo "<div class='row text-end d-flex align-items-center'>
                        <a class='col-md-8 header-content' href='/frontend/authorization/register/register.php'>Registreren</a>
                        <a class='col-md-4' href='/frontend/authorization/login/login.php'>
                            <button class='d-flex align-items-center justify-content-center primary-button'><i class='col-md-3 text-start fa-solid fa-arrow-right-to-bracket'></i><span class='col-md-9'>Inloggen</span></button>
                        </a>
                    </div>";
            }
            ?>

        </div>
    </div>
</div>