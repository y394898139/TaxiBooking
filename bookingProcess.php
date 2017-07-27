<?php
require_once ("settings.php");
$conn = @mysqli_connect($sql_host, $sql_user, $sql_pass, $sql_db);
if (!$conn) {
    echo "<p> Database connection failure</p>";
} else {
    $sql_tble = "create table if not exists cabsOnline(
					                     bookingNum INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
                                         referenceNum VARCHAR(255) NOT NULL,
					                     customerName VARCHAR(255) NOT NULL,
                                         contactPhone INT NOT NULL,
                                         unitNum INT,
                                         streetNum INT NOT NULL,
                                         streetName VARCHAR(255) NOT NULL,
                                         suburb VARCHAR(255) NOT NULL,
                                         city VARCHAR(255) NOT NULL,
                                         desSuburb VARCHAR(255) NOT NULL,
                                         date DATE NOT NULL,
                                         time VARCHAR(255) NOT NULL,
                                         passNum INT NOT NULL,
                                         status varchar(50) default 'unassigned')";
    $queryResult = @mysqli_query($conn, $sql_tble)
            or die("<p> Unable to excute the query.</p>"
                    . "<p>Error code " . mysqli_errno($conn)
                    . ":" . mysqli_error($conn)) . "</p>";
    //get the user input
	$customerName = $_POST['customerName'];
    $contactPhone = $_POST['contactPhone'];
    $unitNum = $_POST['unitNum'];
    $streetNum = $_POST['streetNum'];
    $streetName = $_POST['streetName'];
    $suburb = $_POST['suburb'];
    $desSuburb = $_POST['desSuburb'];
    $date = $_POST['date'];
    $time = $_POST['time'];
    $passNum = $_POST['passNum'];
    $referenceNum = $_POST['referenceNum'];
    $city = $_POST['city'];
    $checkName = false;
    $checkPhoneNum = false;
    $checkStNum = false;
    $checkStName = false;
    $checkSuburb = false;
    $checkDesSuburb = false;
    $checkDate = false;
	//check whether the input is empty
    if (!empty($customerName) && !empty($contactPhone) && !empty($streetNum) &&
            !empty($streetName) && !empty($suburb) &&
            !empty($desSuburb) && !empty($date) && !empty($time)) {
        $nameCheck = "/^[a-zA-Z]*/";
        $streenNumCheck = "/^[0-9]+$/";
        $addressCheck = "/^[a-zA-Z0-9_ ]*$/";
        $dateFormat = "/^(([0-9]|[1-2][0-9]|[3][0-1])-([0-9]|[1][0-2])-([1-2][0-9][0-9][0-9]))/";
        $timeCheck = "/^(([0-1]?[0-9])|([2][0-3])):([0-5]?[0-9])/";
//        check every input
        if (preg_match($nameCheck, $customerName)) {
            $checkName = true;
        } else {
            echo "<p style='color:white; font-weight:bold;'>Customer name should only include a-z and A-Z.</p>";
        }
        if (preg_match($streenNumCheck, $contactPhone)) {
            $checkPhoneNum = true;
        } else {
            echo "<p style='color:white; font-weight:bold;'>Please enter a correct phone number with numbers only.</p>";
        }
        if (preg_match($streenNumCheck, $streetNum)) {
            $checkStNum = true;
        } else {
            echo "<p style='color:white; font-weight:bold;'>Please enter a correct street number with numbers only.</p>";
        }
        if (preg_match($nameCheck, $streetName)) {
            $checkStName = true;
        } else {
            echo "<p style='color:white; font-weight:bold;'>Please enter a corect street name.</p>";
        }
        if (preg_match($nameCheck, $suburb)) {
            $checkSuburb = true;
        } else {
            echo "<p style='color:white; font-weight:bold;'>Please enter a corect suburb name.</p>";
        }
        if (preg_match($nameCheck, $desSuburb)) {
            $chekDesSuburb = true;
        } else {
            echo "<p style='color:white; font-weight:bold;'>Please enter a corect destination suburb!</p>";
        }
        if (strtotime($date." ".$time) > strtotime(date("Y-m-d H:i"))){
            $checkDate = true;
            $timeCheck = true;
        }
		//if the pick up time earlier than the current time
		else{
            echo "<p style='color:white; font-weight:bold;'>Sorry,the pick up time should be later than the current time</p>";
        }
    }   //some blanks are empty
		else {
        echo "<p style ='color:white; font-weight:bold;'>Please fill in the blank except the unit number and pick address are optional.";
    }

    if ($checkName && $checkPhoneNum && $checkStNum && $checkStName && $checkSuburb  && $chekDesSuburb && $checkDate && $timeCheck) {
        $queryCheck = "select * from cabsOnline where referenceNum='$referenceNum'";
        $result = mysqli_query($conn, $queryCheck);
        if (!$result) {
           echo "<p>Something is wrong with ",	$queryCheck, "</p>";
        } else {
            $row = mysqli_fetch_assoc($result);
            //if the request is already exsits
			if ($row != null) {
                echo "<p style='color:white; font-weight:bold;''>Sorry,the request is already exists</p>";
            } else {

                $query = "insert into cabsOnline(referenceNum,customerName,contactPhone,unitNum,streetNum, streetName,suburb,city, desSuburb,date,time,passNum) 
                values('$referenceNum','$customerName','$contactPhone','$unitNum','$streetNum','$streetName','$suburb','$city','$desSuburb','$date','$time','$passNum')";
                echo "<p style ='color:white; font-weight:bold;'>Thank you! Your booking reference number is < " . $referenceNum . " >.";
                echo "<br/>";
                echo "You will be picked up in front of your provided address at " . $time . " on " . $date;
                mysqli_query($conn, $query);
            }
        }
    } else {
        echo "<p style = 'color:white; font-weight:bold;'>You have entered information with wrong format, please check again</p>";
    }
}
?>