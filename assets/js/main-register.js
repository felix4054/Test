$(document).ready(function () {

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
