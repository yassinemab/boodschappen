function parse(list) {
    var maxPrice = 0
    var html = document.querySelector(".products")
    for (product of list) {
        maxPrice += parseFloat(product["price"])
        if (product["description"].length > 50) product["description"] = product["description"].slice(0, 80) + '...'
        html.innerHTML += `<div class="product-grid mb-2">
        <div class="d-flex content">
            <div class="col-md-2 d-flex align-items-center product-grid-image"><img src="${product["image_url"]}"></div>
            <div class="col-md-7 product-info">
            <div class="col-md-10 price-title">${product["title"]}</div>
            <div class="col-md-2 price-title color-blue">${parseFloat(product["price"]).toFixed(2)}</div>
                <div class="col-md-2 tertiary-title">${product["unitSize"]}</div>
                <!-- <div class="col-md-8 quartenary-title">${product["description"]}</div> -->
            </div>
            <div class="col-md-3 d-flex change-status align-items-center justify-content-end pe-5">
                <div class="remove-product d-flex">
                    <div onclick="removeFromCart(${product["id"]})" class="product-card-remove" style="margin-right: 20px !important; border-radius: 5px !important;"><i class="fa-solid fa-minus"></i></div>
                </div>
                <input id="${product["id"]}" class="add-amount" value="${product["amount"]}">
                <div class="add-product d-flex">
                    <div onclick="addToCart(${product["id"]})" class="product-card-add" style="border-radius: 5px !important;"><i class="fa-solid fa-plus"></i></div>
                </div>
            </div>
        </div>
    </div>`
    }
    return maxPrice.toFixed(2)
}

function removeFromCart(id) {
    var xhttp = new XMLHttpRequest()
    xhttp.open("DELETE", "../../backend/shopping-list/removeFromCart.php", false);
    var data = { 'id': id }
    xhttp.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
            if (this.responseText == "LOGIN") {
                alert("login pls")
            }
            else if (this.responseText != "INVALID") {
                var id = document.getElementById(this.responseText)
                console.log(id)
                id.value = parseInt(id.value) - 1
            }
        }
    }
    xhttp.send(JSON.stringify(data));
}

function setValue(id) {
    var value = document.getElementById(id).value
    var xhttp = new XMLHttpRequest();
    xhttp.open("POST", "../../backend/shopping-list/addToCart.php", false);
    var data = { 'id': id, "value": value }
    xhttp.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
            if (this.responseText == "LOGIN") {
                alert("login pls")
            }
            else if (this.responseText != "INVALID") {
                var id = document.getElementById(this.responseText)
                console.log(id)
                id.value = parseInt(id.value) + 1
            }
        }
    }
    xhttp.send(JSON.stringify(data));
}

function addToCart(id) {
    var xhttp = new XMLHttpRequest();
    xhttp.open("POST", "../../backend/shopping-list/addToCart.php", false);
    var data = { 'id': id }
    xhttp.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
            if (this.responseText == "LOGIN") {
                alert("login pls")
            }
            else if (this.responseText != "INVALID") {
                var id = document.getElementById(this.responseText)
                console.log(id)
                id.value = parseInt(id.value) + 1
            }
        }
    }
    xhttp.send(JSON.stringify(data));
}

$(function () {
    var xhttp = new XMLHttpRequest();
    xhttp.open("GET", "../../backend/shopping-list/getShoppingList.php", false);
    xhttp.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
            var price = 0
            var amount = 0
            list = JSON.parse(this.responseText)
            var price = parse(list)
            var amount = 0
            for (product in list) {
                amount += product["amount"]
                console.log(product["amount"])
            }
            if(list.length == 0) {
                // Display leuke message met vul je boodschappenmandje ofzo
            }
            document.querySelector(".list-info").innerHTML += `<div class="d-flex amount-of-products"><div class="col-md-8 price-title">Aantal producten:</div><div class="d-flex col-md-4 price-title justify-content-end color-blue">${amount}</div></div>
            <div class="d-flex average-price mt-3"><div class="col-md-8 price-title">Gem. totale prijs:</div><div class="d-flex justify-content-end col-md-4 price-title color-blue">${price}</div></div>`
        }
        else {
            // Ging fout, display 0 resultaten
        }
    }
    xhttp.send();

    $(".product-card-add").on("click", function () {
        var $btn = $(this);
        var $li = $btn.closest('.product-grid');
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