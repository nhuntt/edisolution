<?php
// $title = 'View Record'; 
// $servername='127.0.0.1';
// $database='edi_solution';
// $username="root";
// $password="Xiu@16031977";

$conn = mysqli_connect('localhost', 'u269067746_root', 'Tonhu@1603', 'u269067746_EDI_SOLUTION');
// Check connection
if (mysqli_connect_error()){
    echo "connection fail".mysqli_connect_error();
}
else { echo "connection successfully";};

$conn = mysqli_connect($servername, $username, $password, $database);
$vCount_header = 0;
if(!isset($_GET['EDIROWID'])){
    echo "fail";
} else{
    $id = $_GET['EDIROWID'];
}
// $sql = "SELECT EDIROWID, SenderISA FROM Inbox ";
$sql2 = "SELECT po.PODate, po.PurchaseOrderNumber, PO.CurrencyCode, PO.ExchangeRate,
ADDBY.NAME as AD_Name_BY,ADDBY.CODE as AD_CODE_BY,ADDBY.ADDRESS1 as AD_ADDRESS1_BY,ADDBY.ADDRESS2 as AD_ADDRESS2_BY,ADDBY.CITY as AD_CITY_BY,ADDBY.STATE as AD_STATE_BY,ADDBY.POSTALCODE as AD_POSTALCODE_BY,ADDBY.COUNTRYCODE as AD_COUNTRYCODE_BY,
ADDST.NAME as AD_Name_ST,ADDST.CODE as AD_CODE_ST,ADDST.ADDRESS1 as AD_ADDRESS1_ST,ADDST.ADDRESS2 as AD_ADDRESS2_ST,ADDST.CITY as AD_CITY_ST,ADDST.STATE as AD_STATE_ST,ADDST.POSTALCODE as AD_POSTALCODE_ST,ADDST.COUNTRYCODE as AD_COUNTRYCODE_ST,
RFFDP.ReferenceIdentification as REFDP,RFFZZ.ReferenceIdentification as REFZZ,
DT01.DATE as DT01 ,DT02.DATE as DT02,
ACP.AC_INDICATOR as AC_INDICATOR_P,ACP.AMOUNT as AMOUNT_P,ACP.PERCENT as PERCENT_P,
POL.ASSIGNED_IDENTIFICATION,POL.PRODUCT_CODE1,POL.PRODUCT_CODE2,POL.QUANTITY_ORDERED,POL.UNIT_PRICE,POL.Item_Description,
ACL.AC_INDICATOR as AC_INDICATOR_L,ACL.AMOUNT as AMOUNT_L,ACL.PERCENT as PERCENT_L from inbox INB 
INNER join purchase_order PO on INB.EDIROWID=PO.EDIROWID
LEFT JOIN address_db ADDBY on PO.ID=ADDBY.PO_ID
LEFT JOIN address_db ADDST on PO.ID=ADDST.PO_ID
LEFT JOIN reference_information RFFDP ON PO.ID=RFFDP.PO_ID
LEFT JOIN reference_information RFFZZ ON PO.ID=RFFZZ.PO_ID
LEFT JOIN datetimeinformation DT01 ON PO.ID=DT01.PO_ID
LEFT JOIN datetimeinformation DT02 ON PO.ID=DT02.PO_ID
LEFT JOIN allowance_charge_po ACP ON PO.ID=ACP.PO_ID
INNER JOIN purchase_order_lineitem POL ON PO.ID=POL.PO_ID
LEFT JOIN allowance_charge_lineitem ACL ON POL.ID=ACL.LINE_ID

WHERE INB.EDIROWID='$id'
AND ADDBY.IDENTIFIER_CODE='BY'
AND ADDST.IDENTIFIER_CODE='ST'
AND RFFDP.ReferenceQualifier='DP'
AND RFFZZ.ReferenceQualifier='ZZ'
AND DT01.Qualifier='001'
AND DT02.Qualifier='002'";
 $result = $conn->query($sql2);
// if ($result->num_rows > 0) { 
    // echo "<p>".$result."</p>";
    // echo "<table><tr><th>EDIROWID</th><th>SenderISA</th></tr>";
    // output data of each row
 //   while($row = $result->fetch_assoc()) { 
  //      $vCount_header = $vCount_header + 1;

  ?>
  



