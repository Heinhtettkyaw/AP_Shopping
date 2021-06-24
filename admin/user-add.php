<?php
session_start();
require '../config/config.php';
require '../config/common.php';
if(empty($_SESSION['user_id'])&& empty($_SESSION['logged_in'])){
	header('Location: login.php');}
if($_SESSION['role']!=1){
		header('Location: login.php');
}
 if($_POST){
	 if(empty($_POST['name']) ||  empty($_POST['email']) || empty($_POST['password']) || strlen($_POST['password'])<6 || empty($_POST['phone']) || empty($_POST['address'])){

		if(empty($_POST['name'])){
			$nameError='Title cannot be null';
		}
		if(empty($_POST['email'])){
			$emailError='Content cannot be null';
		}
		if(empty($_POST['password'])){
			$passwordError='Password cannot be null';
		}
		 if(strlen($_POST['password']) <6){
			$passwordError= 'Password Should be 5 characters at least';
		}if(empty($_POST['phone'])){
			$phoneError='Phone Number cannot be null';
		}
		 if(empty($_POST['address'])){
			$addressError='Address cannot be null';
		}

	}else{

	  $name= $_POST['name'];
	  $password=password_hash( $_POST['password'],PASSWORD_DEFAULT);
	  $email= $_POST['email'];
      $phone=$_POST['phone'];
	  $address=$_POST['address'];

	 if(empty($_POST['role'])){
		 $role=0;
	 }
	 else{
		 $role=1;
	 }

	$stmt= $pdo->prepare("SELECT * FROM users WHERE email=:email");
	$stmt->bindValue(':email',$email);
	$stmt->execute();
	$user=$stmt->fetch(PDO::FETCH_ASSOC);

	if($user){
		echo "<script>alert('Email already registered')</script>";
	}
    else
	{
		$stmt=	$pdo->prepare("INSERT INTO users (name,password,email,phone,address,role) VALUES (:name,:password,:email,:phone,:address,:role)");
		$result=$stmt->execute(
		array(':name'=>$name,
			  ':email'=>$email,
			  ':password'=>$password,
			  ':phone'=>$phone,
			  ':address'=>$address,
			  ':role'=>$role
			 ));

		if($result){
			echo  "<script>alert('Successfully registered, Please Login');window.location.href='users.php';</script>";
		}

	}
	}

 }
?>
 <?php include('header.php'); ?>
    <!-- /.content-header -->

    <!-- Main content -->
    <div class="content">
      <div class="container-fluid">
        <div class="row">
         <div class="col-md-12">
              <form action="user-add.php" method="post">
			    <input type="hidden" name="_token" value="<?php echo $_SESSION['_token']; ?>">

                <div class="form-group">
                    <label for="">Name</label><p style="color: red"><?php echo empty($nameError)? '': '*'.$nameError; ?></p>
                    <input type="text" class="form-control" name="name" >
                </div>
                <div class="form-group">
                    <label for="">Email</label><p style="color: red"><?php echo empty($emailError)? '': '*'.$emailError; ?></p>
                    <input type="email" class="form-control" name="email" >
                </div>
                <div class="form-group">
                    <label for="">Password</label><p style="color: red"><?php echo empty($passwordError)? '': '*'.$passwordError; ?></p>
                    <input type="password" class="form-control" name="password" >
                </div>
				  <div class="form-group">
				  <label for="">Phone Number</label><p style="color: red"><?php echo empty($phoneError)? '': '*'.$phoneError; ?></p>
					  <input type="number" class="form-control" name="phone">
				  </div>
				  <div class="form-group">
				  <label for="">Address</label><p style="color: red"><?php echo empty($addressError)? '': '*'.$addressError; ?></p>
					  <input type="text" class="form-control" name="address">
				  </div>
                <div class="form-check mb-3">
                  <input type="checkbox" class="form-check-input" name="role" value="1">
                  <label class="form-check-label"> : Check Users will be ADMIN</label>
                </div>
                <input type="submit" class="btn btn-success">
                <a href="users.php" class="btn btn-warning">Back</a>
            </form>




          </div>
          <!-- /.col-md-6 -->
        </div>
        <!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

  <!-- Control Sidebar -->
<?php include('footer.html'); ?>
