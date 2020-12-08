<?php
session_start();
$isLogin="false";
if(isset($_SESSION["useremail"])){
$isLogin="true";
}
$error="";
$ProfilePicture="";
$LFP="";
$LBP="";
if(isset($_FILES['profilePhoto'])){
    $errors= array();
    $file_name = $_FILES['profilePhoto']['name'];
    $file_tmp =$_FILES['profilePhoto']['tmp_name'];
    if(empty($errors)==true){
      $ProfilePicture="../userdetails/userImages/".$file_name;
       move_uploaded_file($file_tmp,"../userdetails/userImages/".$file_name);
    }else{
       print_r($errors);
    }
   }
if(isset($_FILES['lfp'])){
  $errors= array();
  $file_name = $_FILES['lfp']['name'];
  $file_tmp =$_FILES['lfp']['tmp_name'];
  if(empty($errors)==true){
    $LFP="../userdetails/userImages/".$file_name;
     move_uploaded_file($file_tmp,$LFP);
  }else{
     print_r($errors);
  }
 }
if(isset($_FILES['lbp'])){
  $errors= array();
  $file_name = $_FILES['lbp']['name'];
  $file_tmp =$_FILES['lbp']['tmp_name'];
  if(empty($errors)==true){
    $LBP="../userdetails/userImages/".$file_name;
     move_uploaded_file($file_tmp,$LBP);
  }else{
     print_r($errors);
  }}
if($_SERVER["REQUEST_METHOD"] == "POST") {
  if(isset($_POST['logout'])){
    session_unset();
    session_destroy();
    header("location: ../homepage/Homepage.php");
  }
  if(!isset($_POST['user_name'])  || trim($_POST['user_name']) === ''){
 $error="Please Provide Name";
  }
  else if(!isset( $_POST['user_email'])  || trim($_POST['user_email']) === ''){
 $error="Please Provide Email";
  }
  else if(!isset( $_POST['user_password'])  || trim( $_POST['user_password']) === ''){
 $error="Please Provide Password";
  }
  else if(!isset( $_POST['age']) || trim( $_POST['age']) === ''){
 $error="Please Provide Age";
  }else{
   $myfile = fopen($_POST['user_email'].".txt", "w") or die("Unable to open file!");
   $txt = $_POST['user_name']."\n";
   fwrite($myfile, $txt);
   $txt = $_POST['user_email']."\n";
   fwrite($myfile, $txt);
   $txt = $_POST['user_password']."\n";
   fwrite($myfile, $txt);
   $txt = $_POST['age']."\n";
   fwrite($myfile, $txt);
   $txt = $_POST['address']."\n";
   fwrite($myfile, $txt);
   $txt = $ProfilePicture."\n";
   fwrite($myfile, $txt);
   $txt = $_POST['user_bio']."\n";
   fwrite($myfile, $txt);
   $txt = $_POST['YOE']."\n";
   fwrite($myfile, $txt);
   $txt = $_POST['user_job']."\n";
   fwrite($myfile, $txt);
   $txt = $_POST['ln']."\n";
   fwrite($myfile, $txt);
   $txt = $LFP."\n";
   fwrite($myfile, $txt);
   $txt = $LBP."\n";
   fwrite($myfile, $txt);
   $txt = $_POST['AFL']."\n";
   fwrite($myfile, $txt);
   $txt = $_POST['NFL']."\n";
   fwrite($myfile, $txt);
   $txt = $_POST['VFL']."\n";
   fwrite($myfile, $txt);
   $txt = $_POST['RFL']."\n";
   fwrite($myfile, $txt);
   $txt = $_POST['TFH']."\n";
   fwrite($myfile, $txt);
   $txt = $_POST['pd']."\n";
   fwrite($myfile, $txt);
   fclose($myfile);
   echo("<script>alert('User created Successfully');</script>");
   }}
