var RegisterService = {
  init: function() {
    var token = localStorage.getItem("token");
    if (token){
      window.location.replace("index.html");
    }
    $('#register-form').validate({
      submitHandler: function(form) {
        var entity = Object.fromEntries((new FormData(form)).entries());
        RegisterService.register(entity);
      }
    });
  },
  register: function(entity) {
    $.ajax({
      url: 'rest/register',
      type: 'POST',
      data: JSON.stringify(entity),
      contentType: "application/json",
      dataType: "json",
      success: function(result) {

        console.log(result);
        alert("Confirmation email has been sent out.");
        localStorage.setItem("token", result.token);
        window.location.replace("login.html");
      },
      error: function(XMLHttpRequest, textStatus, errorThrown) {
        toastr.error(XMLHttpRequest.responseJSON.message);
      }
    });
  },
}
