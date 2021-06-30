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

	 if (empty($_POST['description']) || empty($_POST['name']) || empty($_POST['category']) || empty($_POST['quantity']) || empty($_POST['price'])|| empty($_FILES['image']))
    {
        if (empty($_POST['name'])) {
          $nameError = 'Name cannot be null';
        }
        if (empty($_POST['description'])) {
          $descError = 'Description cannot be null';
        }
        if (empty($_POST['category'])){
          $catError= 'Category is required';
        }
        if (empty($_POST['quantity'])){
          $qtyError= 'Instock is required';
        }  elseif (is_numeric($_POST['quantity'])!=1 )
    		{
          $qtyError='Quantity  must be integer';
        }

        if (empty($_POST['price'])){
          $priceError= 'Price is required';
        }  elseif(is_numeric($_POST['price']) !=1){
            $priceError= 'Price should be interger value';
          }

        if (empty($_FILES['image'])){
          $imgError= 'Image is required';
        }
    }
    else{
     $file= "images/" .($_FILES['image']['name']);
		 $imageType=pathinfo($file,PATHINFO_EXTENSION);
		 if($imageType != 'png' && $imageType != 'jpg' && $imageType != 'jpeg' ){
			echo  "<script>alert('Image Extension must be jpeg,jpg,png')</script>";
		}
      else {
        $name= $_POST['name'];
        $desc= $_POST['description'];
        $category= $_POST['category'];
        $qty= $_POST['quantity'];
        $price= $_POST['price'];
        $image=$_FILES['image']['name'];

        move_uploaded_file($_FILES['image']['tmp_name'] , $file);
        $stmt= $pdo->prepare("INSERT INTO products (name,description,category_id,price,quantity,image) VALUES (:name,:description,:category,:price,:quantity,:image)");
        $result=$stmt->execute(
          array(':name'=>$name,
                ':description'=>$desc,
                ':category'=>$category,
                ':price'=>$price,
                ':quantity'=>$qty,
                ':image'=>$image
                )
          );
          if($result){
            echo "<script>alert('New Products added.'); window.location.href='index.php';</script>";
          }
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
                <h3 class="card-title" ><b style="color= blue;">ADD YOUR NEW PRODUCTS</b></h3>
              </div>
              <div class="card-body">
                <form class="" action="product_add.php" method="POST" enctype="multipart/form-data">
                  <input name="_token" type="hidden" value="<?php echo $_SESSION['_token']; ?>">

                  <div class="form-group">
                    <label for="">Name</label><p style="color:red"><?php echo empty($nameError) ? '' : '*'.$nameError; ?></p>
                    <input type="text" class="form-control" name="name" value="">
                  </div>

                  <div class="form-group">
                    <label for="">Description</label><p style="color:red"><?php echo empty($descError) ? '' : '*'.$descError; ?></p>
                    <textarea class="form-control" name="description" rows="8" cols="80"></textarea>
                  </div>

                  <div class="form-group">
                    <?php
                    $catStmt= $pdo->prepare("SELECT * FROM categories");
                    $catStmt->execute();
                    $catResult= $catStmt->fetchAll();
                    ?>

                    <label for="">Category</label><p style="color:red"><?php echo empty($catError) ? '' : '*'.$catError; ?></p>
                    <select class="form-control" name="category">
                      <option value=" ">SELECT YOUR CATEGORY</option>
                    <?php
                    foreach ($catResult as $value) { ?>
                      <option value="<?php echo $value['id'];?>"><?php echo $value['name'];?></option>
                  <?php  }?>

                    </select>
                  </div>

                  <div class="form-group">
                    <label for="">Quantity</label><p style="color:red"><?php echo empty($qtyError) ? '' : '*'.$qtyError; ?></p>
                    <input type="number" class="form-control" name="quantity" value="">
                  </div>

                  <div class="form-group">
                    <label for="">Price</label><p style="color:red"><?php echo empty($priceError) ? '' : '*'.$priceError; ?></p>
                    <input type="number" class="form-control" name="price" value="">
                  </div>
                  <div class="form-group">
                    <label for="image">Image</label><p style="color:red"><?php echo empty($imgError)? '' : '*'.$imgError; ?></p>
                    <input type="file" name="image" value="">
                  </div>

                  <div class="form-group">
                    <input type="submit" class="btn btn-success" name="" value="SUBMIT">
                    <a href="index.php" class="btn btn-warning">Back</a>
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
