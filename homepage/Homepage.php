<?php
require("../include/head.inc");
?>
        <!-- Homepage CSS-->
        <link rel="stylesheet" href="side.css">
        <link rel="stylesheet" href="main.css">
        <link rel="stylesheet" href="../css/article.css">
        <link rel="stylesheet" href="slideshow.css">
        <link rel="stylesheet" href="../allstyles.css">

        <!-- Homepage JS-->
        <script src="slideshow.js"></script>
</head>
<body onload="autoSlides()">
<?php
require("../include/nav.inc");
?>

        <div class="container">
          <div class="slides fade">
            <div class="numbertext">1 / 3</div>
            <img src="sim-exterior.jpg" style="width:100%">
            <div class="text">The most innovative full-flight simulators</div>
          </div>
          
          <div class="slides fade">
            <div class="numbertext">2 / 3</div>
            <img src="pilots1.png" style="width:100%">
            <div class="text">Learn to fly with CAE</div>
          </div>
          
          <div class="slides fade">
            <div class="numbertext">3 / 3</div>
            <img src="flight.jpg" style="width:100%">
            <div class="text">Experience the sky on the ground</div>
          </div>
          
          <a class="prev" onclick="plusSlides(-1)">&#10094;</a>
          <a class="next" onclick="plusSlides(1)">&#10095;</a>
          
          </div>
          <br>
          
          <div style="text-align:center">
            <span class="dot" onclick="currentSlide(1)"></span> 
            <span class="dot" onclick="currentSlide(2)"></span> 
            <span class="dot" onclick="currentSlide(3)"></span> 
          </div>

          <div class="row">
            <div class="side">
              <h2>About CAE</h2><br/>
              <p>CAE is the world's leader in training. Their technology allows pilots to train in an immersive simulated environment that acclimates them to flying various models of planes for commercial purposes. Every type of aircraft requires its own flight simulator. They have several training locations for their flight simulators. They also work with flight academies that allow pilots to train in real aircrafts. CAE is the largest civil aviation network in the world and graduates over a thousand cadets every year. Furthermore, CAE trains over 220,000 commercial pilots and cabin crew members using over 50 training locations.</p><br/>
              
              <h2>About us</h2><br/>
              <p>We are a booking platform for student pilots to reserve training sessions directly with CAE. Our platform offers weather forecast to help you make the right choice in booking an in-air or flight-simulated training sessions.</p>
            </div>

            <div class="main">
              <h2 class="training">Our Training Centers</h2><br />
              
              <div class="center"><h4 class="centername">CAE</h4> <br/>
                <p>CAE offers the most innovative full-flight simulators, improving training efficiency, providing advanced capabilities, and increasing operational efficiency.</p>
              </div>
              <div class="centerm"><h4 class="centername">Pierre Trudeau Airport</h4> <br/>
                <p>Our biggest center for flight training hours, Pierre Trudeau Airport offers a wide range of aeroplanes to train with that will help you towards your Private Pilot License and Commercial Pilot License</p>
              </div>
              <div class="center"><h4 class="centername">St. Hubert Longueuil Airport</h4><br/>
                <p>Located in the borough of Longueuil, this training center contains a broad selection of training aircraft for students off the island of Montreal.</p>
              </div>

            </div>
          </div>

          <?php include("../include/foot.inc"); ?>
    </body>
</html>
