function parse(data, query) {
    var products = JSON.parse(data)['products']
    document.querySelector(".primary-title").innerHTML = `${capitalizeFirstLetter(query)}<div class='tertiary-title'>${products.length} resultaten</div>`
    for (var product of products) {
        // console.log(product)
        if (!product['orderAvailabilityStatus'] == 'IN_ASSORTMENT') continue
        var title = product['title']
        replace_title = title.length > 35 ? title.slice(0, 35) + '...' : title
        var image_url = product['images'][2]['url']
        var description = product['descriptionHighlights']
        var unitSize = product['salesUnitSize']
        if (unitSize.includes("gram")) unitSize = unitSize.replace("gram", "g")
        if (unitSize.includes("ca.")) unitSize = unitSize.replace("ca.", "")
        var price = product['priceBeforeBonus']
        var html = document.querySelector(".product-results")
        html.innerHTML += `<div class="product-card">
                                <div class="product-image">
                                    <img class="test" src="${image_url}">
                                </div>
                                <div class="d-flex row align-items-center">
                                    <div class="col-md-8">
                                        <div class="product-title">${replace_title}</div>
                                    </div>
                                    <div class="col-md-4 align-items-center">
                                        <div class="d-flex justify-content-end me-4"><span class="price-title">€${price}</span></div>
                                        <div class="d-flex justify-content-end me-4"><span class="tertiary-title">${unitSize}</span></div>
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
        // console.log(product)
    }

    // document.querySelector("body").innerHTML += data
    // var webshops = data.split("products")
    // var products = []
    // for(var i = 1; i < webshops.length; i++) {
    //     products.push(webshops[i].split("'id':"))
    // }
    // console.log(products)
    // for(var i = 0; i < products.length; i++) {
    //     //Split hier de producten en stop de benodigde data in de db
    // }
    // Display product
    // Group by id? -> uitzoeken of id uniek is
}

function capitalizeFirstLetter(string) {
    return string.charAt(0).toUpperCase() + string.slice(1);
}

$(function () {
    var regex = /[^A-Za-z\s]+/
    var query = window.location.href.split("?")[1].split("=")[1].replace("+", " ")
    var xhttp = new XMLHttpRequest();
    xhttp.open("POST", "../../backend/web/scraping.php", false);
    var data = { 'query': query }
    xhttp.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
            console.log(this.responseText)
            parse(this.responseText, query)
        }
    };
    xhttp.send(JSON.stringify(data));
})