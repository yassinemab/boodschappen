function logout() {
    var xhttp = new XMLHttpRequest();
    xhttp.open("POST", "/backend/login/logout.php", false);
    xhttp.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
            location.reload()
        }
    };
    xhttp.send();
}