<?php
session_start();
 require '../config/config.php';
require '../config/common.php';

if(empty($_SESSION['user_id'])&& empty($_SESSION['logged_in'])){
	header('Location: login.php');
}
if($_SESSION['role']!=1){
		header('Location: login.php');
}

if($_POST){
		if(empty($_POST['name']) ||  empty($_POST['description'])){
		
		if(empty($_POST['name'])){
			$titleError='Name cannot be null';
		}
		if(empty($_POST['description'])){
			$contentError='Description cannot be null';
		}
		
	}else{
	$id=$_POST['id'];
	$name=$_POST['name'];
	$description=$_POST['description'];
	
	
	
		$stmt=$pdo->prepare("UPDATE categories SET name=:name, description=:description WHERE id=:id");
			 $result = $stmt->execute(
          array(':name'=>$name,':description'=>$description,':id'=>$id)
      );
			if($result){
			echo  "<script>alert('Updated Successfully');window.location.href='category.php';</script>";
						}
		
		}
	
}


?>

<?php include('header.php'); ?>

    <!-- Main content -->
    <div class="content">
      <div class="container-fluid">
        <div class="row">
         <div class="col-md-12">
            
            <div class="card card-primary">
              <div class="card-header">
                <h3 class="card-title">Update your category </h3>
              </div>
              <!-- /.card-header -->
              <!-- form start -->
				<div class="card card-body">
				<?php 
				$stmt=$pdo->prepare("SELECT * FROM categories WHERE id=".$_GET['id']);
				$stmt->execute();
				$result=$stmt->fetchAll();
				?>
              <form class="" action="" method="post" enctype="multipart/form-data">
				 <input type="hidden" name="_token" value="<?php echo $_SESSION['_token']; ?>">

				  <input type="hidden" name="id" value="<?php echo $result[0]['id']; ?>"
                <div class="card-body">
                  <div class="form-group">
                    <label for="title">Name</label><p style="color: red"><?php echo empty($titleError)? '': '*'. $titleError; ?></p>
                    <input type="text" class="form-control" name="name" value="<?php echo escape($result[0]['name']); ?>" >
                  </div>
                  <div class="form-group">
                    <label for="content">Description</label><p style="color: red"><?php echo empty($contentError)? '': '*'.$contentError; ?></p>
                    <input type="text" class="form-control" name="description" value="<?php echo escape($result[0]['description']); ?>">
                  </div>
                 
					<!--<div class="form-group">
					<label for="image">Photo</label><br>
					<input type="file" name="image" value="" required>
					</div>-->
                  
                </div>
                <!-- /.card-body -->

                <div class="card-footer">
                  <input type="submit" class="btn btn-primary" value="Submit">
					   <a href="category.php" type="button" class="btn btn-secondary">Back</a>
                </div>
              </form>
				</div>
            </div>

            
            <!-- /.card -->
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
<aside class="control-sidebar control-sidebar-dark">
  <!-- Control sidebar content goes here -->
  <div class="p-3">
    <h5>Title</h5>
    <p>Sidebar content</p>
  </div>
</aside>
<!-- /.control-sidebar -->

<!-- Main Footer -->
<footer class="main-footer">
  <!-- To the right -->
  <div class="float-right d-none d-sm-inline">
    <a href="logout.php" type="button" class="btn btn-default">Logout</a>
  </div>
  <!-- Default to the left -->
  <strong>Copyright &copy; 2020 <a href="#">A Programmer</a>.</strong> All rights reserved.
</footer>
</div>
<!-- ./wrapper -->

<!-- REQUIRED SCRIPTS -->

<!-- jQuery -->
<script src="plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<script src="dist/js/adminlte.min.js"></script>
</body>
</html>
