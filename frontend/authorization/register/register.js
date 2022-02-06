function all(iterable) {
    for (var index = 0; index < iterable.length; index++) {
        if (!iterable[index]) return false;
    }
    return true;
}

function validateName(name) {
    var success = false
    var xhttp = new XMLHttpRequest();
    xhttp.open("POST", "/backend/validation/name.php", false);
    var data = { 'name': name.value }
    xhttp.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
            var error = name.name == "surname" ? document.getElementsByClassName("error")[1] : document.getElementsByClassName("error")[0]
            if (this.responseText != '') {
                var success = false
                error.style.display = "block"
                error.innerHTML = this.responseText
                name.style.border = "2px solid red";
            }
            else {
                success = true
                error.style.display = "none"
                name.style.border = "2px solid green";
            }
        }
    };
    xhttp.send(JSON.stringify(data));
    return success
}

function validateEmail(email) {


    var success = false
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
            }
            else {
                success = true
                error.style.display = "none"
                email.style.border = "2px solid green";
            }
        }
    };
    xhttp.send(JSON.stringify(data));
    return success
}

function validatePassword(password) {
    var success = false
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
            }
            else {
                success = true
                error.style.display = "none"
                password.style.border = "2px solid green";
            }
        }
    };
    xhttp.send(JSON.stringify(data));
    return success
}

function validateConfirmPassword() {
    var form = document.querySelector("form")
    var error = document.getElementsByClassName("error")[4]
    if (form[3].value !== form[4].value) {
        error.style.display = "block"
        error.innerHTML = "Wachtwoorden komen niet overeen"
        form[4].style.border = "2px solid red";
        return false
    }
    error.style.display = "none"
    form[4].style.border = "2px solid green";
    return true
}

function checkForm() {
    var form = document.querySelector("form")
    var arr = [validateName(form[0].value),
    validateName(form[1].value),
    validateEmail(form[2].value),
    validatePassword(form[3].value),
    validateConfirmPassword(form[4].value)]
    if (all(arr)) {
        $('.submit').removeAttr('disabled');
    } else {
        $('.submit').attr('disabled', 'disabled');
    }
}

$(function () {

})