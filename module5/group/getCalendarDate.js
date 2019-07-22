// For our purposes, we can keep the current month in a variable in the global scope

// in the nextMonth() and prevMont() functions, the month index is starting from 0 to 11
// in the for getMonth(), the month index is also from 0 to 11
var now = new Date();
var nowDate = now.getDate();
var nowMonth = now.getMonth();
var nowYear = now.getFullYear();
var currentMonth = new Month(nowYear, nowMonth);

var monthNames = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
var weekDayNames = ["Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat"]

updateCalendar();

// Change the month when the "next" button is pressed
document.getElementById("nextMonth").addEventListener("click", function(event){
	currentMonth = currentMonth.nextMonth(); // Previous month would be currentMonth.prevMonth()
	updateCalendar(); // Whenever the month is updated, we'll need to re-render the calendar in HTML
	//alert("The new month is "+monthNames[currentMonth.month]+" "+currentMonth.year);
}, false);

// Change the month when the "previous" button is pressed
document.getElementById("previousMonth").addEventListener("click", function(event){
  currentMonth = currentMonth.prevMonth();
  updateCalendar();
  //alert("The new month is "+monthNames[currentMonth.month]+" "+currentMonth.year);
}, false);


// This updateCalendar() function only alerts the dates in the currently specified month.  You need to write
// it to modify the DOM (optionally using jQuery) to display the days and weeks in the current month.
function updateCalendar(){
	var day = 0;
	var dayID = "";

	var month = currentMonth.month;
	var year = currentMonth.year;

	document.getElementById("currentMonth").innerHTML = monthNames[month]+" "+year;
	document.getElementById("currentMonth").value = monthNames[month]+" "+year;

	var weeks = currentMonth.getWeeks();
	var weekNum = weeks.length;

	// we do not want extra rows displayed
	for (var i=36; i<43; i++){
		if (weekNum == 5){
			document.getElementById(i.toString()).value = i.toString();
			document.getElementById(i.toString()).style.display = "none";
		}
		else {
			document.getElementById(i.toString()).style.display = "";
		}
	}

	for(var w in weeks){
		var days = weeks[w].getDates();
		// days contains normal JavaScript Date objects.
		// alert("Week starting on "+days[0]);

		for(var d in days){
			// You can see console.log() output in your JavaScript debugging tool, like Firebug,
			// WebWit Inspector, or Dragonfly.
			// console.log(days[d].toISOString());
			day ++;
			if (day < 10){
				dayID = "0"+day;
			}
			else {
				dayID = day.toString();
			}

			// console.log(dayID);

			document.getElementById(dayID).innerHTML = days[d].getDate();
			document.getElementById(dayID).value = days[d].getDate()+" "+days[d].getMonth()+" "+days[d].getFullYear();

			if (days[d].getMonth() != month){
				document.getElementById(dayID).style.color = "gray";
			}
			else {
				document.getElementById(dayID).style.color = "black";
			}

			if (year == nowYear && days[d].getDate() == nowDate && month == nowMonth){
				document.getElementById(dayID).style.background = "cyan";
			}
			else {
				document.getElementById(dayID).style.background = "palevioletred";
			}
		}

	}

}
