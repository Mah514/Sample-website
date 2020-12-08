/* ----------------------------------------------------------------------------------
                                    Side bar controls (Scripts)
------------------------------------------------------------------------------------*/
//  Updates the contact info and calls the functions that change the map and check the weather
function pickSite(weather) {
    var t = document.querySelector('#timeSlots');
    var t_text = t.options[t.selectedIndex].value;

    if (t_text == 'CAE') {
        document.getElementById('typeDisp').innerHTML = caeTYPE;
        document.getElementById('nameDisp').innerHTML = caeName;
        document.getElementById('addrDisp').innerHTML = caeAddr;
        document.getElementById('numbDisp').innerHTML = caeNumb;
        document.getElementById('webDisp').innerHTML = caeWEB;

        //  update booking number
        getBookingNumber(t_text);
    }

    if (t_text == 'ACAD') {
        document.getElementById('typeDisp').innerHTML = acadTYPE;
        document.getElementById('nameDisp').innerHTML = acadName;
        document.getElementById('addrDisp').innerHTML = acadAddr;
        document.getElementById('numbDisp').innerHTML = acadNumb;
        document.getElementById('webDisp').innerHTML = acadWEB;

        //  update booking number
        getBookingNumber(t_text);
    }

    if (t_text == 'STHU') {
        document.getElementById('typeDisp').innerHTML = sthuTYPE;
        document.getElementById('nameDisp').innerHTML = sthuName;
        document.getElementById('addrDisp').innerHTML = sthuAddr;
        document.getElementById('numbDisp').innerHTML = sthuNumb;
        document.getElementById('webDisp').innerHTML = sthuWEB;

        //  update booking number
        getBookingNumber(t_text);
    }
    mapUpdate();
    checkWeather(weather);
}

//  Updates page based on the date selected
function pickDate(weather) {
    input = document.querySelector('#dateChoice').valueAsDate;
    var calMonth = '';

    //  parses the input into an array, isolates the day/calMonthth/year
    if (input != null) {
        date = new Date();
        calDate = input;
        calDate = input.toString();
        calDate = calDate.split(" ");

        calDay = parseInt(calDate[2]) + 1;
        calYear = parseInt(calDate[3]);


        // determines the value of the calMonthth
        if (calDate[1] === 'Jan') {
            calMonth = 1;
        } else if (calDate[1] === 'Feb') {
            calMonth = 2;
        } else if (calDate[1] === 'Mar') {
            calMonth = 3;
        } else if (calDate[1] === 'Apr') {
            calMonth = 4;
        } else if (calDate[1] === 'May') {
            calMonth = 5;
        } else if (calDate[1] === 'Jun') {
            calMonth = 6;
        } else if (calDate[1] === 'Jul') {
            calMonth = 7;
        } else if (calDate[1] === 'Aug') {
            calMonth = 8;
        } else if (calDate[1] === 'Sep') {
            calMonth = 9;
        } else if (calDate[1] === 'Oct') {
            calMonth = 10;
        } else if (calDate[1] === 'Nov') {
            calMonth = 11;
        } else if (calDate[1] === 'Dec') {
            calMonth = 12;
        }

        window.location.href = 'bookingpage.php?Day=' + calDay + '&Month=' + calMonth + '&Year=' + calYear;
    }
}


//  Controls the button that opens and closes the side bar
function moveSide(s) {
    sidebar[s] = !sidebar[s];

    var slided = sidebar[s];

    if (slided == true) {
        document.getElementById("left").style.width = "450px";
        document.getElementById("right").style.marginLeft = "450px";

    } else if (slided == false) {
        document.getElementById("left").style.width = "0";
        document.getElementById("right").style.marginLeft = "0";
    }

    setCookie("slided", slided, 30 * 24 * 60 * 60); // 30 days x 24 hrs x 60 min x 60 sec
}

function checkSide() {
    var slidecookie = getCookie("slided");

    if (slidecookie == "true") {
        sidebar['s'] = false;
        moveSide('s');
    }
}

function getBookingNumber(place) {
    var hrs = '';
    if (place === 'STHU') {
        hrs = 7;
    } else if (place === 'ACAD') {
        hrs = 6;
    } else if (place === 'CAE') {
        hrs = 9;
    }
    var d = new Date(calYear, calMonth, calDay, hrs, 0, 0);
    bookingNum = d.getTime();
    document.getElementById('bookingNumber').value = bookingNum;
    document.getElementById('timestamp').value = d.toString();
    document.getElementById('place').value = place;
    document.getElementById('hrs').value = hrs;
}


//  Checks whether the weather imported from Calendar allows for flight training or not.
function checkWeather(weather) {
    var site = document.querySelector('.timeSlotss').value;
    if (weather == 'Snow' | weather == 'Rain' | weather == 'Thunderstorms') {
        if (site == 'ACAD' | site == 'STHU') {
            document.getElementById('weatherIssue').innerHTML = errorW;
            document.querySelector('.submitButton').disabled = true;
            console.log('true');
        } else {
            document.getElementById('weatherIssue').innerHTML = '';
            document.querySelector('.submitButton').disabled = false;
        }
        return false; //  weather bad? 0 === false
    } else return true; //  weather bad? 0 === false
}