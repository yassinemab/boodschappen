function parse(list) {
    for (item of list) {
        if (item["unitSize"].includes("gram")) item["unitSize"] = item["unitSize"].replace("gram", "g")
        if (item["unitSize"].includes("ca.")) item["unitSize"] = item["unitSize"].replace("ca.", "")
        var isHuismerk = item["title"].split(" ")[0] == "Huismerk"
        var html = document.querySelector(".product-results")
        html.innerHTML += `<div class="product-card ${isHuismerk ? "huismerk-card" : ""}">
                                <div class="product-image">
                                    <img class="test" src="${item["image_url"]}">
                                </div>
                                <div class="d-flex row align-items-center">
                                    <div class="col-md-8">
                                        <div class="product-title">${item["title"].length > 40 ? item["title"].slice(0, 40) + '...' : item["title"]}</div>
                                    </div>
                                    <div class="col-md-4 align-items-center">
                                        <div class="d-flex justify-content-end me-4"><span class="price-title">€${item["price"]}</span></div>
                                        <div class="d-flex justify-content-end me-4"><span class="tertiary-title">${item["unitSize"]}</span></div>
                                    </p>
                                </div>
                                <div class="d-flex row align-items-center">
                                    <div class="col-md-6">
                                        <div class="d-flex justify-content-start">
                                            <div class="product-card-add"><i class="fa-solid fa-plus"></i></div>
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
    var query = location.search.split("&")[0].split("=")[1].replace("+", " ")
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
                `${capitalizeFirstLetter(query)}<div class='tertiary-title'>${amntOfResults}</div>`
            parse(list)
        }
        else {
            // Ging fout, display 0 resultaten
        }
    };
    xhttp.send(JSON.stringify(data));

})