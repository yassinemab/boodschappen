function all(iterable) {
    for (var index = 0; index < iterable.length; index++) {
        if (!iterable[index]) return false;
    }
    return true;
}

var valid = [false, false, false, false, false]

function validateName(name) {
    var xhttp = new XMLHttpRequest();
    xhttp.open("POST", "/backend/validation/name.php", false);
    var data = { 'name': name.value }
    xhttp.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
            var error = document.getElementsByClassName("error")[0]
            if (this.responseText != '') {
                error.style.display = "block"
                error.innerHTML = this.responseText
                name.style.border = "2px solid red";
                valid[0] = false
            }
            else {
                error.style.display = "none"
                name.style.border = "2px solid green";
                valid[0] = true
            }
        }
    };
    xhttp.send(JSON.stringify(data));
}

function validateSurname(surname) {
    var xhttp = new XMLHttpRequest();
    xhttp.open("POST", "/backend/validation/name.php", false);
    var data = { 'name': surname.value }
    xhttp.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
            var error = document.getElementsByClassName("error")[1]
            if (this.responseText != '') {
                error.style.display = "block"
                error.innerHTML = this.responseText
                surname.style.border = "2px solid red";
                valid[1] = false
            }
            else {
                error.style.display = "none"
                surname.style.border = "2px solid green";
                valid[1] = true
            }
        }
    };
    xhttp.send(JSON.stringify(data));
}

function validateEmail(email) {
    var xhttp = new XMLHttpRequest();
    xhttp.open("POST", "/backend/validation/email.php", false);
    var data = { 'email': email.value }
    xhttp.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
            var error = document.getElementsByClassName("error")[2]
            if (this.responseText != '') {
                success = false
                error.style.display = "block"
                error.innerHTML = this.responseText
                email.style.border = "2px solid red";
                valid[2] = false
            }
            else {
                error.style.display = "none"
                email.style.border = "2px solid green";
                valid[2] = true
            }
        }
    };
    xhttp.send(JSON.stringify(data));
}

function validatePassword(password) {
    var xhttp = new XMLHttpRequest();
    xhttp.open("POST", "/backend/validation/password.php", false);
    var data = { 'password': password.value }
    xhttp.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
            var error = document.getElementsByClassName("error")[3]
            if (this.responseText != '') {
                success = false
                error.style.display = "block"
                error.innerHTML = this.responseText
                password.style.border = "2px solid red";
                valid[3] = false
            }
            else {
                valid[3] = true
                error.style.display = "none"
                password.style.border = "2px solid green";
            }
        }
    };
    xhttp.send(JSON.stringify(data));
}

function validateConfirmPassword() {
    var form = document.querySelector("form")
    var error = document.getElementsByClassName("error")[4]
    if (form[3].value !== form[4].value) {
        error.style.display = "block"
        error.innerHTML = "Wachtwoorden komen niet overeen"
        form[4].style.border = "2px solid red";
        valid[4] = false
    }
    else if (form[3].value == '') {
        error.style.display = "block"
        error.innerHTML = "Wachtwoord herhalen is verplicht"
        form[4].style.border = "2px solid red";
        valid[4] = false
    }
    else {
        valid[4] = true
        error.style.display = "none"
        form[4].style.border = "2px solid green";
    }
}

function checkForm() {
    console.log(valid)
    if (all(valid)) {
        $('.submit').removeAttr('disabled');
    } else {
        $('.submit').attr('disabled', 'disabled');
    }
}