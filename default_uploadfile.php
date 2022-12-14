<?php
//$file = 'C:\Users\nhung\Downloads\856_035239425_DICWCBRLLF_TEST479964_211201_20.txt';
$file = 'style.css';
$remote_file = '856_CHRI_DICWCBVLLF_KKLUSH4380482_220408_6.txt';
$ftp_server='ftp.edisolution.online';
$ftp_user_name='u269067746.capacity';
$ftp_user_pass='Xiu@16031977';
// set up basic connection
$ftp = ftp_connect($ftp_server);
$openfile=fopen($file,'r');
echo fread($openfile,filesize($file));
fclose($openfile);
// echo $content;

// login with username and password
$login_result = ftp_login($ftp, $ftp_user_name, $ftp_user_pass);

// upload a file
if (ftp_put($ftp, $remote_file, $file, FTP_ASCII)) {
 echo "successfully uploaded $file\n";
} else {
 echo "There was a problem while uploading $file\n";
}

// close the connection
ftp_close($ftp);
?>