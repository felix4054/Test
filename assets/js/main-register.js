$(document).ready(function () {

  let login = document.getElementById("login");
  let password = document.getElementById("password");
  let confirm_password = document.getElementById("confirm_password");
  let email = document.getElementById("email");
  let name = document.getElementById("name");

  login.oninput = trimLogin;
  password.oninput = trimPass;
  confirm_password.oninput = trimCPass;
  email.oninput = trimEmail;
  name.oninput = trimName;

  function trimLogin() {
    login.value = login.value.trim();
  }

  function trimPass() {
    password.value = password.value.trim();
  }

  function trimCPass() {
    confirm_password.value = confirm_password.value.trim();
  }

  function trimEmail() {
    email.value = email.value.trim();
  }

  function trimName() {
    name.value = name.value.trim();
  }
  // обработка кнопки регистрации
  $("#register-button").click(function (event) {
    event.preventDefault();
    $.ajax({
      type: "POST",
      url: "/user/register",
      dataType: "json",
      data: $("#register_form").serialize(),
      success: function (data) {
        if (data.success) {
          // поздравляем с регистрацией.
          $("#register-errors").html(data.message).hide();
          $("#register-success").html(data.message).show();
          // очищаем форму
          $("#register_form")[0].reset();
        } else {
          // выводим ошибку
          $("#register-errors").html(data.message).show();
          $("#register-success").html(data.message).hide();
        }
      },
    });
  });

});
