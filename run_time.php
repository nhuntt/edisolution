
<?php
  
// Demonstrate the use of header() function
// to refresh the current page
   
echo "Welcome to index page</br>";
echo "we will redirect to GeeksForGeeks Official website in 3 second";
    
// The function will redirect to geeksforgeeks official website
header("refresh: 3; url = http://edisolution.online/parseEDI.php");
    
exit;
?>