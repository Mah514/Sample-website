<?php
// Start the session
session_start();


//  Redirect to this page if checkdate fails
$_SESSION['notFound'] = '../redirect/notFound.php';

//--------------------should be removed when login works--------------------------
$_SESSION['authenticated'] == 1;
$_SESSION['userID'] = 12737961;
$_COOKIE['userID'] = 12737961;
$userID = $_COOKIE['userID'];
//--------------------------------------------------------------------------------






//  Validates that the user has logged in, else will return user to homepage

if (((!isset($_SESSION["authenticated"]) | $_SESSION["authenticated"] == 0))
    && !isset($_COOKIE['userID'])
) {
    header('./homepage/homepage.php');


    //  If logged in will read data from users.txt and instantiate variables
} else {
    $userID = $_COOKIE['userID'];
    $filecontents = file_get_contents('../data/users.txt');
    $USERDATA = explode("\n", $filecontents);

    $COLUMNS = explode("\t", $USERDATA[0]);

    for ($i = 1; $i < count($USERDATA); $i++) {
        $string = $USERDATA[$i];
        $stringID = strstr($string, "\t", true);

        if (strcmp($userID,  $stringID) == '0') {
            $userdata = explode("\t", $string);

            $userDATA = array_combine($COLUMNS, $userdata);

            $password = $userDATA['password'];
            $name = $userDATA['name'];
            $email = $userDATA['email'];
            $address = $userDATA['address'];

            $_SESSION['password'] = $password;
            $_SESSION['name'] = $name;
            $_SESSION['email'] = $email;
            $_SESSION['address'] = $address;

            break;
        }
    }
}


//  checks if entered pass is good
if (isset($_POST['confirmPass'])) {
    if (strcmp(($_POST['confirmPass']), ($userDATA['password'])) == 0) {
        setcookie('isgood', "true");
    } else {
        setcookie('isgood', "false");
    }
}

//  sets the time zone
date_default_timezone_set("America/New_York");


//  Calendar Date set to GET (url)
$_SESSION['url'] = $_SERVER['REQUEST_URI'];
$url = $_SESSION['url'];
$url_params = parse_url($url);

//  Checks query part of url if empty
if (!empty($url_params['query'])) {

    parse_str($url_params['query'], $params);

    //  If query has date parameters AND are valide: parses them to page and session variables
    //  else: ends session and redirects to notFound
    if (checkdate($params['Month'], $params['Day'], $params['Year'])) {
        $_SESSION['calDay'] = $params['Day'];
        $_SESSION['calMonth'] = $params['Month'];
        $_SESSION['calYear'] = $params['Year'];
        $_SESSION['calDate'] = mktime(0, 0, 0, $calMonth, $calDay, $calYear);
        $_SESSION['prevDay'] = strtotime("-1 Day", $calDate);
        $_SESSION['nextDay'] = strtotime("+1 Day", $calDate);
        $_SESSION['weather'] = $params['weather'];

        $calDay = (int)$params['Day'];
        $calMonth = (int)$params['Month'];
        $calYear = (int)$params['Year'];
        $calDate = mktime(0, 0, 0, $calMonth, $calDay, $calYear);
        $prevDay = strtotime("-1 Day", $calDate);
        $nextDay = strtotime("+1 Day", $calDate);
        $weather = $params['weather'];

        setcookie('calDay', $calDay);
        setcookie('calMonth', $calMonth);
        setcookie('calYear', $calYear);
        setcookie('calDate', $calDate);
        setcookie('prevDay', $prevDay);
        setcookie('nextDay', $prevDay);
        setcookie('today', time());
    } else {
        header('location:' . $_SESSION['notFound']);
        session_destroy();
    }
    //  Checks the url for the weather parameter from the calendar page and parses it to the weather variables, 
    if ($params['weather'] == 'snow' | $params['weather'] == 'rain' | $params['weather'] == 'thunderstorms') {
        $weather = 0;   //  weather bad? 0 === false
    }
} else {
    //  In case of no query, default day chosen
    $_SESSION['calDay'] = date('d');
    $_SESSION['calMonth'] = date('m');
    $_SESSION['calYear'] = date('Y');
    $_SESSION['calDate'] = mktime(0, 0, 0, $calMonth, $calDay, $calYear);
    $_SESSION['prevDay'] = strtotime("-1 Day", $calDate);
    $_SESSION['nextDay'] = strtotime("+1 Day", $calDate);

    $calDay = date('d');
    $calMonth = date('m');
    $calYear = date('Y');
    $calDate = mktime(0, 0, 0, $calMonth, $calDay, $calYear);
    $prevDay = strtotime("-1 Day", $calDate);
    $nextDay = strtotime("+1 Day", $calDate);

    setcookie('calDay', $calDay);
    setcookie('calMonth', $calMonth);
    setcookie('calYear', $calYear);
    setcookie('calDate', $calDate);
    setcookie('prevDay', $prevDay);
    setcookie('nextDay', $prevDay);
    setcookie('today', time());
}

