const date = new Date();



const API_KEY = "aa389e674a5ea63c269216198dacc063";
const API_CALL = 'https://api.openweathermap.org/data/2.5/onecall?lat=45.5088&lon=-73.5878&exclude=hourly,minutely,alerts&appid=' + API_KEY;

const renderCalendar = () => {
  
  //Set the date to the 1st of the month
  date.setDate(1);
  console.log(date);
  //alert(jsonBookings['22112020']);
  	
  const monthDays = document.querySelector(".days");
  
  const lday = new Date(date.getFullYear(),date.getMonth() + 1,0).getDate();
  const prevlday = new Date(date.getFullYear(),date.getMonth(),0).getDate();
  const firstDayIndex = date.getDay();
  const ldayIndex = new Date(date.getFullYear(),date.getMonth() + 1,0).getDay();

  const nextDays = 7 - ldayIndex - 1;

  const months = [
    "January",
    "February",
    "March",
    "April",
    "May",
    "June",
    "July",
    "August",
    "September",
    "October",
    "November",
    "December",
  ];

  document.querySelector(".date h1").innerHTML = months[date.getMonth()] + " " +date.getFullYear();

  document.querySelector(".date p").innerHTML = new Date().toDateString();

  let days = "";

/*Dates from last month*/
	console.log(firstDayIndex);
  for (let x = firstDayIndex; x > 0; x--) {
    	  days += `<div class="dayBox lastmonth">
				
				<div class="dayHeader">
					<div class="dayNum">${prevlday - x + 1}</div>
 
				</div>
			</div>`
  }
  

var today = 0;
var todayAlready = false;
var tempInt = 0;

for (let i = 1; i <= lday; i++) {
    
	/*Today*/
	if (
      i === new Date().getDate() &&
      date.getMonth() === new Date().getMonth()
    ) {
	  days += `<div class="dayBox">
				
				<div class="dayHeader">
					<div class="dayNum">${i}</div>
					<div class="dayTemp" id="temp_today"></div>
					<div class="dayImg">
						<img class="weather_icon" id="img_today"  src="" alt="">
					</div>     
				</div>
				<div class="q_${hasBookings(i,date.getMonth()+1,date.getFullYear())}">
				<a href="../Booking/BookingPage.php" id="link_today" class ="linkz">
			    <div class="dayAvailability" id="avail_today" >${isAvailable(i,date.getMonth()+1,date.getFullYear())}
				</div>  
				</a>
				</div>
			</div>`
	  today = i;
	  todayAlready = true;
    } 
	
	/*All other days this month*/	
	else {
      	  days += `<div class="dayBox">
					<div class="dayHeader">
						<div class="dayNum">${i}</div>`;
		
		if(todayAlready && i-today<10){
		
			days += `<div class="dayTemp" id=temp_${i-today}></div>`;
			days += `<div class="dayImg">
						<img class="weather_icon" id=img_${i-today}  src="" alt="">
					</div> `;
			days += `</div>
					<div class="q_${hasBookings(i,date.getMonth()+1,date.getFullYear())}">
					<a href="../Booking/BookingPage.php" id=link_${i-today} class ="linkz">
					<div class="dayAvailability" id=avail_${i-today} >${isAvailable(i,date.getMonth()+1,date.getFullYear())}
					</div></a></div>
					</div>`;		
			tempInt = i-today;
			days += `</div></div>`;
		}
		
		else if(todayAlready && i-today>=10){
	  
			days += `
					</div><div class="q_${hasBookings(i,date.getMonth()+1,date.getFullYear())}">
					<a  class ="linkz" href="../Booking/BookingPage.php?Day=${i}&Month=${date.getMonth()+1}&Year=${date.getFullYear()}&weather=Clear" id=link_${i-today}>
					<div class="dayAvailability">${isAvailable(i,date.getMonth()+1,date.getFullYear())}
					</div></a></div></div>`;
		}
		else if(todayAlready){
			days += `</div>
			<div class="q_${hasBookings(i,date.getMonth()+1,date.getFullYear())}">
			<a class ="linkz" href="../Booking/BookingPage.php?Day=${i}&Month=${date.getMonth()+1}&Year=${date.getFullYear()}&weather=Clear" id=link_${i-today}>
			<div class="dayAvailability q_1">${isAvailable(i,date.getMonth()+1,date.getFullYear())}</div></a></div></div>`;
		  //days += `</div></div>`;
		}
		else{
			days += `</div>
			<div class="q_${hasBookings(i,date.getMonth()+1,date.getFullYear())}">
			<a class ="linkz" href="../Booking/BookingPage.php?Day=${i}&Month=${date.getMonth()+1}&Year=${date.getFullYear()}&weather=Clear">
			<div class="dayAvailability q_1">${isAvailable(i,date.getMonth()+1,date.getFullYear())}</div></a></div></div>`;
		}
		  
	  
    }
	
	
  }
   
	

	/*days from next month*/
	
  for (let j = 1; j <= nextDays; j++) {
		
	days += `<div class="dayBox nextmonth">
				
				<div class="dayHeader">
					<div class="dayNum">${j}</div>
					<div class="dayTemp" id=temp_${++tempInt}></div>
					    
				</div>
				<div class="q_${hasBookings(j,(date.getMonth()+2)%12,date.getFullYear()+1)}">
			    <div class="dayAvailability" >${isAvailable(j,(date.getMonth()+2)%12,date.getFullYear()+1)}
				</div> 
				</div>
			</div>`
    
  }
  
  monthDays.innerHTML = days;
  
};

