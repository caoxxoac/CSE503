document.getElementById("create").addEventListener("click", function(event){
  createEvent();
}, false);

function createEvent(){
  var now = new Date();
  var nowYear = now.getFullYear();
  var nowMonth = now.getMonth();
  var nowDay = now.getDate();

  var eventTitle = document.getElementById("eventTitle").value;
  var eventDate = document.getElementById("eventDate").value;
  var eventTime = document.getElementById("eventTime").value;

  if (eventDate == "" || eventTime == "" || eventTitle == ""){
    alert("Check all inputs again!");
    return false;
  }

  var eventYear = eventDate.substring(0, 4);
  var eventMonth = eventDate.substring(5, 7);
  var eventDay = eventDate.substring(8, 10);

  var year = parseInt(eventYear);
  var month = parseInt(eventMonth);
  var day = parseInt(eventDay);

  // check valid inputs for date
  if (year < nowYear || (year == nowYear && month < nowMonth) || (year == nowYear && month == nowMonth && day < nowDay)){
    alert("You cannot create events for the past!");
    return false;
  }

  $.ajax({
    type: "POST",
    url: "createEvent.php",
    data: {"eventTitle": eventTitle, "eventYear": eventYear, "eventMonth": eventMonth, "eventDay": eventDay, "eventTime": eventTime},
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
