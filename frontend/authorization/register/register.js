function validateName(name) {
    console.log(name.value)
    $.ajax({
        type: "POST",
        url: "/backend/register/validation/name.php",
        data: { 'name': name.value },
        contentType: "application/json; charset=utf-8",
        dataType: "json",
        success: function (data) {
            console.log(data);
        }
    });
}
