<?php
//error_reporting(0); - active--------------------------------------------------------------------

// }
//Create connection
$conn = mysqli_connect('localhost', 'u269067746_root', 'Tonhu@1603', 'u269067746_EDI_SOLUTION');
// Check connection
if (mysqli_connect_error()){
    echo "connection fail".mysqli_connect_error();
}
else { echo "connection successfully";};



$sql = "INSERT INTO Inbox (EDIROWID,ReceivedDate,SenderISA, ReceiveISA,x12_Message) VALUES ('123hsj','30032022','134','145','agsgasd')";
//             echo "ISA query:".$sql."<br>";

if ($conn->query($sql) === TRUE) {
  echo "New record created successfully";
} else {
  echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();

?>