if ($_COOKIE['calDate'] < $_COOKIE['today']) {
    setcookie('tooEarly', 1);
} else {
    setcookie('tooEarly', 0);
}

//  Day of week based on calDate chosen
$dow = date('l', $calDate);
$dow = substr($dow, 0, 3);

//  gets weather data
$uno = 'https://api.openweathermap.org/data/2.5/onecall?lat=45.5088&lon=-73.5878&exclude=hourly,minutely,alerts&appid=aa389e674a5ea63c269216198dacc063';

$now = time();
$count = $calDate - $now;

$count = round($count / (60 * 60 * 24));

$contents = file_get_contents($uno);
$clima = json_decode($contents);

if (($calDate == mktime(0, 0, 0, date('m'), date('d'), date('Y')) | ($count < 0))) {
    $desc = $clima->current->weather[0]->main;
} else {
    $desc = $clima->daily[$count]->weather[0]->main;
}
setcookie('weatherDesc', $desc);

//  determines if ok to fly or not
if ($desc == 'Snow' | $desc == 'Rain' | $desc == 'Thunderstorms') {
    $weather = 0;
} else {
    $weather = 1;
}
setcookie('weather', $weather);

//  Check bookingfile for the booking events for the date selected
$filecontents = file_get_contents('../data/bookings.txt');
$BOOKINGDATA = explode("\n", $filecontents);

for ($i = 1; $i < count($BOOKINGDATA); $i++) {
    $string = $BOOKINGDATA[$i];
    $theDate = trim($calMonth . ' ' . $calDay . ' ' . $calYear);
    $dates = trim(substr(substr($string, strpos($string, "\t"), strlen($string)), 0, 26));
    $dates = trim(substr($dates, 4, strlen($dates)));
    $times = trim(substr($dates, 13, strlen($dates)));
    $dates = trim(substr($dates, 0, 12));

    $theDateOBJ = date_create_from_format("m d Y", $theDate);
    $datesOBJ =  date_create_from_format("M d Y", $dates);



    if ($theDateOBJ == $datesOBJ) {
        $place = strrchr($string, ")\t");
        $place = trim(substr($place, 1, strlen($place)));

        $trainings[] =
            array(
                'place' => $place,
                'date' => $dates,
                'time' => $times
            );
    }
}

//  In case of no events, set array to empty
if (!isset($trainings)) {
    $trainings = [];
}


if (isset($_POST['timestamp'])) {
    setcookie('timestamp', $_POST['timestamp']);
}
if (isset($_POST['bookingNumber'])) {
    setcookie('bookingNumber', $_POST['bookingNumber']);
}
if (isset($_POST['place'])) {
    setcookie('place', $_POST['place']);
}
if (isset($_COOKIE['bookingNumber'])) {
    $bookingNumber = $_COOKIE['bookingNumber'];
}

if (isset($_POST['dateChoice'])) {
    $dateChoice = strtotime($_POST['dateChoice']);
    setcookie('dateChoice', $_POST['dateChoice']);
    $calDay = date('d', $datets);
    setcookie('calDay', $_POST['calDay']);
    $calMonth = date('m', $datets);
    setcookie('calMonth', $_POST['calMonth']);
    $calYear = date('Y', $datets);
    setcookie('calYear', $_POST['calYear']);
}





