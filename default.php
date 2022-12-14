<?php
 session_start();
//error_reporting(0); - active--------------------------------------------------------------------
// $dsn="mysql:host=localhost;dbname=edi_solution";
$conn = mysqli_connect('localhost', 'u269067746_root', 'Tonhu@1603', 'u269067746_EDI_SOLUTION');
// Check connection
if (mysqli_connect_error()){
    echo "connection fail".mysqli_connect_error();
}
// else { echo "connection successfully";};

   
    if(isset($_POST['Login']))
    {
       if(empty($_POST['username']) || empty($_POST['password']))
       {
            header("location:index.php?Empty= Please Fill in the Blanks");
       }
       else
       {
            $query="select * from ADMIN_ACCOUNT where username ='".$_POST['username']."' and password='".$_POST['password']."'";
            $result=mysqli_query($conn,$query);
			if(mysqli_fetch_assoc($result))
            {
                $_SESSION['User']=$_POST['username'];
                header("location:parseEDI.php");
            }
		
       }
    }
    // else
    // {
    //     echo 'Not Working Now Guys';
    // }

  
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>EDI SOLUTION</title>
	<!-- Import Boostrap css, js, font awesome here -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css">       
    <link href="https://use.fontawesome.com/releases/v5.0.4/css/all.css" rel="stylesheet">    
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js">
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js">
    </script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>
    <link href="style.css" rel="stylesheet">
</head>
<body>
	<div class="d-flex align-items-center justify-content-center div-LoginPage-bg"
	>
	<div class="div-LoginPage-Login" >
		<div class="div-LoginPage-LgC" >
			<p class="p-LoginPage-title" > Đăng nhập </p>
			<div class="div-LoginPage-form">
				<form method="POST" action="#">
				<!-- <form onSubmit={handleSubmit}> -->
					<div class="div-LoginPage-ctnInput" >
						<label class="label-LoginPage-title" >
							Tên Đăng Nhập
						</label>
						<br />
						<input
							type="text"
							 name="username"
						
							class="input-LoginPage-input"
						/>
					</div>
					<div class="div-LoginPage-ctnInput2">
						<label class="label-LoginPage-title" c>Mật khẩu</label>
						<br />
						<input
							type="password"
							name="password"
							
							class="input-LoginPage-input" 
						/>
					</div>
					<button class="button-LoginPage" name="Login" > Đăng nhập </button>
				</form>
			</div>
		</div>
	</div>
</div>

</body>
</html>	
