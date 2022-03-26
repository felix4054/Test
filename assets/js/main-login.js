$(document).ready(function () {
  // обработка кнопки авторизации
  $("#login-button").click(function (event) {
    event.preventDefault();
    $.ajax({
      type: "POST",
      url: "/user/login",
      dataType: "json",
      data: $("#login_form").serialize(),
      success: function (data) {
        if (data.success) {
          //перезагрузка страницы
          location.reload();
        } else {
          // выводим ошибку
          $("#login-errors").html(data.message).show();
        }
      },
    });
  });
});