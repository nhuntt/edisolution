<?php
$page = $_SERVER['PHP_SELF'];
$sec = "10";
// $dsn="mysql:host=localhost;dbname=edi_solution";
$servername='127.0.0.1:3306';
$database='u269067746_EDI_SOLUTION';
$username="u269067746_root";
$password="Xiu@16031977";

$conn = mysqli_connect($servername, $username, $password, $database);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " 
        . $conn->connect_error);
}
    echo "Connected successfully"."<br>";
    $sql = "SELECT EDIROWID, SenderISA FROM Inbox ";
    

    // if ($result->num_rows > 0) {
    //     echo "<table><tr><th>EDIROWID</th><th>SenderISA</th></tr>";
    //     // output data of each row
    //     while($row = $result->fetch_assoc()) {
    //       echo "<tr><td>".$row["EDIROWID"]."</td><td>".$row["SenderISA"]."</td></tr>";
    //     }
    //     echo "</table>";
    //   } else {
    //     echo "0 results";
    //   }
    class crud {
      public function getAttendeeDetails($EDIROWID)
      {
      try{
      $sql = "select * from Inbox ";
      $stmt = $this->db->prepare($sql);
      $stmt->bindparam(':id', $EDIROWID);
      $stmt->execute();
      $result = $stmt->fetch();
      return $result;
      }catch (PDOException $e) {
      echo $e->getMessage();
      return false;
      }}
      }

?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="refresh" content="<?php echo $sec?>;URL='<?php echo $page?>'">
  <title>Document</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
  <link rel="stylesheet" href="style.css">
</head>

<body>
  <div class="app">
    <div class="container-fluid px-0">
      <header>
        <div class="logo">
            EDISOLUTION WEB SUPPLIER
        </div>
        <div>
        </div>
      </header>
      <main>
        <div class="container-fluid px-0">
          <div class="d-flex">
            <div class="sidebar">
              In
            </div>
            <div class="contents-wrapper">
              <div class="content">
                <table>
                    <tr>
                      <th>EDIROWID</th>
                      <th>SenderISA</th>
                      <th>viewdocument</th>
                    </tr>
          <?php
          $result = $conn->query($sql);
          if ($result->num_rows > 0) { 
            // echo "<p>".$result."</p>";
            // echo "<table><tr><th>EDIROWID</th><th>SenderISA</th></tr>";
            // output data of each row
            while($row = $result->fetch_assoc()) { ?>
              
               <tr>
                 <td><?php echo $row["EDIROWID"] ?></td>
                 <td><?php echo $row["SenderISA"] ?></td>             
              <td><a href="demo.php?EDIROWID=<?php echo $row['EDIROWID'] ?>" class="btn btn-primary">View </a></td>
              </tr>
              <?php }?>
            
              <?php } else {
            echo "0 results";
          }?>
         
                  </table>
              </div>
            </div>
          </div>
        </div>
      </main>
      <footer>
        <div class="footer-wrapper">
          <div class="copyright">
            EDISOLUTION WEB SUPPLIER
          </div>
        </div>
      </footer>
      <!-- <td><input style="WIDTH: 200px" type="submit" value="View" onclick="location.href='viewDocument.html'" ></td></tr> -->
      
    </div>
  </div>
</body>
</html>