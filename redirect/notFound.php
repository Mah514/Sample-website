<?php
// Start the session
session_start();

require("../include/head.php");
?>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<link rel="stylesheet" href="../cdd/notFound.css">

<title>404 Not Found</title>
</head>

<body id="errorPage">
<div id="pageTop">
        <?php
        require("../include/nav.php");
        ?>
    </div>

    <div id="errorPage-wrap">
        <div id="error-wrap">

            <p id="error">404 Error - Page not Found</p>

            <p id="noPage">
                Looks like the page you're looking for does not exist.
            </p>

            <a href="../homepage/homepage.php" id="backHome">--- Return to the homepage</a>

        </div>
    </div>

    <?php include "../footer.inc" ?>

</body>

</html>