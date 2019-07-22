document.getElementById("edit").addEventListener("click", function(event){
  editEvent();
}, false);

function editEvent(){
  var originalTitle = document.getElementById("originalTitle").value;
  var originalDate = document.getElementById("originalDate").value;
  var originalTime = document.getElementById("originalTime").value;

  var updateTitle = document.getElementById("updateTitle").value;
  var updateDate = document.getElementById("updateDate").value;
  var updateTime = document.getElementById("updateTime").value;

  var originalYear = originalDate.substring(0, 4);
  var originalMonth = originalDate.substring(5, 7);
  var originalDay = originalDate.substring(8, 10);

  var updateYear = updateDate.substring(0, 4);
  var updateMonth = updateDate.substring(5, 7);
  var updateDay = updateDate.substring(8, 10);

  if (originalTitle == "" || originalDate == "" || originalTime == "" || updateTitle == "" || updateDate == "" || updateTime == ""){
    alert("Check all inputs again!");
    return false;
  }

  $.ajax({
    type: "POST",
    url: "editEvent.php",
    data: {"originalTitle": originalTitle, "originalYear": originalYear, "originalMonth": originalMonth, "originalDay": originalDay, "originalTime": originalTime,
    "updateTitle": updateTitle, "updateYear": updateYear, "updateMonth": updateMonth, "updateDay": updateDay, "updateTime": updateTime},
    success: function(data){
      var jsonData = JSON.parse(data);
      var suc = jsonData.success;
      var msg = jsonData.message;
      if (suc == true){
        var displayEvent = document.createElement("script");
        displayEvent.src = "showEvent.js";
        document.body.appendChild(displayEvent);
        alert(msg);
        // todo
      }
      else{
        alert(msg);
      }
    }
  });
}