document.querySelector(".cal_prev").addEventListener("click", () => {
  date.setMonth(date.getMonth() - 1);
  renderCalendar();
  getWeather();
});

document.querySelector(".cal_next").addEventListener("click", () => {
  date.setMonth(date.getMonth() + 1);
  renderCalendar();
  getWeather();
});

renderCalendar();

function openPage(){
	alert("open's a new page");
};


//https://bithacker.dev/fetch-weather-openweathermap-api-javascript
function getWeather(){
	
	fetch(API_CALL)
	.then(function(resp) {return resp.json()})
	.then(function(data) { 
		//console.log(data);
		addWeather(data);
	}	)
	

	
}

window.onload = function(){
		getWeather();
}

function addWeather(data){
	
	var tempDate = new Date();
	var tempMonth = tempDate.getMonth()+1;
	var tempDay = tempDate.getDate();
	var tempYear = tempDate.getFullYear();
	
	var temp= Math.round(parseFloat(data.current.temp) - 273.15 );
	var icon = data.current.weather[0].main ;
	var description = data.current.weather[0].description ;
	
	document.getElementById('temp_today').innerHTML = temp + '&#176C';
	document.getElementById('img_today').src = getGif(icon);
	document.getElementById('img_today').alt = description;
	
	if(icon == 'Thunderstorm' || icon == 'Rain' || icon == 'Snow' || icon == 'Atmosphere'){
		document.getElementById('avail_today').classList.add('hazard')
	}
		
	
	
	//add the GET request
	
	document.getElementById('link_today').href = `../Booking/BookingPage.php?Day=${tempDay}&Month=${tempMonth}&Year=${tempDate.getFullYear()}&weather=${icon}`;
	


	

	for(let i = 1; i < 8; i++){
		
		tempDate.setDate(tempDate.getDate() + 1);
		tempMonth = tempDate.getMonth()+1;
		tempDay = tempDate.getDate();
		tempYear = tempDate.getFullYear();
	
		
		icon = data.daily[i].weather[0].main ;

		description = data.daily[i].weather[0].description ;
		var futureTemp = Math.round(parseFloat(data.daily[i].temp.day) - 273.15 );
		
		document.getElementById(`temp_${i}`).innerHTML = futureTemp + '&#176C';
		document.getElementById(`img_${i}`).src = getGif(icon);
		document.getElementById(`img_${i}`).alt = description;
		
		//PHP Pass the value 

		document.getElementById(`link_${i}`).href = `../Booking/BookingPage.php?Day=${tempDay}&Month=${tempMonth}&Year=${tempDate.getFullYear()}&weather=${icon}`;
		

		
		if(icon == 'Thunderstorm' || icon == 'Rain' || icon == 'Snow' || icon == 'Atmosphere'){
			document.getElementById(`avail_${i}`).classList.add('hazard')
		}
	
		
	}
	
	//8 & 9 Test case

		
		tempDate.setDate(tempDate.getDate() + 1);
		tempMonth = tempDate.getMonth()+1;
		tempDay = tempDate.getDate();

		
		document.getElementById('temp_8').innerHTML = 25 + '&#176C';
		document.getElementById('img_8').src = getGif("Thunderstorm");
		document.getElementById('img_8').alt = 'Thunder Test';
		
		//PHP Pass the value
		document.getElementById('link_8').href = `../Booking/BookingPage.php?Day=${tempDay}&Month=${tempMonth}&Year=${tempDate.getFullYear()}&weather=Thunderstorm`;	
	
		if(icon == 'Thunderstorm' || icon == 'Rain' || icon == 'Snow' || icon == 'Atmosphere'){
			document.getElementById(`avail_8`).classList.add('hazard')
		}		
		
		//9
		tempDate.setDate(tempDate.getDate() + 1);
		tempMonth = tempDate.getMonth()+1;
		tempDay = tempDate.getDate();

		
		document.getElementById('temp_9').innerHTML = 15 + '&#176C';
		document.getElementById('img_9').src = getGif("Clear");
		document.getElementById('img_9').alt = 'Clear Test';
		
		//PHP Pass the value 
		document.getElementById('link_9').href = `../Booking/BookingPage.php?Day=${tempDay}&Month=${tempMonth}&Year=${tempDate.getFullYear()}&weather=Clear`;
		
		
	
	
	
	//

	
}

