<link rel = "stylesheet" href="style.css">
<?php

include_once "settings.php";
$conn = @mysqli_connect($sql_host, $sql_user, $sql_pass, $sql_db);
if (!$conn) {
    /* Check database connection */
    echo "<p><font color='red'>Sorry connect failure</font></p>";
} else {
     /* Create table if not exists*/
    $sql_tble = "create table if not exists cabsOnline(
                                         bookingNum INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
                                         referenceNum VARCHAR(255) NOT NULL,
                                         customerName VARCHAR(255) NOT NULL,
                                         contactPhone INT NOT NULL,
                                         unitNum INT,
                                         streetNum INT NOT NULL,
                                         streetName VARCHAR(255) NOT NULL,
                                         suburb VARCHAR(255) NOT NULL,
                                         city VARCHAR (255) NOT NULL,
                                         desAddress VARCHAR(255) NOT NULL,
                                         desSuburb VARCHAR(255) NOT NULL,
                                         date DATE NOT NULL,
                                         time VARCHAR(255) NOT NULL,
                                         passNum INT NOT NULL,
                                         status varchar(50) default 'unassigned')";

    mysqli_query($conn, $sql_tble);
    $sql = "select * from cabsOnline";
    $result = mysqli_query($conn, $sql);
    if ($result == null) {
        echo "<p><font color='red'>There is no unassigned request</font></p>";
    } else {
        /* Create the table header */
        $tble_str = "<table border='2'class = 'requestTableLayout'><tr><th>Reference Number</th>
		<th>Customer Name</th><th>Contact Phone</th><th>Pick Up Suburb</th>
		<th>Destination Suburb</th><th>Pick Up Date</th>
		<th>Pick-Up Time</th><th>Number of Passengers</th><th>Status</th>";
        while ($row = mysqli_fetch_assoc($result)) {
            $status = $row["status"];
            if ($status == "unassigned") {
                $referenceNum = $row["referenceNum"];
                $customerName = $row["customerName"];
                $contactPhone = $row["contactPhone"];
                $pickUpSub = $row["suburb"];
                $desSub = $row["desSuburb"];
                $pickUpDate = $row["date"];
                $pickUpTime = $row["time"];
                $passNum = $row["passNum"];
                $status = $row["status"];
                /*Two hours later than current time*/
		$twoHoursLater = date("Y-m-d H:i",strtotime('+2 hour'));
                /*Current Time*/
		$currTime = date('Y-M-D H:i');
		        if((strtotime($pickUpDate." ".$pickUpTime)>strtotime(date('Y-m-d H:i'))) && (strtotime($pickUpDate." ".$pickUpTime) <  strtotime($twoHoursLater))){
                   /* Create the table to show the request */
                    $tble_str = $tble_str . "<tr><td>" . $referenceNum . "</td>
		           <td>" . $customerName . "</td>
		           <td>" . $contactPhone . "</td>
		           <td>" . $pickUpSub . "</td>
		           <td>" . $desSub . "</td>
		           <td>" . $pickUpDate . "</td>
		           <td>" . $pickUpTime . "</td>
		           <td>" . $passNum . "</td>
                   <td>" . $status."    </tr>";
                }
            }
        }
        $tble_str = $tble_str . "</table>";
        /*Print the table*/
	echo $tble_str;
    }
}
?>