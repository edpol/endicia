<?php
/*
*  open csv file
*  if success
*      open file with PDO driver
*      if success
*          open database
*          loop:
*              csv file, build sql statement, execute
*
* write log file if status is not 'P' ?
*/

function redirectTo( $location = NULL ) {
    if ($location !== NULL) {
// echo "<br /><pre>\$_SESSION "; print_r($_SESSION); echo $location . "</pre>";
        header("Location: {$location}");
        exit;
    }
}

function deleteOldCsv(){
    $scanned_directory = array_diff(scandir(UPLOADS_FOLDER), array('..', '.'));
    foreach($scanned_directory as $file){
        $file_pointer = UPLOADS_FOLDER.DS.$file;
        $modified = filemtime($file_pointer);
        if($modified + CACHE_LIFETIME < time()) {
            // Use unlink() function to delete a file
            if (!unlink($file_pointer)) {
                echo ("$file cannot be deleted due to an error");
            } else {
                echo ("$file has been deleted");
            }
            echo '<br>';
        }
    }
}

function buildArray($file): array
{
    /* open csv file */
    $csv = array_map('str_getcsv', file($file));

    /*
     *      array_combine was not working because it was detecting a 27th column in the data
     *      so this section changes the array of arrays to array of associative arrays
     *
     *      array_walk($csv, static function (&$a) use ($csv) { $a = array_combine($csv[0], $a); });
     */
    $header = $csv[0];
    array_shift($csv); # remove column header
    $count_col = count($header);
    foreach ($csv as $j => $row) {
        $new_row = [];
        for($i=0; $i<$count_col; $i++) {
            $new_row[$header[$i]] = $row[$i];
        }
        $csv[$j] = $new_row;
    }

    $db_list = array("MDR" => "MailOrderManager", "CCC" => "MOM-Clientele");
    $output = [];

    $endshare_columns = ['num_rows', 'orderno', 'tracking_number', 'weigh', 'ship_date', 'charges'];
    foreach ($db_list as $company => $dbname) {

        $color = ($company === "MDR") ? 'Azure' : '#FFE9E6';
        $tr_color = true;
        $bg = "#EEE";

        $database = new Database($dbname);
        $db =& $database;
        
        $output[$company] = "<table>\n";
        $output[$company] .= "<tr><td colspan='". count($endshare_columns) . "'>{$company}</td></tr>\n";
        $output[$company] .= "<tr style='background-color:{$bg}'>\n";
        foreach($endshare_columns as $el) {
            $output[$company] .= "<th>{$el}</th>\n";
        }
        $output[$company] .= "<tr>\n";
        $total = 0;
        foreach ($csv as $row) {
            $group = $row['Reference1'];
            if (($company === 'MDR' && $group === '25') || ($company === 'CCC' && $group === '26')) {

                $result = $db->update($row);
                $ship_date = '';
                extract($result, EXTR_OVERWRITE);
                $ship_date = date('m/d/Y', strtotime($ship_date));

                $bg = ($tr_color) ? $color : "#EEE";
                $tr_color = !$tr_color;
                $output[$company] .= "<tr style='background-color:{$bg}'>\n";
                foreach($endshare_columns as $el) {
                    $output[$company] .= "<td>{$$el}</td>\n";
                }
                $output[$company] .= "<tr>\n";
                $total += $result['num_rows'];
            }
        }
        $output[$company] .= "</table>\n";
        $output[$company] .= "<tr><td colspan='".count($endshare_columns)."'>{$company} Total: {$total}</td></tr>\n";
        $output[$company] .= "<br />\n";
    }
    return $output;
}
