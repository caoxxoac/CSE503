document.getElementById("logout").addEventListener("click", logoutAjax, false);

function logoutAjax(event) {
  $.ajax({
    type: "POST",
    url: "logout.php",
    success: function(data){
      var jsonData = JSON.parse(data);
      var msg = jsonData.message;
      logoutFormat();
      var refresh = document.createElement("script");
      refresh.src = "getCalendarDate.js";
      document.body.appendChild(refresh);
      alert(msg);
    }
  });
}

function logoutFormat(){
  document.getElementById("eventCreate").style.display = "none";
  document.getElementById("eventUpdate").style.display = "none";
  document.getElementById("eventDelete").style.display = "none";
  document.getElementById("loginPart").style.display = "block";
  document.getElementById("registerPart").style.display = "block";
  document.getElementById("logoutPart").style.display = "none";
  document.getElementById("passwordChange").style.display = "none";
  document.getElementById("eventShare").style.display = "none";
  document.getElementById("userPart").style.display = "none";
    document.getElementById("disableEvent").style.display = "none";
}
