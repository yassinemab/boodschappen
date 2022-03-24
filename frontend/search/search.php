<!DOCTYPE html>
<html lang="en">

<head>
    <?php include_once "../head.html"; ?>
    <script type="text/javascript" src="search.js"></script>
</head>

<body>
    <div class="header"></div>
    <div class="loading-screen"><img class="loading" src="../../assets/loading.gif"></div>
    <div class="search-results">
        <div class="search-for-more">
            <div class="search-results-header">
                <img src="/assets/home-background1.jpg">
                <div class="centered">
                    <h1 class="white-title mb-4">Waar ben je naar op zoek?</h1>
                    <form class="d-flex justify-content-center" action="/frontend/search/search.php">
                        <span class="col-md-8 col-sm-6 pe-2 text-end">
                            <input class="search-bar-results" name="query" type="text" placeholder="Bijvoorbeeld Appelsap">
                        </span>
                        <span class="col-md-4 col-sm-6 text-start">
                            <button class="btn-md submit" type="submit"><i class="fa-solid fa-magnifying-glass"></i> Zoeken</button>
                        </span>
                    </form>
                    <div class="suggested-searches tertiary-title mt-4">Hier moeten suggestions komen</div>
                </div>
            </div>
        </div>
        <div class="main-container">

            <div class="d-flex">
                <div class="col-md-2">
                    <div class="mt-20">
                        <div class="mb-4">
                            <div class="filter-title">Categorieën</div>
                            <div class="items">
                                <div class="filter-item d-flex align-items-center"><input class="checkbox me-1" type="checkbox">Vlees</div>
                                <div class="filter-item d-flex align-items-center"><input class="checkbox me-1" type="checkbox">Brood</div>
                                <div class="filter-item d-flex align-items-center"><input class="checkbox me-1" type="checkbox">Eieren</div>
                                <div class="filter-item d-flex align-items-center"><input class="checkbox me-1" type="checkbox">Water</div>
                                <div class="filter-item d-flex align-items-center"><input class="checkbox me-1" type="checkbox">Pizzabeleg</div>
                                <div class="filter-item d-flex align-items-center"><input class="checkbox me-1" type="checkbox">Vlees</div>
                            </div>
                        </div>
                        <div class="mb-4">
                            <div class="filter-title">Dieet</div>
                            <div class="items">
                                <div class="filter-item d-flex align-items-center"><input class="checkbox me-1" type="checkbox">Vegetarisch</div>
                                <div class="filter-item d-flex align-items-center"><input class="checkbox me-1" type="checkbox">Vegan</div>
                                <div class="filter-item d-flex align-items-center"><input class="checkbox me-1" type="checkbox">Glutenvrij</div>
                                <div class="filter-item d-flex align-items-center"><input class="checkbox me-1" type="checkbox">Lactosevrij</div>
                            </div>
                        </div>
                        <div class="mb-4">
                            <div class="filter-title">Categorieën</div>
                            <div class="items">
                                <div class="filter-item d-flex align-items-center"><input class="checkbox me-1" type="checkbox">Vlees</div>
                                <div class="filter-item d-flex align-items-center"><input class="checkbox me-1" type="checkbox">Vlees</div>
                                <div class="filter-item d-flex align-items-center"><input class="checkbox me-1" type="checkbox">Vlees</div>
                                <div class="filter-item d-flex align-items-center"><input class="checkbox me-1" type="checkbox">Vlees</div>
                                <div class="filter-item d-flex align-items-center"><input class="checkbox me-1" type="checkbox">Vlees</div>
                                <div class="filter-item d-flex align-items-center"><input class="checkbox me-1" type="checkbox">Vlees</div>
                            </div>
                        </div>
                        <div class="mb-4">
                            <div class="filter-title">Categorieën</div>
                            <div class="items">
                                <div class="filter-item d-flex align-items-center"><input class="checkbox me-1" type="checkbox">Vlees</div>
                                <div class="filter-item d-flex align-items-center"><input class="checkbox me-1" type="checkbox">Vlees</div>
                                <div class="filter-item d-flex align-items-center"><input class="checkbox me-1" type="checkbox">Vlees</div>
                                <div class="filter-item d-flex align-items-center"><input class="checkbox me-1" type="checkbox">Vlees</div>
                                <div class="filter-item d-flex align-items-center"><input class="checkbox me-1" type="checkbox">Vlees</div>
                                <div class="filter-item d-flex align-items-center"><input class="checkbox me-1" type="checkbox">Vlees</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-10 text-start">
                    <div class="d-flex">
                        <div class="col-md-9 mt-5 ms-3 primary-title"></div>
                        <div class="col-md-3 d-flex sort align-items-end"><select class="sort" onchange="sortList.call(this)">
                                <option value="0">Sorteren op</option>
                                <option value="1">Alfabetische volgorde</option>
                                <option value="2">Prijs laag-hoog</option>
                                <option value="3">Prijs hoog-laag</option>
                                <option value="4">Relevantie</option>
                            </select>
                        </div>
                    </div>
                    <div class="product-results"></div>
                    <!-- <div class="pagination">
                    <div class="pages">
                        <div class="first">1</div>
                        <div class="previous"></div>
                        <div class="current"></div>
                        <div class="next"></div>
                        <div class="last"></div>
                    </div>
                </div> -->
                </div>
            </div>
        </div>
    </div>
    <div class="results"></div>
</body>

</html>