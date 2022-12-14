<!DOCTYPE html>
<html>
  
<head>
    <title>Move file</title>
    <link rel = "stylesheet" type="text/css" href="/style.css">
</head>
<body>
	<div class="wrapper">
		<div class="header">
			<img src="/Picture/headerImage.jpg">
		</div>
		<div class="menu">
			<p>menu</p>
		</div>
		<div class="main">
			<table id="customers">
			  <tr>
				<th>ToCustomer</th>
				<th>Filename</th>
				<th>Process Status</th>
				<th>Content</th>
			  </tr>
				<?php
// 				$servername='';
// $database='u269067746_EDI_SOLUTION';
// $username="u269067746_root";
// $password="Xiu@";

// }
//Create connection
// $conn = mysqli_connect($servername, $username, $password, $database);
// echo "sv: ".$servername. "</br>";
// echo "pw: ".$password. "</br>";
// // Check connection
// if ($conn->connect_error) {
//     die("Connection failed: " 
//         . $conn->connect_error);
// }
// else  { echo "Connected successfully on parseEDI"."<br>";}
$conn = mysqli_connect('localhost', 'u269067746_root', 'Tonhu@1603', 'u269067746_EDI_SOLUTION');
// Check connection
if (mysqli_connect_error()){
    echo "connection fail".mysqli_connect_error();
}
else { echo "connection successfully";};
		chdir("./../");
		$dir = getcwd();
		if(is_dir($dir)){
			if($dh = opendir($dir)){
				while (($file = readdir($dh)) !== false){
					if(($file != 'Processed')and ($file != 'Processing')and ($file != 'public_html')and ($file != '.')and ($file != '..')){
						//echo "folder:" .$file. "<br>";
						chdir($file);
						//echo getcwd(). "<br>";
						$current_customer_folder = getcwd();
						//echo ($current_folder) "<br>";
						$scandata = scandir($current_customer_folder);
						//print_r($scandata);
						$Looping_Counter_file = 0;
						while($scandata[$Looping_Counter_file] != null){
							if((($scandata[$Looping_Counter_file]) != ".") and (($scandata[$Looping_Counter_file]) != "..")){
								//echo "file name: ".$scandata[$Looping_Counter_file]. "<br>";
								//echo file_get_contents($scandata[$Looping_Counter_file]). "<br>";
								$filename = basename($scandata[$Looping_Counter_file]);
								$filenameoriginal = $file. '_' .$filename;
								//echo "original: ".($filenameoriginal). "<br>";
								$content_file = file_get_contents($scandata[$Looping_Counter_file]);
								 
								 
//-------merge ParseEDI code into here-----------------
$EDI_Content=$content_file;
//echo "file before running: ".$content_file. "</br>";
// echo "file after running: ".$EDI_Content. "</br>";
$arr_main_data = explode("^", $EDI_Content);
$Looping_Counter = 0;
$available_PO1=0;



while ($arr_main_data[$Looping_Counter] != null){
	$arr_segment_data = explode("*", $arr_main_data[$Looping_Counter]);
	$segment_name = $arr_segment_data[0];
    echo "current segment name: ".$segment_name. "<br>";
	$Looping_Counter = ++$Looping_Counter;
	
	switch($segment_name){
		case "ISA":
            $EDIROWID=uniqid();
            $ReceivedDate=date("Y/m/d");
            // $EDIROWID='abc';
            echo "EDI_Content=" .$EDI_Content. "<br>";
			$SenderISA = $arr_segment_data[6];
			$ReceiverISA = $arr_segment_data[8];
			//echo "SenderISA = " .$SenderISA. "<br>";
			//echo "ReceiverISA = " .$ReceiverISA. "<br>";           
            echo "EDIROWID = " .$EDIROWID. "<br>";    
            $sql = "INSERT INTO Inbox (EDIROWID,ReceivedDate,SenderISA, ReceiveISA,x12_Message) VALUES ('$EDIROWID','$ReceivedDate','$SenderISA','$ReceiverISA','$EDI_Content')";
            echo "ISA query:".$sql."<br>";
            if ($conn->query($sql) === TRUE) {
  echo "New record  created successfully";
} else {
  echo "Error: " . $sql . "<br>" . $conn->error;
}


        //     if (mysqli_query($conn, $sql)) {
        //         echo "New record created successfully";
        //   }
			break;
		case "GS":
			$SenderGSID = $arr_segment_data[2];
			$ReceiverGSID = $arr_segment_data[3];
			echo "SenderGSID = " .$SenderGSID. "<br>";
			echo "ReceiverGSID = " .$ReceiverGSID. "<br>";
            echo "EDIROWID = " .$EDIROWID. "<br>";    
            $sql="UPDATE INBOX
            SET SenderGSID = '$SenderGSID',ReceiverGSID='$ReceiverGSID'
            WHERE EDIROWID='$EDIROWID'";
            //echo "EDIROWID = " .$EDIROWID. "<br>"; 
            echo "GS query:".$sql."<br>";
            if (mysqli_query($conn, $sql)) {
                echo "New record created successfully";
          }
			break;            
        case "ST":
            $Transactions = $arr_segment_data[1];
            echo "Transactions = " .$Transactions. "<br>";
            echo "EDIROWID = " .$EDIROWID. "<br>";    
            $sql="UPDATE Inbox
            SET Transactions = '$Transactions'
            WHERE EDIROWID='$EDIROWID'";
            echo "ST query:".$sql."<br>";
            if (mysqli_query($conn, $sql)) {
                echo "New record created successfully";
          }
            break;
        case "BEG":
            $ID=uniqid();
            $PO_ID=$ID;
            echo "ID = " .$ID. "<br>";
            echo "EDIROWID = " .$EDIROWID. "<br>"; 
            $TransactionSetPurposeCode = $arr_segment_data[1];
            $PurchaseOrderTypeCode = $arr_segment_data[2];
            $PurchaseOrderNumber = $arr_segment_data[3];
            $ReleaseNumber = $arr_segment_data[4];
            $PODate = $arr_segment_data[5];
            $Contract_Number = $arr_segment_data[6];
            $Acknowledgment_Type = $arr_segment_data[7];
            $Invoice_Type_Code = $arr_segment_data[8];
            $Contract_Type_Code = $arr_segment_data[9];
            $Purchase_Category = $arr_segment_data[10];
            $SecurityLevelCode = $arr_segment_data[11];
            $TransactionTypeCode = $arr_segment_data[12];
            //error_reporting(E_ERROR | E_PARSE);
            echo "TransactionSetPurposeCode = " .$TransactionSetPurposeCode. "<br>";
            $sql = "INSERT INTO PURCHASE_ORDER 
            (ID,EDIROWID,TransactionSetPurposeCode,PurchaseOrderTypeCode,PurchaseOrderNumber,ReleaseNumber,PODate,Contract_Number,Acknowledgment_Type,Invoice_Type_Code,Contract_Type_Code,Purchase_Category,SecurityLevelCode,TransactionTypeCode) 
            VALUES ('$ID','$EDIROWID','$TransactionSetPurposeCode','$PurchaseOrderTypeCode','$PurchaseOrderNumber','$ReleaseNumber','$PODate','$Contract_Number','$Acknowledgment_Type','$Invoice_Type_Code','$Contract_Type_Code','$Purchase_Category','$SecurityLevelCode','$TransactionTypeCode')";
            echo "BEG query:".$sql."<br>";
            if (mysqli_query($conn, $sql)) {
                echo "New record created successfully";
          }
            $sql="UPDATE Inbox
            SET DocumentID = '$PurchaseOrderNumber'
            WHERE EDIROWID='$EDIROWID'";
            if (mysqli_query($conn, $sql)) {
                echo "New record created successfully";
          }
            break;
        case "CTT":
            $NumberOfLineItem = $arr_segment_data[1];
            echo "NumberOfLineItem = " .$NumberOfLineItem. "<br>";
            echo "ID = " .$PO_ID. "<br>";
            $sql="UPDATE PURCHASE_ORDER
            SET NumberOfLineItem = '$NumberOfLineItem'
            WHERE ID='$PO_ID'";
            echo "CTT query:".$sql."<br>";
            if (mysqli_query($conn, $sql)) {
                echo "New record created successfully";
          }
            break;
        case "REF":
            $ID=uniqid();
            echo "ID = " .$ID. "<br>";
            // echo "EDIROWID = " .$EDIROWID. "<br>"; 
            $ReferenceQualifier = $arr_segment_data[1];
            $ReferenceIdentification = $arr_segment_data[2];
            $Desctiption = $arr_segment_data[3];
            $sql = "INSERT INTO REFERENCE_INFORMATION (ID,PO_ID,ReferenceQualifier,ReferenceIdentification,Desctiption) 
            VALUES ('$ID','$PO_ID','$ReferenceQualifier','$ReferenceIdentification','$Desctiption')";
            echo "REF query:".$sql."<br>";
            if (mysqli_query($conn, $sql)) {
                echo "New record created successfully";
          }
            break;
        case "N1":
            echo "preparing to create new record<br>";
            $ID=uniqid();
            echo "ID Address= " .$ID. "<br>";
            $IDENTIFIER_CODE = $arr_segment_data[1];
            $NAME = $arr_segment_data[2];
            $IDENTIFIER_CODE_QUALIFIER = $arr_segment_data[3];
            $CODE = $arr_segment_data[4];
            echo "code is".$IDENTIFIER_CODE. "<br>";
                $sql = "INSERT INTO ADDRESS_DB (ID,PO_ID,IDENTIFIER_CODE,NAME,IDENTIFIER_CODE_QUALIFIER,CODE) 
                VALUES ('$ID','$PO_ID','$IDENTIFIER_CODE','$NAME','$IDENTIFIER_CODE_QUALIFIER','$CODE')";
             echo "N1 query:".$sql."<br>"; 
             if (mysqli_query($conn, $sql)) {
                echo "New record created successfully";
          }  
            break;
        case "N3":
            $ADDRESS1 = $arr_segment_data[1];
            $ADDRESS2 = $arr_segment_data[2];
            $sql="UPDATE ADDRESS_DB
            SET ADDRESS1 = '$ADDRESS1',ADDRESS2='$ADDRESS2'
            WHERE ID='$ID'";
             echo "N3 query:".$sql."<br>";
            if (mysqli_query($conn, $sql)) {
                echo "New record created successfully";
          }  
            break;
        case "N4":
            $CITY = $arr_segment_data[1];
            $STATE = $arr_segment_data[2];
            $POSTALCODE = $arr_segment_data[3];
            $COUNTRYCODE = $arr_segment_data[4];
            $sql="UPDATE ADDRESS_DB
            SET CITY = '$CITY',STATE='$STATE',POSTALCODE='$POSTALCODE',COUNTRYCODE='$COUNTRYCODE'
            WHERE ID='$ID'";
            echo "N4 query:".$sql."<br>";
              if (mysqli_query($conn, $sql)) {
                echo "New record created successfully";
          }  
            break;
        case "PER":
            $CONTACTFUNCTIONCODE = $arr_segment_data[1];
            $CONTACTNAME = $arr_segment_data[2];
            $COMMUNICATEQUALIFIER1 = $arr_segment_data[3];
            $COMMUNICATENUMBER1 = $arr_segment_data[4];
            $COMMUNICATEQUALIFIER2 = $arr_segment_data[5];
            $COMMUNICATENUMBER2 = $arr_segment_data[6];
            $COMMUNICATEQUALIFIER3 = $arr_segment_data[7];
            $COMMUNICATENUMBER3 = $arr_segment_data[8];
            echo "Transaction = " .$Transaction. "<br>";
            $sql="UPDATE ADDRESS_DB
            SET CONTACTFUNCTIONCODE = $CONTACTFUNCTIONCODE,CONTACTNAME=$CONTACTNAME,COMMUNICATEQUALIFIER1=$COMMUNICATEQUALIFIER1,COMMUNICATENUMBER1=$COMMUNICATENUMBER1,COMMUNICATEQUALIFIER2=$COMMUNICATEQUALIFIER2,COMMUNICATENUMBER2=$COMMUNICATENUMBER2,COMMUNICATEQUALIFIER3=$COMMUNICATEQUALIFIER3,COMMUNICATENUMBER3=$COMMUNICATENUMBER3
            WHERE ID='$ID'";
            if (mysqli_query($conn, $sql)) {
                echo "New record created successfully";
          }  
                
            break;
        case "DTM":
            $ID=uniqid();
            echo "ID DateTime= " .$ID. "<br>";
            $QUALIFIER = $arr_segment_data[1];
            $DATE = $arr_segment_data[2];
            $TIME = $arr_segment_data[3];
            $sql = "INSERT INTO DATETIMEINFORMATION (ID,PO_ID,QUALIFIER,IFDATE,IFTIME) 
            VALUES ('$ID','$PO_ID','$QUALIFIER','$DATE','$TIME')";
             if (mysqli_query($conn, $sql)) {
                echo "New record created successfully";
          } 
            break;
        case "PO1":
            $ID=uniqid();
            $LINE_ID=$ID;
            $available_PO1=1;
            $ASSIGNED_IDENTIFICATION = $arr_segment_data[1];
            $QUANTITY_ORDERED = $arr_segment_data[2];
            $UOM = $arr_segment_data[3];
            $UNIT_PRICE = $arr_segment_data[4];
            $BASIC_OF_UNIT_PRICE_CODE = $arr_segment_data[5];
            $PRODUCT_QUALIFIER1 = $arr_segment_data[6];
            $PRODUCT_CODE1 = $arr_segment_data[7];
            $PRODUCT_QUALIFIER2 = $arr_segment_data[8];
            $PRODUCT_CODE2 = $arr_segment_data[9];
            $PRODUCT_QUALIFIER3 = $arr_segment_data[10];
            $PRODUCT_CODE3 = $arr_segment_data[11];
            $PRODUCT_QUALIFIER4 = $arr_segment_data[12];
            $PRODUCT_CODE4 = $arr_segment_data[13];
            $PRODUCT_QUALIFIER5 = $arr_segment_data[14];
            $PRODUCT_CODE5 = $arr_segment_data[15];
            $PRODUCT_QUALIFIER6 = $arr_segment_data[16];
            $PRODUCT_CODE6 = $arr_segment_data[17];
            $PRODUCT_QUALIFIER7 = $arr_segment_data[18];
            $PRODUCT_CODE7 = $arr_segment_data[19];
            $PRODUCT_QUALIFIER8 = $arr_segment_data[20];
            $PRODUCT_CODE8 = $arr_segment_data[21];
            $PRODUCT_QUALIFIER9 = $arr_segment_data[22];
            $PRODUCT_CODE9 = $arr_segment_data[23];
            $PRODUCT_QUALIFIER10 = $arr_segment_data[24];
            $PRODUCT_CODE10 = $arr_segment_data[25];
            
            $sql = "INSERT INTO PURCHASE_ORDER_LINEITEM (ID,PO_ID,ASSIGNED_IDENTIFICATION,QUANTITY_ORDERED,UOM,UNIT_PRICE,BASIC_OF_UNIT_PRICE_CODE,PRODUCT_QUALIFIER1,PRODUCT_CODE1,PRODUCT_QUALIFIER2,PRODUCT_CODE2,PRODUCT_QUALIFIER3,PRODUCT_CODE3,PRODUCT_QUALIFIER4,PRODUCT_CODE4,
            PRODUCT_QUALIFIER5,PRODUCT_CODE5,PRODUCT_QUALIFIER6,PRODUCT_CODE6,PRODUCT_QUALIFIER7,PRODUCT_CODE7,PRODUCT_QUALIFIER8,PRODUCT_CODE8,PRODUCT_QUALIFIER9,PRODUCT_CODE9,PRODUCT_QUALIFIER10,PRODUCT_CODE10)
            VALUES ('$ID','$PO_ID','$ASSIGNED_IDENTIFICATION','$QUANTITY_ORDERED','$UOM','$UNIT_PRICE','$BASIC_OF_UNIT_PRICE_CODE','$PRODUCT_QUALIFIER1','$PRODUCT_CODE1','$PRODUCT_QUALIFIER2','$PRODUCT_CODE2','$PRODUCT_QUALIFIER3','$PRODUCT_CODE3','$PRODUCT_QUALIFIER4','$PRODUCT_CODE4',
            '$PRODUCT_QUALIFIER5','$PRODUCT_CODE5','$PRODUCT_QUALIFIER6','$PRODUCT_CODE6','$PRODUCT_QUALIFIER7','$PRODUCT_CODE7','$PRODUCT_QUALIFIER8','$PRODUCT_CODE8','$PRODUCT_QUALIFIER9','$PRODUCT_CODE9','$PRODUCT_QUALIFIER10','$PRODUCT_CODE10')";
            if (mysqli_query($conn, $sql)) {
                echo "New record created successfully";}
            break;
        case "CUR":
            $CurrencyIdentifierCode = $arr_segment_data[1];
            $CurrencyCode = $arr_segment_data[2];
            $ExchangeRate = $arr_segment_data[3];
            
                if($available_PO1!=1){
                    
                    $sql="UPDATE PURCHASE_ORDER
                    SET CurrencyIdentifierCode = '$CurrencyIdentifierCode',CurrencyCode='$CurrencyCode',ExchangeRate='$ExchangeRate'
                    WHERE ID='$ID'";
            
                }
                else{
                    
                    $sql="UPDATE PURCHASE_ORDER_LINEITEM
                    SET CurrencyIdentifierCode = '$CurrencyIdentifierCode',CurrencyCode='$CurrencyCode',ExchangeRate='$ExchangeRate'
                    WHERE ID='$ID'";
                }
                if (mysqli_query($conn, $sql)) {
                    echo "New record created successfully";}
            break;
        case "SAC":
            $AC_INDICATOR = $arr_segment_data[1];
            $AC_CODE = $arr_segment_data[2];
            $AGENCY_QUALIFIER_CODE = $arr_segment_data[3];
            $AGENCY_AC_CODE = $arr_segment_data[4];
            $AMOUNT = $arr_segment_data[5];
            $PERCENT_QUALIFIER = $arr_segment_data[6];
            $PERCENT = $arr_segment_data[7];
            $RATE = $arr_segment_data[8];
            $UOM = $arr_segment_data[9];
            $Quantity = $arr_segment_data[10];
            if($available_PO1!=1){
                $ID=uniqid();
            $sql = "INSERT INTO ALLOWANCE_CHARGE_PO (ID,PO_ID,AC_INDICATOR,AC_CODE,AGENCY_QUALIFIER_CODE,AGENCY_AC_CODE,AMOUNT,PERCENT_QUALIFIER,PERCENT_,RATE,UOM,Quantity) 
            VALUES ('$ID','$PO_ID','$AC_INDICATOR','$AC_CODE','$AGENCY_QUALIFIER_CODE','$AGENCY_AC_CODE','$AMOUNT','$PERCENT_QUALIFIER','$PERCENT','$RATE','$UOM','$Quantity')";           
            }
            else{
                $ID=uniqid();
            $sql = "INSERT INTO ALLOWANCE_CHARGE_LINEITEM (ID,LINE_ID,AC_INDICATOR,AC_CODE,AGENCY_QUALIFIER_CODE,AGENCY_AC_CODE,AMOUNT,PERCENT_QUALIFIER,PERCENT_,RATE,UOM,Quantity) 
            VALUES ('$ID','$LINE_ID','$AC_INDICATOR','$AC_CODE','$AGENCY_QUALIFIER_CODE','$AGENCY_AC_CODE','$AMOUNT','$PERCENT_QUALIFIER','$PERCENT','$RATE','$UOM','$Quantity')";    
            }
            if (mysqli_query($conn, $sql)) {
                echo "New record created successfully";}
            break;
        case "PID":
            $Item_Description = $arr_segment_data[5];
            $sql="UPDATE PURCHASE_ORDER_LINEITEM
            SET Item_Description = '$Item_Description'
            WHERE ID='$ID'";
            if (mysqli_query($conn, $sql)) {
                echo "New record created successfully";}
            break;
        }
    //     if (mysqli_query($conn, $sql)) {
    //         echo "New record created successfully";
    //   } else {
    //         echo "Error: " . $sql . "<br>" . mysqli_error($conn);
    //   }
      
    }
    
    //mysqli_close($conn);
								 
//-------end Merge ParseEDI-------------------------------
								 
								//echo ($content_file). "<br>";
								chdir("./../");
								chdir('Processing');
								file_put_contents($filenameoriginal,$content_file);
								chdir("./../");
								chdir($file);
								unlink($scandata[$Looping_Counter_file]);
								echo"<tr>";
								echo"<th>".$file."</th>";
								echo"<th>".$filename."</th>";
								echo"<th>Moved to Processing</th>";
								echo <<<GFG
                                   <th><button class="myBtn">ViewContent</button></th>;
								   <div class="modal">
										<!-- Modal content -->
										<div class="modal-content">
										<span class="close">&times;</span>
										<p>{$content_file}</p>
										</div>
									</div>
GFG;
								echo"</tr>";
							}
							$Looping_Counter_file = ++$Looping_Counter_file;
						}
						chdir("./../");
				// 		$dir = getcwd();
				// 		echo $dir ;
					}
				}
			}
		}
		mysqli_close($conn);
