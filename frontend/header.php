<div class="d-flex align-items-center p-2 row shadow">
    <div class="px-5 col-md-8 text-left logo"><img style="width: 150px; height: auto;" src="/assets/logo.png"></div>
    <div class="row col-md-4 header-content">
        <div class="col-md-3"><a href="">Item</a></div>
        <div class="col-md-3"><a href="">Item</a></div>
        <div class="col-md-3"><a href="">Item</a></div>
        <div class="col-md-3"><a href="">
                <?php if (isset($_SESSION['id'])) {
                    echo "<div class='profile'><div class='welcome-user'>Hello, " . $_SESSION['name'] . "</div><div class='profile-picture'></div></div>";
                } else {
                    echo "<div class='login'>Login</div>";
                }
                ?>
            </a></div>

    </div>
</div>