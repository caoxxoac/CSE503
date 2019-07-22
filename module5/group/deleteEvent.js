document.getElementById("delete").addEventListener("click", function(event){
  deleteEvent();
}, false);

function deleteEvent(){
  var eventTitle = document.getElementById("deleteTitle").value;
  var eventDate = document.getElementById("deleteDate").value;
  var eventTime = document.getElementById("deleteTime").value;

  var eventYear = eventDate.substring(0, 4);
  var eventMonth = eventDate.substring(5, 7);
  var eventDay = eventDate.substring(8, 10);

  $.ajax({
    type: "POST",
    url: "deleteEvent.php",
    data: {"deleteTitle": eventTitle, "deleteYear": eventYear, "deleteMonth": eventMonth, "deleteDay": eventDay, "deleteTime": eventTime},
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
