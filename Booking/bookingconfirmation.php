<?php
// Start the session
session_start();

//  Redirect to this page if checkdate fails
$_SESSION['notFound'] = '../redirect/notFound.php';



$userID = $_POST['userID'];
$password = $_POST['password'];
$name = $_POST['name'];
$email = $_POST['email'];
$uaddress = $_POST['address'];
$bookingNumber = $_COOKIE['bookingNumber'];
$timestamp = $_COOKIE['timestamp'];
$place = $_COOKIE['place'];
$hrs = $_POST['hrs'];
$dateChoice = $_COOKIE['dateChoice'];
$timeSlots = $_POST['timeSlots'];
$orderID = $_POST['orderID'];
$uDay = $_POST['uDay'];
$uMon = $_POST['uMon'];
$uYear = $_POST['uYear'];
$uDate = $_POST['$uDate'];
$virtHrs = '0';
$realHrs = '0';
$fileDone = 0;


if ($place == 'CAE') {
    $virtHrs = '9';
    $addr = '8585 Ch de la Cote-de-Liesse, Saint-Laurent, QC H4T 1G6';
} else if ($place == 'STHU') {
    $realHrs = '5';
    $addr = '5800 Route de l\'Aéroport, Saint-Hubert, QC J3Y 8Y9';
} else if ($place == 'ACAD') {
    $realHrs = '6';
    $addr = '300 Boul Marcel-Laurin Suite 200, Saint-Laurent, Quebec H4M 2L4';
}


$filename = "../data/users/" . "$userID" . ".txt";
$file = fopen("$filename", 'a') or die("Failed to create file");

$text = $bookingNumber . "\t" . $orderID . "\t" . $timestamp . "\t" . $place
    . "\t" . $virtHrs . "\t" . $realHrs . "\n";

fwrite($file, $text) or die("Could not write to file");

fclose($file);




use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
date_default_timezone_set('Etc/UTC');
require './PHPMailerTemplate/vendor/autoload.php';
$mail = new PHPMailer;
$mail->isSMTP();
$mail->Host = 'smtp.gmail.com';
$mail->Port = 587;
$mail->SMTPAuth = true;
$mail->Username = 'ag0586243@gmail.com';
$mail->Password = 'aaAA11!!';
$mail->setFrom('ag0586243@gmail.com', 'TakeOff-MTL Administrator');
$mail->addAddress(/* $email, $name */'ag0586243@gmail.com'); // commented out so that I don't keep emailing this random person
$mail->isHTML(true);
$mail->Subject = 'PHPMailer SMTP test';

$body = 'Dear '. $name . ',' . 
'<p>A reservation has been successfully created for you with ' . $place . ' for ' 
. $dateChoice . ' corresponding to the booking ID "'. $bookingNumber .'"' 
. '<br>Your confirmation number is: ' . $orderID . '</p>' 
. '<br><br>' 
. 'Date and Time: ' . $timestamp . '.<br>'
. 'Place: ' . $place . '.<br>'
. 'Address: ' . $addr . '.<br>'
. 'Number of virtual hours ' . $virtHrs . '.<br>'
. 'Number of real hours ' . $realHrs . '.<br>'
. 'Confirmation number ' . $orderID . '. <br><br>' .

'Best regards,<br>
TakeOff-MTL Administrator';

$mail->Body = $body;
if (!$mail->send()) {
    echo 'Mailer Error: ' . $mail->ErrorInfo;
} else {
    echo 'Success!!';
}

setcookie('isgood', '', -1);
setcookie('bookingNumber', '', -1);
setcookie('dateChoice', '', -1);
setcookie('timestamp', '', -1);
setcookie('place', '', -1);
setcookie('slided', '', -1);
setcookie('weather', '', -1);
setcookie('weatherDesc', '', -1);
setcookie('calDay', '', -1);
setcookie('calMonth', '', -1);
setcookie('calYear', '', -1);
setcookie('calDate', '', -1);
setcookie('today', '', -1);
setcookie('tooEarly', '', -1);



session_destroy();

require("../include/head.php");
?>

<!-- 
    SOEN 287 - Fall 2020 - Semester Project - Team 20

    Page Title: Booking Page
    Author: Alexa
    Purpose: For scheduling flight training sessions

-->


<link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" integrity="sha512-xodZBNTC5n17Xt2atTPuE1HxjVMSvLVW9ocqUKLsCC5CXdbqCmblAshOMAS6/keqq/sMZMZ19scR4PsZChSR7A==" crossorigin="">
<script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js" integrity="sha512-XQoYMqMTK8LvdxXYG3nZ448hOEQiglfqkJs1NOQV44cWnUrBc8PkAOcXy20w0vlaXaVUearIOBhiXZ5V3ynxwA==" crossorigin=""></script>
<script type="text/javascript" src="sidebarctrl.js"></script>
<script type="text/javascript" src="mapctrl.js"></script>
<script type="text/javascript" src="cookiectrl.js"></script>


<link type="text/css" rel="stylesheet" href="../css/booking.css">
<title>Booking Page</title>
</head>

<body id="bodyconf">
    <div id="pageTop">
        <?php
        require("../include/nav.php");
        ?>
    </div>

    <div id="bookconfirm-wrap">
        <div id="confirmation-wrap">


            <p id="bookconfirmation">
                Your booking order ID number is: <?php echo $orderID; ?>
                <br><br>
                We have emailed your booking details to <?php echo $email; ?>
                <br><br>
                Thank you for booking with us.
            </p>

            <a href="./bookingpage.php" id="backtoBooking">--- Make a new booking</a>

        </div>
    </div>


    <?php include("../include/foot.php"); ?>

</body>

</html>