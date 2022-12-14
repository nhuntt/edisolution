<?
	$EDI_Content = "ISA*00*          *00*          *01*963348037P     *ZZ*IBCGROUP       *220309*2227*U*00400*000000055*0*P*>^GS*SH*963348037P*IBCGROUP*20220309*2227*55*X*004010^ST*856*000000026^BEG*PO*00*PO0001^";
	$arr_main_data = explode("^", $EDI_Content);
	$Looping_Counter = 0;
	while($arr_main_data[$Looping_Counter] != null){
		$arr_segment_data = explode("*", $arr_main_data[$Looping_Counter]);
		$Looping_Counter = ++$Looping_Counter;
		$segment_name = $arr_segment_data[0];
		switch($segment_name){
			case "ISA":
				$SenderISA = $arr_segment_data[6];
				$ReceiverISA = $arr_segment_data[8];
				echo "senderISA = " .$SenderISA."<br>";
				echo "SenderISA = " .$ReceiverISA. "<br>";
				break;
			case "GS":
				$SenderGSID = $arr_segment_data[2];
				$ReceiverGSID = $arr_segment_data[3];
				echo "SenderGSID = " .$SenderGSID."<br>";
				echo "ReceiverGSID = " .$ReceiverGSID. "<br>";
				break;
			case "BEG":
				$DocumentID = $arr_segment_data[3];
				echo "DocumentID = " .$DocumentID."<br>";
				break;
			case "ST":
				$Transaction = $arr_segment_data[1];
				echo "Transaction = " .$Transaction."<br>";
				break;
		}
	}
?>