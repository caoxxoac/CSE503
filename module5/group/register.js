document.getElementById("register").addEventListener("click", registerAjax, false);

function registerAjax(event){
  var username = document.getElementById("registerUsername").value;
  var password = document.getElementById("registerPassword").value;

  $.ajax({
    type: "POST",
    url: "register.php",
    data: {"username": username, "password": password},
    success: function(data){
      var jsonData = JSON.parse(data);
      var suc = jsonData.success;
      var msg = jsonData.message;
      if (suc == true){
        alert(msg);
        // todo
      }
      else{
        alert(msg);
      }
    }
  });
}
