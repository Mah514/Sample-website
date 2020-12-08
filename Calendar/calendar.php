<?php
	$bdTransformed = array();
	$bookingData = array();
	
	$file = fopen('../data/Bookings.txt', 'r');
	$skip = 0;
	
	while (($result = fgetcsv($file,1000,"\t")) !== false)
	{	
		if($skip !== 0){
		$bookingData[] = $result;
		$arrKey = substr($result[1] ,8 ,2) . monthInt(strtoupper(substr($result[1] , 4,3))) . substr($result[1] ,11,4);
		
		if(array_key_exists($arrKey,$bdTransformed)){
		$bdTransformed[$arrKey]++;
		}
		else{
			$bdTransformed[$arrKey] = 1;
		}

		
		}
		$skip++;
		
	}

	fclose($file);
	
			function monthInt($mo){
			switch ($mo) {
				case 'JAN':
					return '01';
					break;
				case 'FEB':
					return '02';
					break;
				case 'MAR':
					return '03';
					break;
				case 'APR':
					return '04';
					break;
				case 'MAY':
					return '05';
					break;
				case 'JUN':
					return '06';
					break;
				case 'JUL':
					return '07';
					break;
				case 'AUG':
					return '08';
					break;
				case 'SEP':
					return '09';
					break;
				case 'OCT':
					return '10';
					break;
				case 'NOV':
					return '11';
					break;
				case 'DEC':
					return '12';
					break;					
				}
		}
	
	

?>

<?php
require("../include/head.inc");
?>

    <style>
	.linkz:link {
		text-decoration: none;
		color: black;
		}

	.linkz:visited {
	text-decoration: none;	
	color: black;
	}

	</style>
	<link rel="stylesheet" href="calendarStyle.css">
	<link rel="stylesheet" href="otherStyles.css">

  <title>Calendar</title>
  </head>
  <body>
 
	<?php include "../include/nav.inc"?>

    <div class="cal_block">
      <div class="calendar">
        <div class="month">
		<a class="cal_prev">&#10094;</a>
         
          <div class="date">
            <h1></h1>
            <p></p>
          </div>
           <a class="cal_next" >&#10095;</a>
        </div>
        <div class="weekdays">
          <div >Sunday</div>
          <div >Monday</div>
          <div >Tuesday</div>
          <div >Wednesday</div>
          <div >Thursday</div>
          <div >Friday</div>
          <div >Saturday</div>
        </div>
        <div class="days"></div>
      </div>
    </div>
	
	<?php include "../include/foot.inc" ?>
	
	<script type="text/javascript">
		var jsonBookings = <?php echo json_encode($bdTransformed); ?>;
			
	</script>

    <script src="calendar.js"></script>
  </body>
</html>