<!-- <div style="width: 18rem;">
    <div >
        <p>
            EDIROWID: 
        </p>

    </div>
</div> -->
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">

<html>
<head>
	
	<meta http-equiv="content-type" content="text/html; charset=iso-8859-1"/>
	<title>Purchase Order</title>
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
	<link rel="stylesheet" href="style.css">
	<style type="text/css">
		body,div,table,thead,tbody,tfoot,tr,th,td,p { font-family:"Trebuchet MS"; font-size:x-small }
		a.comment-indicator:hover + comment { background:#ffffdd; position:absolute; display:flex; border:1px solid black; padding:0.5em;  } 
		a.comment-indicator { background:red; display:flex; border:1px solid black; width:0.5em; height:0.5em;  } 
		comment { display:none;  } 
	</style>
	 
</head>

<body>
<?php if ($result->num_rows > 0){
    $vsubtotal=0;
        while($row = $result->fetch_assoc()) { 
            $vCount_header = $vCount_header + 1;
            if ($vCount_header == 1) {
                $vGeneral_Comments = $row["REFZZ"];?>
<div>
<table cellspacing="0" border="0">
	<tr>
		<td style="background-color:  #136a8a"><b>VENDOR NAME</b></td>
		
		<td style="background-color:  #136a8a"><b>PURCHASE ORDER</b></td>
	</tr>
</table>
</div>
<br>
<table>
	<tr>
		<td>CURRENTCY CODE</td>
		<td><?php echo $row["CurrencyCode"]; ?></td>
		<td>PO DATE</td>
		<td><?php echo $row["PODate"]; ?></td>
	</tr>
	<tr>
		<td>ExchangeRate</td>
		<td><?php echo $row["ExchangeRate"]; ?></td>
		<td>PO NUMBER</td>
		<td><?php echo $row["PurchaseOrderNumber"]; ?></td>
	</tr>
</table>

<table>
	<tr>
		<td style="background-color:  #136a8a"><b>BUYER</b></td>
	</tr>
	<tr>
		<td>Name</td>
		<td><?php echo $row["AD_Name_BY"]; ?></td>
	</tr>
	<tr>
		<td><?php echo $row["AD_CODE_BY"]; ?></td>
		<td><br></td>
	</tr>
	<tr>
		<td>Street</td>
		<td><?php echo $row["AD_ADDRESS1_BY"].", ".$row["AD_ADDRESS2_BY"]; ?></td>
		<td><br></td>

	</tr>
	<tr>
		<td >CITY</td>
		<td ><?php echo $row["AD_CITY_BY"]; ?></td>
		<td><br></td>
	</tr>
	<tr>
		<td> STATE</td>
		<td><?php echo $row["AD_STATE_BY"]; ?></td>
		<td><br></td>

	</tr>
	<tr>
		<td>ZIPCODE</td>
		<td><?php echo $row["AD_POSTALCODE_BY"]; ?></td>
		<td><br></td>

	</tr>
	<tr>
		<td>POSTAL CODE</td>
		<td><?php echo $row["AD_COUNTRYCODE_BY"]; ?></td>
		<td><br></td>
	</tr>
</table>
<table>
	<tr>
		<td style="background-color:  #136a8a"><b>BuyerCode</b></td>
		<td>SHIP TO</td>
	</tr>
	<tr>
		<td>Name</td>
		<td><?php echo $row["AD_Name_ST"]; ?></td>
	</tr>
	<tr>

		<td>Code</td>
		<td><?php echo $row["AD_CODE_ST"]; ?></td>
	</tr>
	<tr>

		<td>Street</td>
		<td><?php echo $row["AD_ADDRESS1_ST"].", ".$row["AD_ADDRESS2_ST"]; ?></td>
	</tr>
	<tr>

		<td>CITY</td>
		<td><?php echo $row["AD_CITY_ST"]; ?></td>
	</tr>
	<tr>

		<td>STATE</td>
		<td><?php echo $row["AD_STATE_ST"]; ?></td>
	</tr>
	<tr>

		<td>ZIPCODE</td>
		<td><?php echo $row["AD_POSTALCODE_ST"]; ?></td>
	</tr>
	<tr>
		<td>POSTAL CODE</td>
		<td><?php echo $row["AD_COUNTRYCODE_ST"]; ?></td>
	</tr>
</table>
<table>
	<tr>
		<td height="40">><br></td>
	</tr>
	<tr>
		<td><b>DEPARTMENT NO</b></td>
		<td><b>EXPECTED DELIVERY DATE</b></td>
		<td><b>CANCEL AFTER</b></td>
		<td><b>A/C IDENTIFY</b></td>
		<td><b>AMOUNT</b></td>
		<td><b>PERCENT</b></td>

	</tr>
	<tr>
		<td height="24" ><?php echo $row["REFDP"]; ?></td>
		<td ><?php echo $row["DT01"]; ?></td>
		<td ><?php echo $row["DT02"]; ?></td>
		<td ><?php echo $row["AC_INDICATOR_P"]; ?></td>
		<td>><?php echo $row["AMOUNT_P"]; ?></td>
		<td >><?php echo $row["PERCENT_P"]; ?></td>
		<td> ><br></td>
		<td valign=bottom><br></td>
		<td> ><br></td>
		<td valign=bottom><br></td>
	</tr>
	<tr>
		<td height="20"><br></td>
	</tr>
	</table>
<table>
	<tr>
		<td  height="24"  sdnum="1033;0;0.00%"><b>ITEM #</b></td>
		<td sdnum="1033;0;0.00%"><b>PRODUCTCODE1</b></td>
		<td sdnum="1033;0;0.00%"><b>PRODUCTCODE2</b></td>
		<td sdnum="1033;0;0.00%"><b>DESCRIPTION</b></td>
		<td  ><b>QTY</b></td>
		<td  ><b>UNIT PRICE</b></td>
		<td  ><b>TOTAL</b></td>
		<td  ><b>A/C IDENTIFY</b></td>
		<td><b>AMOUNT</b></td>
		<td><b>PERCENT</b></td>
	</tr>
<?php } ?> <!-- end of group if count = 1-->
	<tr>
		<td height="20">><?php echo $row["ASSIGNED_IDENTIFICATION"]; ?></td>
		<td><?php echo $row["PRODUCT_CODE1"]; ?></td>
		<td><?php echo $row["PRODUCT_CODE2"]; ?></td>
		<td><?php echo $row["Item_Description"]; ?></td>
		<td ><?php $vQuantity = $row["QUANTITY_ORDERED"]; echo $vQuantity; ?></td>
		<td> <?php $vUnit_Price = $row["UNIT_PRICE"]; echo $row["UNIT_PRICE"]; ?> </td>
		<td><?php $Price = $vUnit_Price; $Price = $Price*$vQuantity; echo $Price; $vsubtotal = $vsubtotal + $Price;?> </td>
		<td ><?php echo $row["AC_INDICATOR_L"]; ?></td>
		<td ><?php echo $row["AMOUNT_L"]; ?></td>
		<td ><?php echo $row["PERCENT_L"]; ?></td>
	</tr>
<?php } ?> <!-- end of group while fetch row of result-->
	<tr>
		
		<td >SUBTOTAL</td>
		<td ><?php echo $vsubtotal; ?></td>
	</tr>
	<tr>
		<td><b>>Comments or Special Instructions</b></td>
		<td>>TAX</td>
	</tr>
	<tr>
		<td style="border-top: 1px solid #a6a6a6; border-left: 1px solid #a6a6a6; border-right: 1px solid #a6a6a6" colspan=4 height="24">><?php echo $vGeneral_Comments; ?></td>
		<td><br></td>
		<td>>SHIPPING</td>
	</tr>
	<tr>
		<td><br></td>
		<td style="border-bottom: 2px double #000000">>OTHER</td>

	</tr>
	<tr>
		<td><br></td>
		<td><b>>TOTAL</b></td>
	</tr>

	<!-- <tr>
		<td style="border-bottom: 1px solid #a6a6a6; border-left: 1px solid #a6a6a6; border-right: 1px solid #a6a6a6" colspan=4 height="24">><br></td>
		<td><br></td>
		<td valign=bottom><br></td>
		<td valign=bottom><br></td>
		<td valign=bottom><br></td>
		<td valign=bottom><br></td>
		<td valign=bottom><br></td>
	</tr> -->
</table>
<!-- ************************************************************************** -->


<?php } ?> <!-- end of group IF result > 0-->

</body>

</html>


