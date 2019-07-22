document.addEventListener("DOMContentLoaded", showEvent, false);
document.getElementById("nextMonth").addEventListener("click", showEvent, false);
document.getElementById("previousMonth").addEventListener("click", showEvent, false);
document.getElementById("create").addEventListener("click", showEvent, false);
document.getElementById("edit").addEventListener("click", showEvent, false);
document.getElementById("delete").addEventListener("click", showEvent, false);
showEvent();

function showEvent(event){
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
    url: "showEvent.php",
    data: {"eventYear": eventYear, "eventMonth": eventMonth},
    success: function(data){
      try {
        var jsonData = JSON.parse(data);
      } catch(e){
        return false;
      }

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
          var events = [];
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
            if (parseInt(calendarDay) < 10){
              calendarDay = "0"+calendarDay;
            }
            if (parseInt(calendarMonth)+1 < 10){
              calendarMonth = "0"+(parseInt(calendarMonth)+1).toString();
            }
            else{
              calendarMonth = (parseInt(calendarMonth)+1).toString();
            }
            if (calendarDay == day && calendarMonth == month && calendarYear == year){
              if (events.indexOf("<p>"+title+" "+time+"</p>") < 0){
                events.push("<p>"+title+" "+time+"</p>");
                document.getElementById(newJ).innerHTML = day.toString();
              }
            }
            document.getElementById(newJ).innerHTML = calendarDay;
            i++;
          }

          for (var item in events){
            events[item] = events[item].fontcolor("purple");
            document.getElementById(newJ).innerHTML += events[item];
          }
        }
      }

      loginFormat();
    }
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
