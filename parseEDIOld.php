<?php
//error_reporting(0); - active--------------------------------------------------------------------
// $dsn="mysql:host=localhost;dbname=edi_solution";
$servername='127.0.0.1:3306';
$database='u269067746_EDI_SOLUTION';
$username="u269067746_root";
$password="Xiu@16031977";

// }
//Create connection
$conn = mysqli_connect($servername, $username, $password, $database);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " 
        . $conn->connect_error);
}
    echo "Connected successfully on parseEDI"."<br>";
   

//$EDI_Content = "ISA*00*          *00*          *01*833123370      *01*DUMMYID4       *210302*2213*U*00401*000002968*0*P*|^GS*PO*833123370*DUMMYID4*20210302*2213*436*X*004010^ST*850*0076^BEG*00*SA*4511721383**20210527***IEL^CUR*BY*CAN^REF*DP*DP127^REF*ZZ*intruction^SAC*A*D240*VI*OHRO*50^DTM*001*20220319^DTM*002*20220520^N1*BY*John Deere C&F, Division of Deere ^N3*PO BOX 8808^N4*MOLINE*IL*612668808*US^N1*ST*John Deere C&F Dubuque Works*1*005269527^N3*18600 South John Deere Road^N4*DUBUQUE*IA*520019746*US^PO1*00010*3*PC*451.33*TE*BP*QAT491406B*EC*B^CUR*BY*USD^PID*F****Oil Cooler, HYDRAULIC OIL COOLER 1050^PO1*00020*1*PC*15514.64*TE*BP*QAT491406NRE^CUR*BY*USD^PID*F****Payment for test, HOC thermal cycle tes^SAC*C*D360*VI*REGH*10^CTT*2*4^SE*79*0076^GE*1*436^IEA*1*000002968^";
$EDI_Content=$content_file;
echo "noi dung file tai bien parseEDI: ". $EDI_Content. "</br>";
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
            $sql = "INSERT INTO INBOX (EDIROWID,ReceivedDate,SenderISA, ReceiveISA,x12_Message) VALUES ('$EDIROWID','$ReceivedDate','$SenderISA','$ReceiverISA','$EDI_Content')";
            echo "ISA query:".$sql."<br>";
            if (mysqli_query($conn, $sql)) {
                echo "New record created successfully";
          }
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
            $sql="UPDATE INBOX
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
            $sql="UPDATE INBOX
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
            $sql = "INSERT INTO DATETIMEINFORMATION (ID,PO_ID,QUALIFIER,DATE,TIME) 
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
            $sql = "INSERT INTO ALLOWANCE_CHARGE_PO (ID,PO_ID,AC_INDICATOR,AC_CODE,AGENCY_QUALIFIER_CODE,AGENCY_AC_CODE,AMOUNT,PERCENT_QUALIFIER,PERCENT,RATE,UOM,Quantity) 
            VALUES ('$ID','$PO_ID','$AC_INDICATOR','$AC_CODE','$AGENCY_QUALIFIER_CODE','$AGENCY_AC_CODE','$AMOUNT','$PERCENT_QUALIFIER','$PERCENT','$RATE','$UOM','$Quantity')";           
            }
            else{
                $ID=uniqid();
            $sql = "INSERT INTO ALLOWANCE_CHARGE_LINEITEM (ID,LINE_ID,AC_INDICATOR,AC_CODE,AGENCY_QUALIFIER_CODE,AGENCY_AC_CODE,AMOUNT,PERCENT_QUALIFIER,PERCENT,RATE,UOM,Quantity) 
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
    mysqli_close($conn);
  
?>
