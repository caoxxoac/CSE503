document.getElementById("change").addEventListener("click", changeAjax, false);

function changeAjax(event){
  var password1 = document.getElementById("password1").value;
  var password2 = document.getElementById("password2").value;

  $.ajax({
    type: "POST",
    url: "changePassword.php",
    data: {"password1": password1, "password2": password2},
    success: function(data){
      var jsonData = JSON.parse(data);
      var suc = jsonData.success;
      var msg = jsonData.message;
      if (suc == true){
        // todo
        alert(msg);
      }
      else{
        alert(msg);
      }
    },
  });
}