if(isset($_POST['Login'])) { 
    if(!isset( $_POST['email']) || trim($_POST['email']) === ''){
      $error="Please Provide Email";
      }
      else if(!isset( $_POST['psw']) || trim($_POST['psw']) === ''){
      $error="Please Provide Password";
      }else{
      if(file_exists($_POST['email'].".txt")==1){
        $myfile = fopen($_POST['email'].".txt", "r") or die("Unable to open file!");
        $UserEmail='';
        $UserPassword='';
        $i=0;
        while(!feof($myfile)) {
          $val= fgets($myfile);
            if($i==1){
              $UserEmail=$val;
            }
            if($i==2){
              $UserPassword=$val;
            }
            $i++;
        }
        if(trim($UserEmail)==$_POST['email'] && trim($UserPassword)==$_POST['psw']){
          $_SESSION["useremail"] = $UserEmail;
          $isLogin="true";
          header("location: ../homepage/Homepage.php");
         }else{
           echo("<script>alert('Email or password doesnot match');</script>");
          $error="Email or password doesnot match";   
        }
       }else{
        echo("<script>alert('User Doesnot exist');</script>");
        $error="User Doesnot exist";
       }
     }
   } 
 ?>
<header class="header" id="header">
        <table>
          <tr>
            <td><h1>TakeOff-MTL</h1></td>
            <td rowspan="2"><img src="../include/logo1.png"></td>
          </tr>
          <tr>
            <td><p>Touch the sky</p></td>
          </tr>
        </table>
      </header> 
           <!-- Navigation bar-->
           <nav>
            <ul class="navbar">
                <li><a href="../homepage/Homepage.php" class="activenav">About CAE</a></li>
                <li><a href="#centers">Training Centers</a></li>
                <li><a href="../Booking/BookingPage.php">Booking</a></li>
                <li><a href="../calendar/calendar.php">Calendar</a></li>
                <?php 
                   if($isLogin=="true")
                   {echo "<li><a href='../userdetails/UserInfo.php'>UserProfile</a></li>";} ?>
                <li class="dropdown right">
                   <a  class="dropbtn">
                  <?php 
                   if($isLogin=="true")
                   {echo "Logout";} 
                   else{echo "Login/Sign up";}?>
                   </a>
                   <div class="dropdown-content">
                   <?php if($isLogin=="false"){
                    echo '
                    <button class="loginbutton" onclick="document.getElementById(\'id01\').style.display=\'block\'"; style="width:auto;font-style: italic;">Login</button>
                    <button class ="subutton" onclick="document.getElementById(\'id02\').style.display=\'block\'"; style="width:auto; font-style: italic;">Sign Up</button>';
                   }else{
                     echo '
                     <form method="post"> 
                     <button class ="subutton" name="logout" style="width:auto; font-style: italic;">Logout</button>
                     </form> 
                     ';
                   }
                   ?>
                 </li>
                <li class="dropdown right">
                  <a href="javascript:void(0)" class="dropbtn">Support</a>
                  <div class="dropdown-content">
                    <a href="#contact">Contact us</a>
                    <a href="#review">Training center reviews</a>
                  </div>
                </li>
            </ul>
          </nav>

          <div id="id01" class="modal">
            <form class="modal-content animate" method="post">
              <div class="imgcontainer">
                <span onclick="document.getElementById('id01').style.display='none'" class="close" title="Close Modal">&times;</span>
              </div>
              <div class="logincontainer">
                <label for="uname"><b>Email</b></label>
                <input type="text" placeholder="Enter Email" name="email" id="email" required>
                <label for="psw"><b>Password</b></label>
                <input type="password" placeholder="Enter Password" name="psw" id="psw" required>
                <div style = "color:#cc0000; margin-top:10px"><?php echo $error; ?></div>
                <button class="loginbutton" id="Login" name="Login" type="submit">Login</button>
                <label>
                <input type="checkbox" checked="checked" name="remember"> Remember me
                </label>
              </div>
              <div class="logincontainer" style="background-color:#f1f1f1">
                <button  type="button" onclick="document.getElementById('id01').style.display='none'" class="cancelbtn">Cancel</button>
                <a href="forgot" style="text-align: center; color:black;">Forgot password?</a>
              </div>
            </form>
          </div>

          <div id="id02" class="sumodal">
            <form  action=""  id="form" method="post" enctype="multipart/form-data">
            <span onclick="document.getElementById('id02').style.display='none'" class="suclose" title="Close Modal">&times;</span>
            <h1>Sign Up</h1>
            <div style = "color:#cc0000; margin-top:10px"><?php echo $error; ?></div>
            </form></div>
          </div>