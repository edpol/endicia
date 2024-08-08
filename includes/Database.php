<?php
require_once(LIB_PATH.DS."config.php");

class Database {

    private $dbh;
    public $last_query;

    public function __construct($db){
        $this->openConnection($db);
    }

    public function openConnection($db): void
    {
        try {
            $serverName = DB_SERVER . "\\" . DB_INSTANCE . "," . DB_PORT;
            $dsn = "sqlsrv:Server=" . $serverName . ";Database=$db";
            $this->dbh = new PDO ($dsn, DB_USER, DB_PASS);
            $this->dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
        } catch (PDOException $e) {
            echo $e->getMessage();
            die();
        }
    }

//    public function query($sql) {
//        $res = $this->dbh->exec($sql);
//        return $res->fetchAll(PDO::FETCH_ASSOC);
//    }

    public function update($row): array
    {
        $ship_date = date('Y-m-d G:i:s', strtotime($row['Ship Date']));
        $charges = preg_replace("/[^0-9.]/", "", $row['Amount Paid']);

        $weigh = explode(" ", $row['Weight']);
        $lbs = preg_replace("/[^0-9.]/", "", $weigh[0]);  // if not number replace with nothing
        $oz  = preg_replace("/[^0-9.]/", "", $weigh[1]);
        $bill_weigh = sprintf('%s', $lbs + $oz/16);

        // old version "'9405515969007798578268'"
        // new version ="9400116969007798825953"
        $tracking_number = str_replace(array("'","=",'"'), array('','',''), $row['Tracking #']);
        $orderno = $row['Reference2'];

        $sql  = "update ENDSHARE set USI_STATUS='S', TRACKINGNO='$tracking_number', BILL_WEIGH='$bill_weigh', SHIP_DATE='$ship_date', CHARGES=$charges where ORDERNO='$orderno' and USI_STATUS = :usi_status ";

        $rows = $this->myExecute($sql,[':usi_status' => 'P']);
        $num_rows = $rows->rowCount();
        /*
         * if there are no orders with the status Printed try the query for status Returned.
         * if it exists and has no ship date or tracking number it should be updates
         */
        if($num_rows===0) {
            $rows = $this->myExecute($sql,[':usi_status' => 'R']);
            $num_rows = $rows->rowCount();
        }

        /*
         * get box_id and update tracking number if it is blank
         */
        $row = $this->myFetch("select box_id, orderno, trackingno from ENDSHARE where ORDERNO='$orderno' ");
        if(empty($row['trackingno'])) {
            $temp = $this->myExecute("update BOX set TRACKINGNO='$tracking_number' where box_id=" . $row['box_id'] );
        }

        return [
            'sql'             => $sql,
            'num_rows'        => $num_rows,
            'orderno'         => $orderno,
            'tracking_number' => $tracking_number,
            'weigh'           => $bill_weigh,
            'ship_date'       => $ship_date,
            'charges'         => $charges,
        ];
    }

    //update box
    //set trackingno=e.trackingno --, ship_date=e.ship_date
    //from endshare as e
    //left join box as b on b.box_id=e.box_id
    //where e.ship_date>@yesterday and b.box_id=293866

    public function myFetchAll($sql,$values=[])
    {
        $sth = $this->myExecute($sql,$values);
        return $sth->fetchAll();
    }
    public function myFetch($sql,$values=[])
    {
        $sth = $this->myExecute($sql,$values);
        return $sth->fetch();
    }
    public function myExecute($sql,$values=[])
    {
        $sth = $this->dbh->prepare($sql);
        $this->last_query = $sql;
        $sth->execute($values);
        return $sth;
    }
}