// // 		echo $dir ;
// 		chdir("Outbox");
//         $folder_Outbox = getcwd();
//         // echo  $dir3;
//         $scandata_810 = scandir($folder_Outbox);
// 		print_r($scandata_810);
        
	?>
			</table>
		</div>
		<div class="footer">
			<div class="footer_above">
				<p style="text-align:center">NGHIÊN CỨU GIẢI PHÁP EDI TRONG NGÀNH BÁN LẺ VÀ THIẾT KẾ HỆ THỐNG EDI TRONG CHUỖI CUNG ỨNG</p>
			</div>
			<div class="footer_below">
				<div class="footer_image_below">
					<img src="/Picture/Economics_logo.jpg" width="250" height="250" >
				</div>
				<div class="footer_content_center">
					<p style="text-align:left">SINH VIÊN THỰC HIỆN:</p>
					<p style="text-align:left">NGUYỄN THỊ NHƯ Ý - 44k21.2</p>
					<p style="text-align:left">NGUYỄN THỊ TỐ NHƯ - 44k14</p>
				</div>
			</div>
		</div>
	</div>
<script>
// Get the modal
var modals = document.getElementsByClassName("modal");

// Get the button that opens the modal
var btns = document.getElementsByClassName("myBtn");

// Get the <span> element that closes the modal
var spans = document.getElementsByClassName("close");

// When the user clicks the button, open the modal 
for(let i=0;i<btns.length;i++){
   btns[i].onclick = function() {
      modals[i].style.display = "block";
   }
}

// When the user clicks on <span> (x), close the modal
for(let i=0;i<spans.length;i++){
    spans[i].onclick = function() {
       modals[i].style.display = "none";
    }
 }

// When the user clicks anywhere outside of the modal, close it
window.onclick = function(event) {
	for(let i=0;i<btns.length;i++){
		if (event.target == modals[i]) {
			modals[i].style.display = "none";
		}
	}
}
</script>
</body>
</html>   