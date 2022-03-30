$(document).ready(function () {

  let login = document.getElementById("login");
  let password = document.getElementById("password");

  login.oninput = trimLogin;
  password.oninput = trimPass;

  function trimLogin() {
    login.value = login.value.trim();
  }

  function trimPass() {
    password.value = password.value.trim();
  }
  
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