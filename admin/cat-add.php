<?php
session_start();
require '../config/config.php';
require '../config/common.php';

if (empty($_SESSION['user_id']) && empty($_SESSION['logged_in'])) {
  header('Location: login.php');
}
if ($_SESSION['role'] != 1) {
  header('Location: login.php');
}     

if ($_POST){
	
	 if (empty($_POST['description']) || empty($_POST['name']))  {
        if (empty($_POST['name'])) {
          $titleError = 'Name cannot be null';
        }
        if (empty($_POST['description'])) {
          $contentError = 'Description cannot be null';
        }
       
      }else
		{
      $name = $_POST['name'];
      $description = $_POST['description'];
    

      $stmt = $pdo->prepare("INSERT INTO categories(name,description) VALUES (:name,:description)");
      $result = $stmt->execute(
          array(':name'=>$name,':description'=>$description)
      );
      if ($result) {
        echo "<script>alert('Successfully added');window.location.href='category.php';</script>";
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
            <div class="card">
				 <div class="card-header">
                <h3 class="card-title">Description</h3>
              </div>
              <div class="card-body">
                <form class="" action="cat-add.php" method="post" enctype="multipart/form-data">
                  <input name="_token" type="hidden" value="<?php echo $_SESSION['_token']; ?>">
                  <div class="form-group">
                    <label for="">Name</label><p style="color:red"><?php echo empty($titleError) ? '' : '*'.$titleError; ?></p>
                    <input type="text" class="form-control" name="name" value="">
                  </div>
                  <div class="form-group">
                    <label for="">Description</label><p style="color:red"><?php echo empty($contentError) ? '' : '*'.$contentError; ?></p>
                    <textarea class="form-control" name="description" rows="8" cols="80"></textarea>
                  </div>
                
                  <div class="form-group">
                    <input type="submit" class="btn btn-success" name="" value="SUBMIT">
                    <a href="category.php" class="btn btn-warning">Back</a>
                  </div>
                </form>
              </div>
            </div>
            <!-- /.card -->
          </div>
        </div>
        <!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content -->
  <?php include('footer.html')?>