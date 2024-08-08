<?php
require_once("../includes/initialize.php");
session_start();

$arr_file_types = ['text/plain', "text/csv", 'application/vnd.ms-excel'];
if (!(in_array($_FILES['file']['type'], $arr_file_types))) {
    $_SESSION['error'] .= "Wrong File Type " . $_FILES['file']['type'] . "<br>";
    return;
}

if (!file_exists('uploads')) {
    mkdir('uploads', 0777);
}

$tmp_file = $_FILES['file']['tmp_name'];
$dest_file = 'uploads/' . time() . '_' . $_FILES['file']['name'];

if (!copy($tmp_file, $dest_file)) {
    echo "failed to copy $tmp_file...\n";
} else {
    $_SESSION['file'] = $dest_file;
    //    redirectTo('updateMOM.php');
}
