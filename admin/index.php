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


?>


<?php include('header.php'); ?>
    <!-- Main content -->
    <div class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-md-12">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">Products Listings</h3>
              </div>
              <?php
      				if (!empty($_GET['pageno'])){
                $pageno= $_GET['pageno'];
              }else{
      			$pageno=1;
      		}
      				$numOfrecs=5;
      				$offset= ($pageno-1)* $numOfrecs;
              if(empty($_POST['search'])){

      				$stmt= $pdo->prepare("SELECT * FROM products ORDER BY id DESC");
      				$stmt->execute();
      				$rawResult= $stmt->fetchAll();
      				$total_pages= ceil(count($rawResult)/ $numOfrecs);

      				$stmt= $pdo->prepare("SELECT * FROM products ORDER BY id DESC LIMIT $offset,$numOfrecs");
      				$stmt->execute();
      				$result= $stmt->fetchAll();

      		}else
      		{
      				$searchKey=$_POST['search'];
      				$stmt= $pdo->prepare("SELECT * FROM products WHERE name LIKE '%$searchKey%' ORDER BY id DESC");
      				$stmt->execute();
      				$rawResult= $stmt->fetchAll();
      				$total_pages= ceil(count($rawResult)/ $numOfrecs);

      				$stmt= $pdo->prepare("SELECT * FROM products WHERE name LIKE '%$searchKey%' ORDER BY id DESC LIMIT $offset,$numOfrecs");
      				$stmt->execute();
      				$result= $stmt->fetchAll();
      		}
      				?>
              <!-- /.card-header -->
              <div class="card-body">
                <div>
                  <a href="product_add.php" type="button" class="btn btn-success">New Products</a>
                </div>
                <br>
                <table class="table table-bordered">
                  <thead>
                    <tr>
                      <th style="width: 10px">#</th>
                      <th style="width: 200px">Name</th>
                      <th style="width: 300px">Description</th>
                      <th style="width: 100px">Category</th>
                      <th style="width: 100px">Instock</th>
                      <th style="width: 100px">Price</th>
                      <th style="width: 60px">Actions</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    if ($result){
                      $i=1;
                      foreach ($result as $value) {?>
                        <?php
                        $catStmt= $pdo->prepare("SELECT * FROM categories WHERE id=".$value['category_id']);
                        $catStmt->execute();
                        $catResult=$catStmt->fetchAll();


                         ?>
                      <tr>
                        <td><?php echo $i;?></td>
                        <td><?php echo escape($value['name']); ?></td>
                        <td><?php echo escape($value['description']); ?></td>
                        <td><?php echo escape($catResult[0]['name']); ?></td>
                        <td><?php echo escape($value['quantity']); ?></td>
                        <td><?php echo escape($value['price']); ?></td>
                        <td>
                <div >
              <a href="product_edit.php?id=<?php echo $value['id']; ?>" type="button" class="btn btn-warning">
              <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16">
                <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456l-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"/>
                <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5v11z"/>
              </svg>

                </a>
                  <a href="product_delete.php?id=<?php echo $value['id']; ?>" onClick="return confirm('Are you sure you want to delete?')" type="button" class="btn btn-danger">
                  <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash" viewBox="0 0 16 16">
                    <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6z"/>
                    <path fill-rule="evenodd" d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1zM4.118 4L4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118zM2.5 3V2h11v1h-11z"/>
                  </svg>

                  </a>
                  </div>
              </td>
                      </tr>
                      <?php
      							  $i++;
      						  }
      					  }
      					  ?>
                  </tbody>
                </table><br>

              </div>
              <!-- /.card-body -->
              <div class="card-footer clearfix">
                <ul class="pagination pagination-sm m-0 float-right">
                  <li class="page-item">
                    <a class="page-link" href="?pageno=1">First</a>
                  </li>
                  <li class="page-item"<?php if($pageno <=1){echo 'disabled';} ?>>
               <a class="page-link" href="<?php if($pageno<=1){ echo '#';} else {echo "?pageno=".($pageno-1);}?>">Previous</a>
                  </li>
                  <li class="page-item">
                    <a class="page-link" href="#"><?php echo $pageno; ?></a>
                  </li>
                  <li class="page-item"<?php if($pageno >= $total_pages){echo 'disabled';} ?>>
                    <a class="page-link" href="<?php if ($pageno>= $total_pages){echo '#';}else {echo "?pageno=".($pageno+1);} ?>">Next</a>
                  </li>
                  <li class="page-item">
                    <a class="page-link" href="?pageno=<?php echo $total_pages;?>">Last</a>
                  </li>
                </ul>
              </div>
            <!-- /.card -->
          </div>
        </div>
        <!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content -->
    </div>
<!-- /.content-wrapper -->

<!-- Control Sidebar -->

<!-- /.control-sidebar -->

<!-- Main Footer -->
<?php include('footer.html'); ?>
