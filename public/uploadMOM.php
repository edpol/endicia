<?php
    require_once("../includes/initialize.php");
    session_start();
?>
<!DOCTYPE html>
<html lang="en-US">
<head>
    <meta charset="UTF-8">
    <title>Upload to MOM</title>
    <link rel="stylesheet" type="text/css" href="stylesheets/style.css">
    <link rel="stylesheet" type="text/css" href="//fonts.googleapis.com/css?family=Raleway" />
</head>
<body>
    <div id="wrapper">
        <form id="myForm" action="updateSQL.php" method="post">
            <p style="text-align:center">File uploaded successfully.</p>
            <input class="blue_up" id="myBtn"  type="submit" name="submit" value="Update MOM?" />
            <input class="blue_up" id="cancel" type="button" formaction="index.php" value="Cancel" />
        </form>
    </div>
    <script type="text/javascript" src="js/script.js"></script>

</body>
</html>
