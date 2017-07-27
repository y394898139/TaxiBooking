<?php

//connect to database
require_once('settings.php');

$conn = @mysqli_connect($sql_host, $sql_user, $sql_pass, $sql_db);

if ($conn) {
    $referenceNum = $_POST["referenceNum"];
    if (!empty($_POST["referenceNum"])) {

        $query = "select * from cabsOnline where referenceNum = '$referenceNum'";
        $uniqueQueryResult = @mysqli_query($conn, $query) or die("<p>Unable to execute the query.</p>"
                        . "<p>Error code " . mysqli_errno($conn)
                        . ": " . mysqli_error($conn)) . "</p>";

        $result = mysqli_query($conn, $query);
        if (!$result) {
            echo "<p><font color='red'>The SQL query got error!</font></p>";
        } else {
	     //The reference number does not exists
            if (mysqli_num_rows($result) < 1) {
                echo "<p><font color='red'>Sorry,the entered reference number does not exists!</font></p>";
            } else {
		//Update the status of the request to assigned
                $query = "update cabsOnline set status = 'assigned' where referenceNum = '$referenceNum' and status = 'unassigned'";
                /*Error display*/
                $queryResult = @mysqli_query($conn, $query) or die("<p>Unable to execute the query.</p><p>Error code " . mysqli_errno($conn) . ":" . mysqli_error($conn)) . "</p>";

                $result = mysqli_query($conn, $query);

                if (!$result) {
                    echo "<p><font color='red'>The query operation got error!</font></p>";
                } else {

                    echo "<p><font color='oldlace'>The booking request <font color='red'>$referenceNum</font> has been properly assigned.</font></p>";
                }
            }
        }
    } else {
        echo "<p><font color='Red'>Please enter the reference number in the search box!</font></p>";
    }
    //close the connect
    mysqli_close($conn);
} else {

    echo "<p>Database connection failure</p>";
    mysqli_close($conn);
}
?>