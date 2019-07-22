document.getElementById("share").addEventListener("click", function(event){
  shareEvent();
}, false);

function shareEvent(){
  var shareTitle = document.getElementById("shareTitle").value;
  var shareDate = document.getElementById("shareDate").value;
  var shareTime = document.getElementById("shareTime").value;
  var shareUser = document.getElementById("shareUser").value;

  var shareYear = shareDate.substring(0, 4);
  var shareMonth = shareDate.substring(5, 7);
  var shareDay = shareDate.substring(8, 10);

  if (shareTitle == "" || shareDate == "" || shareTime == "" || shareUser == ""){
    alert("Check all inputs again!");
    return false;
  }

  $.ajax({
    type: "POST",
    url: "shareEvent.php",
    data: {"shareTitle": shareTitle, "shareYear": shareYear, "shareMonth": shareMonth, "shareDay": shareDay, "shareTime": shareTime, "shareUser": shareUser},
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
