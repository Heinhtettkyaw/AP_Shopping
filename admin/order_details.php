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

?>


<?php include('header.php'); ?>

<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h3 class="m-0 text-">Order Details
                </h3>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                     <li class="breadcrumb-item"><a href="order_list.php">Orders</a></li>
                     <li class="breadcrumb-item active">Order Details</li>

                </ol>
            </div> <!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.container-fluid -->
</div>
<!-- /.content-header -->

<!-- Main content -->
<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <?php
                        if (!empty($_GET['pageno'])){
                            $pageno= $_GET['pageno'];
                        }else{
                            $pageno=1;
                        }
                        $numOfrecs=5;
                        $offset= ($pageno-1)* $numOfrecs;

                        $stmt= $pdo->prepare("SELECT * FROM sale_order_detail WHERE sale_order_id=".$_GET['id']);
                        $stmt->execute();
                        $rawResult= $stmt->fetchAll();
                        $total_pages= ceil(count($rawResult)/ $numOfrecs);

                        $stmt= $pdo->prepare("SELECT * FROM sale_order_detail WHERE sale_order_id=".$_GET['id']. " LIMIT $offset,$numOfrecs");
                        $stmt->execute();
                        $result= $stmt->fetchAll();


                        $oStmt= $pdo->prepare("SELECT * FROM sale_orders  WHERE id=".$result[0]['sale_order_id']);
                        $oStmt->execute();
                        $oResult=$oStmt->fetchAll();


                        $userStmt= $pdo->prepare("SELECT * FROM users WHERE id=".$oResult[0]['user_id']);
                        $userStmt->execute();
                        $userResult=$userStmt->fetchAll();
                        ?>


                        <h3 class="card-title"><span style="color: #f0ad4e; font-size: 20px; font-style: italic;"><?php echo $userResult[0]['name']; ?></span> 's Order List Detail </h3>
                    </div>


                    <!-- /.card-header -->
                    <div class="card-body">
                        <table class="table table-bordered">
                            <thead>
                            <tr>
                                <th style="width: 10px">#</th>
                                <th>Product</th>
                                <th>Quantity</th>
                                <th>Order Date</th>

                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            if ($result){
                                $i=1;
                                foreach ($result as $value){?>
                                    <?php
                                    $pStmt= $pdo->prepare("SELECT * FROM products WHERE id=".$value['product_id']);
                                    $pStmt->execute();
                                    $pResult=$pStmt->fetchAll();
                                    ?>
                                    <tr>

                                        <td><?php echo $i; ?></td>
                                        <td><?php echo escape($pResult[0]['name']); ?></td>
                                        <td> <?php echo escape($value['quantity']); ?></td>
                                        <td><?php echo escape(date('Y-m-d',strtotime($value['order_date']))) ?></td>


                                    </tr>

                                    <?php
                                    $i++;
                                }
                            }
                            ?>

                            </tbody>
                        </table>
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
                </div>
                <!-- /.card -->


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
<?php include('footer.html'); ?>