require("../include/head.php");
?>

<!-- 
    SOEN 287 - Fall 2020 - Semester Project - Team 20

    Page Title: Booking Page
    Author: Alexa
    Purpose: For scheduling flight training sessions

-->


<!-- LIBRARIES -->
<link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" integrity="sha512-xodZBNTC5n17Xt2atTPuE1HxjVMSvLVW9ocqUKLsCC5CXdbqCmblAshOMAS6/keqq/sMZMZ19scR4PsZChSR7A==" crossorigin="">
<script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js" integrity="sha512-XQoYMqMTK8LvdxXYG3nZ448hOEQiglfqkJs1NOQV44cWnUrBc8PkAOcXy20w0vlaXaVUearIOBhiXZ5V3ynxwA==" crossorigin=""></script>

<!-- css pages -->
<link type="text/css" rel="stylesheet" href="../css/booking.css">

<!-- JAVASCRIPT FUNCTION SHEETS -->
<script type="text/javascript" src="sidebarctrl.js"></script>
<script type="text/javascript" src="mapctrl.js"></script>
<script type="text/javascript" src="cookiectrl.js"></script>
<script type="text/javascript" src="p.js"></script>
<script type="text/javascript" src="generator.js"></script>

<title>Booking</title>
</head>

<body id="bookingPage" onload="checkSide() && checkWeather('<?php echo $desc; ?>')">
    <div id="pageTop">
        <?php
        require("../include/nav.php");
        ?>
    </div>



    <div class="BookingPageContainer">

        <!-- Side bar which goes over the map -->
        <div class="formSide" id="left">

            <!-- If pass is good will submit to confirmation page, if not it will reload current page -->
            <form id="bookingform" <?php echo (($_COOKIE['isgood'] == "false") | (!isset($_COOKIE['isgood']))) ? "" : "action=\"./bookingconfirmation.php\""; ?> method="post" class="bookingForm">

                <div class="submitWrapper">
                    <a href="../calendar/calendar.php" class="dateButtons" id="returnCalendar">Return to Calendar</a><br>
                    <h3><?php echo date('l', $calDate) . ' ' . date('F jS, Y', $calDate) ?></h3>
                    <a class="dateButtons" href="<?php echo 'bookingpage.php?Day=' . date('d', $prevDay) . '&Month=' . date('m', $prevDay) . '&Year=' . date('Y', $prevDay) ?>">
                        Previous</a>
                    <a class="dateButtons" href="<?php echo 'bookingpage.php?Day=' . date('d', $nextDay) . '&Month=' . date('m', $nextDay) . '&Year=' . date('Y', $nextDay) ?>">
                        Next</a>
                </div>

                <table class="bookingFormTable">
                    <!-- User must select a date for the booking -->
                    <tr>
                        <td class="rightCol">
                            <fieldset class="userinputs">
                                <legend class="required">Date</legend>
                                <input type="date" name="dateChoice" id="dateChoice" class="userSelect" value="<?php echo date("Y-m-d", $calDate); ?>" onchange="pickDate()" required>
                            </fieldset>
                        </td>
                    </tr>

                    <!-- User must select a time slot for the booking -->
                    <tr>
                        <td class="rightCol">
                            <fieldset class="userinputs">
                                <legend class="required">Training Options</legend>
                                <select name="timeSlots" id="timeSlots" class="timeSlotss" onchange="pickSite('<?php echo $desc; ?>')" required>
                                    <?php
                                    if (isset($_POST['timeSlots'])) {
                                        if (count($trainings) === 0) {
                                            echo '<option disabled selected value> -- Sorry, none available -- </option>';
                                        } else {
                                            echo '<option disabled selected value> -- select an option -- </option>';
                                            for ($i = 0; $i < count($trainings); $i++) {
                                                if ($trainings[$i]['place'] == $_POST['timeSlots']) {
                                                    print '<option selected value="' . $trainings[$i]['place'] . '">' . $trainings[$i]['time'] . ' at ' . $trainings[$i]['place'] . ' </option>';
                                                } else {
                                                    print '<option value="' . $trainings[$i]['place'] . '">' . $trainings[$i]['time'] . ' at ' . $trainings[$i]['place'] . '</option>';
                                                }
                                            }
                                        }
                                    } else {

                                        if (count($trainings) === 0) {
                                            echo '<option disabled selected value> -- Sorry, none available -- </option>';
                                        } else {
                                            echo '<option disabled selected value> -- select an option -- </option>';
                                            for ($i = 0; $i < count($trainings); $i++) {
                                                print '<option value="' . $trainings[$i]['place'] . '">' . $trainings[$i]['time'] . ' at ' . $trainings[$i]['place'] . '</option>';
                                            }
                                        }
                                    }

                                    ?>
                                </select>
                            </fieldset>
                        </td>
                    </tr>


                    <fieldset class="placeInfo">
                        <div class="placeInfo">
                            <!-- Page will display the type of training offered for the selected booking  -->
                            <tr>
                                <td class="leftCol">
                                    <p class="labels">Weather that day: </p>
                                </td>
                                <td class="rightCol">
                                    <p id="weaDisp"><?php echo $desc; ?></p>
                                </td>
                            </tr>

                            <!-- Page will display the type of training offered for the selected booking  -->
                            <tr>
                                <td class="leftCol">
                                    <p class="labels">Type of Training: </p>
                                </td>
                                <td class="rightCol">
                                    <p id="typeDisp"></p>
                                </td>
                            </tr>

                            <!-- Page will display the name of the location for the selected booking -->
                            <tr>
                                <td class="leftCol">
                                    <p class="labels">Name: </p>
                                </td>
                                <td class="rightCol">
                                    <p id="nameDisp"></p>
                                </td>
                            </tr>

                            <!-- Page will display the address of the location for the selected booking -->
                            <tr>
                                <td class="leftCol">
                                    <p class="labels">Address: </p>
                                </td>
                                <td class="rightCol">
                                    <p id="addrDisp"></p>
                                </td>
                            </tr>

                            <!-- Page will display the phone number of the location for the selected booking -->
                            <tr>
                                <td class="leftCol">
                                    <p class="labels">Phone Number: </p>
                                </td>
                                <td class="rightCol">
                                    <p id="numbDisp"></p>
                                </td>
                            </tr>

                            <!-- Page will display the website address of the location for the selected booking -->
                            <tr>
                                <td class="leftCol">
                                    <p class="labels">Web Address: </p>
                                </td>
                                <td class="rightCol">
                                    <p id="webDisp"></p>
                                </td>
                            </tr>
                        </div>
                    </fieldset>
                </table>

                <table class="bookingFormTable">
                    <!-- Page will display the name of the current user -->
                    <tr>
                        <td class="rightCol">
                            <fieldset id="" class="userinfo">
                                <legend id="" class="">Your Name</legend>
                                <input type="text" name="name" value="<?php echo $name; ?>" readonly>
                            </fieldset>
                        </td>
                    </tr>

                    <!-- Page will display the address of the current user -->
                    <tr>
                        <td class="rightCol">
                            <fieldset id="" class="userinfo">
                                <legend id="" class="">Your Address</legend>
                                <input type="text" name="address" value="<?php echo $address; ?>" readonly>
                            </fieldset>
                        </td>
                    </tr>

                    <!-- Page will display the email of the current user -->
                    <tr>
                        <td class="rightCol">
                            <fieldset id="" class="userinfo">
                                <legend id="" class="">Your Email</legend>
                                <input type="text" name="email" value="<?php echo $email; ?>" readonly>
                            </fieldset>
                        </td>
                    </tr>

                    <!-- User must onfirm their password before submitting -->
                    <tr>
                        <td class="rightCol">
                            <div class="pWrap">
                                <div id="showPF">
                                    <fieldset id="fieldP" class="userInput">
                                        <legend id="" class="required">Please confirm your password: </legend>
                                        <input type="password" name="confirmPass" id="confirmPass" class="userSelect" <?php echo (!isset($_POST['confirmPass'])) ? '' : 'value=' . "$password" . "'"; ?> required>
                                    </fieldset>
                                </div>
                                <div id="showPASS" onclick="isVisible('v')"><i class="material-icons" id="visieye">&#xe8f5;</i></div>
                            </div>
                            <?php
                            if (isset($_COOKIE['isgood'])) {
                                if ($_COOKIE['isgood'] == "true") {
                                    echo 'Password check passed.';
                                } else if ($_COOKIE['isgood'] == "false") {
                                    echo 'Password check failed!';
                                } else if (!isset($_COOKIE['isgood'])) {
                                    echo "";
                                }
                            }
                            ?>
                        </td>
                    </tr>
                </table>
                </fieldset>

                <table class="bookingFormTable">
                    <!-- If there is issue with the weather on the specified date, this error will appear -->
                    <tr>
                        <td colspan="2" class="twoCol">
                            <div class="textWrapper">
                                <p id="weatherIssue"></p>
                            </div>
                        </td>
                    </tr>

                    <!-- Submit Button -->
                    <tr>
                        <td colspan="2" class="twoCol">
                            <div class="submitWrapper">
                                <?php
                                if (!$_COOKIE['tooEarly']) {
                                    echo (($_COOKIE['isgood'] == "false") | (!isset($_COOKIE['isgood']))) ? '<button type="submit" id="ISgood" class="submitButton">Check Password</button>' : '<button type="submit" class="submitButton" id="reserve" name="reserve" onclick="return confirm(' . "'" . 'Are you sure?' . "'" . ')">Reserve Now!</button>';
                                } else {
                                    echo '<button disabled type="submit" class="submitButton" id="reserve" name="reserve">Reserve Now!</button>';
                                }
                                ?>
                            </div>
                        </td>
                    </tr>

                    <!-- Here will be the unique booking ID# (once the form is submitted) -->
                    <tr>
                        <td colspan="2" class="twoCol">
                            <div class="textWrapper">
                                <div id="bookIDwrap">
                                    <label for="bookingNumber">Booking #: </label>
                                    <input type="text" id="bookingNumber" name="bookingNumber" value="<?php echo (!isset($_COOKIE['bookingNumber'])) ? '' : $bookingNumber; ?>" readonly></div>
                                <input type="hidden" name="timestamp" id="timestamp" value="<?php echo (!isset($_POST['timeStamp'])) ? '' : $timeStamp; ?>">
                                <input type="hidden" name="place" id="place" value="<?php echo (!isset($_POST['place'])) ? '' : $place; ?>">
                                <input type="hidden" name="hrs" id="hrs" value="">
                                <input type="hidden" name="uDay" id="uDay" value="<?php echo $calDay; ?>">
                                <input type="hidden" name="uMon" id="uMon" value="<?php echo $calMonth; ?>">
                                <input type="hidden" name="uYear" id="uYear" value="<?php echo $calYear; ?>">
                                <input type="hidden" name="uDate" id="uDate" value="<?php echo $calDate; ?>">
                                <input type=hidden name="userID" value='<?php echo $userID; ?>'>
                                <input type=hidden name="password" value='<?php echo $password; ?>'>
                                <input type=hidden name="orderID" id="orderID" value="">
                            </div>
                        </td>
                    </tr>
                </table>
            </form>
        </div>

        <div id="right">
            <!--- Map of location -->
            <div id="localeMap"></div>
            <div class="buttonWrapper">
                <button id="slideButton" class="slideSide" onclick="moveSide('s'); return true;">☰</button>
            </div>

            <?php include("../include/foot.php"); ?>

        </div>
    </div>



    <script type="text/javascript">
        //  CAE that does virtual training
        const caeName = 'CAE Montreal Training Center';
        const acadAddr = '8585 Ch de la Cote-de-Liesse, Saint-Laurent, QC H4T 1G6';
        const caeNumb = '(514) 341-6780';
        const acadLAT = 45.475330;
        const acadLNG = -73.706880;
        const caeWEB = '<A href="https://trainwithcae.com/locations/montreal">trainwithcae</a>';
        const caeTYPE = 'Virtual Aircraft';

        //  Pierre Trudeau airport that does in air training
        const acadName = 'Academy of Aeronautics <br>Academie D\'Aeronautique';
        const caeAddr = '300 Boul Marcel-Laurin Suite 200, Saint-Laurent, Quebec H4M 2L4';
        const acadNumb = '(514) 315-8762';
        const caeLAT = 45.503150;
        const caeLNG = -73.675990;
        const acadWEB = '<a href="https://academyofaeronautics.com/">academyaeronautic</a>';
        const acadTYPE = 'Real Aircraft';

        //  St hubert airport that does in air training
        const sthuName = 'Air Richelieu';
        const sthuAddr = '5800 Route de l\'Aéroport, Saint-Hubert, QC J3Y 8Y9';
        const sthuNumb = '(450) 445-4444';
        const sthuLAT = 45.514080;
        const sthuLNG = -73.408290;
        const sthuWEB = '<a href="http://airrichelieu.ca/EN/">airrichelieu</a>';
        const sthuTYPE = 'Real Aircraft';

        //  Error message shown when weather is bad
        const errorW = '<br>Unfortunately, the weather on this day<br>will not permit IN AIR training.<br> ';

        //  Date selected based on URL
        var calDate = <?php echo $calDate ?>;
        var calDay = <?php echo $calDay ?>;
        var calMonth = <?php echo $calMonth ?> - 1;
        var calYear = <?php echo $calYear ?>;

        var sidebar = {
            s: false,
        };

        //  Booking values taken from php via the booking file for the selected date
        <?php

        if (count($trainings) !== 0) {
            for ($i = 0; $i < count($trainings); $i++) {
                echo 'var place' . $i . ' = ' . '"' . $trainings[$i]['place'] . '"' . ';' . "\n";
                echo 'var date' . $i . ' = ' . '"' . $trainings[$i]['date'] . '"' . ';' . "\n";
                echo 'var time' . $i . ' = ' . '"' . $trainings[$i]['time'] . '"' . ';' . "\n";
                echo 'var dateObj' . $i . ' = new Date(calYear, calMonth, calDay, ' . "parseInt(time$i)" . ', 0, 0);' . "\n";
            }
        }

        ?>



        //  TODO: Read and pull data from users file
        var userAddr = '1100 Boulevard Robert-Bourassa #104, Montreal, Quebec H3B 3A5'; // TODO: updated by users profile
        var startLat = 45.5017; //  TODO: Determined by users address
        var startLng = -73.5673; //  TODO: Determined by users address


        //  TODO: get from url or method or file
        var weather = '<?php echo $weather; ?>';


        //  Default onload
        document.getElementById("left").style.width = 0;
        document.getElementById("right").style.marginLeft = 0;

        //  Default Map Positioning
        var mymap = L.map('localeMap').setView([startLat, startLng], 11);

        //  The map image tiles pulled from mapBox
        L.tileLayer('https://api.mapbox.com/styles/v1/{id}/tiles/{z}/{x}/{y}?access_token=pk.eyJ1IjoibWFwYm94IiwiYSI6ImNpejY4NXVycTA2emYycXBndHRqcmZ3N3gifQ.rJcFIG214AriISLbB6B5aw', {
            maxZoom: 18,
            attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/ ">OpenStreetMap</a> contributors, <br>' +
                '<a href="https://creativecommons.org/licenses/bysa/2.0/ ">CC-BY-SA</a>, ' +
                'Imagery © <a href="https://www.mapbox.com/ ">Mapbox</a>',
            id: 'mapbox/streets-v11',
            tileSize: 512,
            zoomOffset: -1
        }).addTo(mymap);

        var circleMarker = L.circle([0, 0], {
            color: 'blue',
            fillcolor: 'blue',
            fillOpacity: 0.25,
            radius: 0,
            draggable: true,
        }).addTo(mymap);



        var el = document.getElementById('bookingform');
        if (el) {
            el.addEventListener("submit", function() {
                var x = getID();
                var y = getID();
                var z = x + y;
                document.getElementById("orderID").value = z;
            }, false);
        }
    </script>
</body>

</html>