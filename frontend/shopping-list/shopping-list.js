$(function () {
    var xhttp = new XMLHttpRequest();
    xhttp.open("GET", "../../backend/shopping-list/shopping-list.php", false);
    xhttp.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
            list = JSON.parse(this.responseText)
            originalList = list
            document.querySelector(".amt-of-products").innerHTML = `<div class='secondary-title'>U heeft ${list.length} producten in uw winkelwagen</div>`
            parse(list)
        }
        else {
            // Ging fout, display 0 resultaten
        }
    }
})