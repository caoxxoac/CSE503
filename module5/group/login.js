document.getElementById("login").addEventListener("click", loginAjax, false);

function loginAjax(event){
  var username = document.getElementById("username").value;
  var password = document.getElementById("password").value;

  $.ajax({
    type: "POST",
    url: "login.php",
    data: {"username": username, "password": password},
    success: function(data){
      var jsonData = JSON.parse(data);
      var suc = jsonData.success;
      var msg = jsonData.message;
      var username = jsonData.username;
      if (suc == true){
        // todo
        loginFormat();
        document.getElementById("user").innerHTML = "Hi "+username+"!";
        var displayEvent = document.createElement("script");
        displayEvent.src = "showEvent.js";
        document.body.appendChild(displayEvent);
        alert(msg);
      }
      else{
        alert(msg);
      }
    },

  });
}

function loginFormat(){
  document.getElementById("eventCreate").style.display = "block";
  document.getElementById("eventUpdate").style.display = "block";
  document.getElementById("eventDelete").style.display = "block";
  document.getElementById("loginPart").style.display = "none";
  document.getElementById("registerPart").style.display = "none";
  document.getElementById("logoutPart").style.display = "block";
  document.getElementById("passwordChange").style.display = "block";
  document.getElementById("eventShare").style.display = "block";
  document.getElementById("userPart").style.display = "block";
  document.getElementById("disableEvent").style.display = "block";
}