function getGif(main){
	
	if(main == "Thunderstorm")
		return "animated/thunder.svg";
	else if(main == "Drizzle")
		return "animated/rainy-2.svg";
	else if(main == "Rain")
		return "animated/rainy-6.svg";
	else if(main == "Snow")
		return "animated/snowy-5.svg";
	else if(main == "Clear")
		return "animated/day.svg";
	else if(main =="Clouds")
		return "animated/cloudy-day-3.svg";
	else
		return "animated/cloudy-day-1.svg";
}

function isAvailable(day, month, year){
	
	var tempToday = new Date();
	var otherDate = new Date();
	
	otherDate.setFullYear(year);
	otherDate.setMonth(month-1);
	otherDate.setDate(day);
	
	day = String(day);
	month = String(month);
	year = String(year);
	
	var tempStr = "" + ( day.length == 1 ? '0'+ day : day) + (month.length == 1 ? '0'+ month : month) + (year) ;
	
	console.debug(tempStr,jsonBookings.hasOwnProperty(tempStr) , jsonBookings);
	
	if(otherDate < tempToday){
		
		return 'Date already passed'
	}	
	
	else if(jsonBookings.hasOwnProperty(tempStr)){
		return "( " + jsonBookings[tempStr] + " )  Available Bookings";
	}

	else{
		return "No Bookings"
	}
		
	
}
function hasBookings(day,month,year){
	
	
	var tempToday = new Date();
	var otherDate = new Date();
	
	otherDate.setFullYear(year);
	otherDate.setMonth(month-1);
	otherDate.setDate(day);
	
	day = String(day);
	month = String(month);
	year = String(year);
	
	var tempStr = "" + ( day.length == 1 ? '0'+ day : day) + (month.length == 1 ? '0'+ month : month) + (year) ;
	
	console.debug(tempStr,jsonBookings.hasOwnProperty(tempStr) , jsonBookings);
	
	if(otherDate < tempToday){
		
		return '0';
	}	
	
	else if(jsonBookings.hasOwnProperty(tempStr)){
		return '1';
	}

	else{
		return '0';
	}

}

function passBooking(){
	alert("Hello");
}
