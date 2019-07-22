document.getElementById("disable").addEventListener("click", disableEvent, false);

function disableEvent(event){
  var monthNames = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
  var eventYearMonth = document.getElementById("currentMonth").value;
  eventYearMonth = eventYearMonth.split(" ");
  var eventYear = eventYearMonth[1];
  var eventMonthName = eventYearMonth[0];
  var eventMonth = monthNames.indexOf(eventMonthName)+1;

  if (eventMonth < 10){
    eventMonth = "0"+eventMonth;
  }
  else{
    eventMonth = eventMonth.toString();
  }

  $.ajax({
    type: "POST",
    url: "disableEvent.php",
    data: {"eventYear": eventYear, "eventMonth": eventMonth},
    success: function(data){
      try {
        var jsonData = JSON.parse(data);
      } catch(e){
        return false;
      }
      if (jsonData.length > 0){
        var suc = jsonData[0].success;
        var msg = jsonData[0].message;
        var username = jsonData[0].username;
        if (suc == true){
          // todo
          document.getElementById("user").innerHTML = "Hi "+username+"!";

          for (var j=1; j<43; j++){
            var newJ = j.toString();
            if (j < 10){
              var newJ = "0"+newJ;
            }

            var calendarGetInfo = document.getElementById(newJ).value;

            if (calendarGetInfo.length == 2){
              j = 43;
            }

            else{
              var i = 0;
              while (i < jsonData.length){
                var title = jsonData[i].eventTitle;
                var year = jsonData[i].eventYear;
                var month = jsonData[i].eventMonth;
                var day = jsonData[i].eventDay;
                var time = jsonData[i].eventTime;

                var calendarInfo = calendarGetInfo.split(" ");
                var calendarYear = calendarInfo[2];
                var calendarMonth = calendarInfo[1];
                var calendarDay = calendarInfo[0];
                document.getElementById(newJ).innerHTML = calendarDay;
                i++;
              }
            }
          }
          alert(msg);
        }
        else{
          alert(msg);
        }
      }
    }

  });
}
