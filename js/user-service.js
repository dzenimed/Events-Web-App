var UserService = {
  init: function() {
    var token = localStorage.getItem("token");
    if (token) {
      window.location.replace("index.html");
    }
    $('#login-form').validate({
      submitHandler: function(form) {
        var entity = Object.fromEntries((new FormData(form)).entries());
        UserService.login(entity);
      }
    });
  },
  login: function(entity) {
    $.ajax({
      url: 'rest/login',
      type: 'POST',
      data: JSON.stringify(entity),
      contentType: "application/json",
      dataType: "json",
      success: function(result) {
        console.log(result);
        localStorage.setItem("token", result.token);
        window.location.replace("index.html");
      },
      error: function(XMLHttpRequest, textStatus, errorThrown) {
        toastr.error(XMLHttpRequest.responseJSON.message);
      }
    });
  },

  logout: function() {
    // setTimeout(()=> {window.location.reload(true); });
    localStorage.clear();
    const btn = document.getElementById('logout');
    btn.style.display = 'none';

    const my_ev = document.getElementById('my_events');
    my_ev.style.display = 'none';
    const login_link = document.getElementById('login_link');
    login_link.style.display = 'block';
  },

  user_info: function() {
    $.ajax({
         url: 'rest/user/' + id,
         type: 'GET',
         data: JSON.stringify(body),
         contentType: "application/json",
         beforeSend: function(xhr){
           if (localStorage.getItem("token")){
             xhr.setRequestHeader('Authentication', localStorage.getItem("token"));
           }
         },
         success: function(data) {
           success(data);
           console.log(data);
         //   $("#name-greet").html("");
         //   var html = "";
         //     html += `
         //     <div class="col-lg-3">
         //     <p><strong>`+ data.name +`</strong><input id="username" type="text" disabled /></p>=
         //     <div id="greeting"></div>
         //     </div>`;
         //
         // $("#name-greet").html(html);
         },
         error: function(XMLHttpRequest, textStatus, errorThrown) {
           toastr.error(XMLHttpRequest.responseJSON.message);
         }
      });
  }
}
