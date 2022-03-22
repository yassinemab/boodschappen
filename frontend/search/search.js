function parse(list) {
    for (item of list) {
        if (item["unitSize"].includes("gram")) item["unitSize"] = item["unitSize"].replace("gram", "g")
        if (item["unitSize"].includes("ca.")) item["unitSize"] = item["unitSize"].replace("ca.", "")
        var isHuismerk = item["title"].split(" ")[0] == "Huismerk"
        var html = document.querySelector(".product-results")
        html.innerHTML += `<div class="product-card ${isHuismerk ? "huismerk-card" : ""}">
                                <div class="product-image">
                                    <img src="${item["image_url"]}">
                                </div>
                                <div class="d-flex row align-items-center">
                                    <div class="col-md-8">
                                        <div class="product-title">${item["title"].length > 35 ? item["title"].slice(0, 35) + '...' : item["title"]}</div>
                                    </div>
                                    <div class="col-md-4 align-items-center">
                                        <div class="d-flex justify-content-end me-4"><span class="price-title">€${item["price"]}</span></div>
                                        <div class="d-flex justify-content-end me-4"><span class="tertiary-title">${item["unitSize"]}</span></div>
                                    </p>
                                </div>
                                <div class="d-flex row align-items-center">
                                    <div class="col-md-6">
                                        <div class="d-flex justify-content-start">
                                            <div id="${item["id"]}" class="product-card-add" onclick="addToCart(${item["id"]})"><i class="fa-solid fa-plus"></i></div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="d-flex justify-content-end">
                                            <div class="product-card-save"><i class="fa-regular fa-bookmark"></i></div>
                                        </div>
                                    </div>
                            </div>`
    }
}

function addToCart(id) {
    var xhttp = new XMLHttpRequest();
    xhttp.open("POST", "../../backend/shopping-list/addToCart.php", false);
    var data = { 'id': id }
    xhttp.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
            if (this.responseText == "LOGIN") {
                alert("login pls")
                // Open modal to login
            }
            else if (this.responseText == "SUCCESS") {
                // Change amount of items in cart left, maybe do some weird ass animation
            }
        }
    }
    xhttp.send(JSON.stringify(data));
}

function showAnimation() {

}


function sortAlphabetically(a, b) {
    if (a["title"] === b["title"]) {
        return 0;
    }
    else {
        return (a["title"] < b["title"]) ? -1 : 1;
    }
}

function sortPriceAscending(a, b) {
    if (a["price"] === b["price"]) {
        return 0;
    }
    else {
        return (a["price"] < b["price"]) ? -1 : 1;
    }
}

function sortPriceDescending(a, b) {
    if (a["price"] === b["price"]) {
        return 0;
    }
    else {
        return (a["price"] < b["price"]) ? 1 : -1;
    }
}

function sortList() {
    var type = this.options[this.selectedIndex].value
    if (type === "1") {
        list.sort(sortAlphabetically)
    } else if (type === "2") {
        list.sort(sortPriceAscending)
    } else if (type === "3") {
        list.sort(sortPriceDescending)
    } else if (type == "4") {
        list = originalList
        console.log(list)
    }
    document.querySelector(".product-results").innerHTML = ""
    parse(list)
}

function capitalizeFirstLetter(string) {
    return string.charAt(0).toUpperCase() + string.slice(1);
}

var list = []
var originalList = []

$(function () {
    var query = location.search.split("&")[0].split("=")[1].replace(/\+/g, " ")
    var page = location.search.split("&")[1]
    if (page !== undefined) page = page.split("=")[1].replace("+", " ")
    else page = 1

    var xhttp = new XMLHttpRequest();
    xhttp.open("POST", "../../backend/web/products.php", false);
    var data = { 'query': query, 'page': page }
    xhttp.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
            list = JSON.parse(this.responseText)
            originalList = list
            var amntOfResults = list.pop()
            document.querySelector(".primary-title").innerHTML =
                `${capitalizeFirstLetter(query)}<div class='tertiary-title'>${amntOfResults}</div>
 `
            parse(list)
        }
        else {
            // Ging fout, display 0 resultaten
        }
    };
    xhttp.send(JSON.stringify(data));

    $(".product-card-add").on("click", function () {
        var $btn = $(this);
        var $li = $btn.closest('.product-card');
        var btnOffsetTop = $btn.offset().top;
        var btnOffsetRight = window.innerWidth - $btn.offset().left;
        $li.find('img')
            .clone()
            .css({ top: btnOffsetTop, right: btnOffsetRight })
            .addClass("zoom")
            .appendTo($li);

        setTimeout(function () {
            $(".zoom").remove();
        }, 1000);
    });

})
