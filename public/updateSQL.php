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
<div style="margin-left:5%;">
<?php
    /*
     * hide the for div, show a spinning gif then show this
     */

    if (isset($_POST['submit'], $_SESSION['file'])) {
        $output = buildArray($_SESSION['file']);
        foreach($output as $table){
            echo $table . "<br />";
        }
    } else {
        echo "Error in updateSQL file";
    }

    echo "<p>Delete CSV files <u>older</u> than 31 days <br>";
    deleteOldCsv();
    echo "</p>";
?>
</div>
<script type="text/javascript" src="js/script.js"></script>
</body>
</html>
