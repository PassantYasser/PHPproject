<?php
$conn= mysqli_connect("localhost" , 'root','','shops');
$message=null;
$count=1;

//-----create method-----//
if(isset($_POST['send'])){
    $name=$_POST['productName'];
    $category=$_POST['productCategory'];
    $Price=$_POST['productPrice'];
    $insert = "INSERT INTO `products` VALUES(NULL , ' $name','$category', $Price ,default)"; //default de bta3tt el notsold 3lashan fe sql asmha kada
    $i= mysqli_query($conn , $insert);
if( $i){
    $message="insert successfully";
}
}

//----read method----/
$select="SELECT * FROM `products`";
$allData= mysqli_query($conn , $select);


//----delete method----/

if(isset($_GET['delete'])){
    $id=$_GET['delete'];
    $delete ="DELETE FROM `products` WHERE Id=$id";
    $deleteData= mysqli_query($conn , $delete);

    header("location: index.php"); //بعد ما بعمل مسح يعمل تجديد لي الصفحة منغير ما اعمل ريفريش
}


//----update method----/


$name = "";
$price= "";
$category = "";
$update=false;
if (isset($_GET['edit'])) {
    $id = $_GET['edit'];
    $selectOneRow = "SELECT * FROM `products` WHERE Id = $id";
    $oneRow = mysqli_query($conn, $selectOneRow);
    $rowData = mysqli_fetch_assoc($oneRow);  //fetch data ely 3mltlha read

    $name = $rowData['name'];
    $category = $rowData['category'];
    $price = $rowData['price'];
    $update=true;

    if(isset($_POST['update'])){
        $name=$_POST['productName'];
        $category=$_POST['productCategory'];
        $Price=$_POST['productPrice'];
        $update = "UPDATE `products` SET name='$name', category='$category', price=$Price WHERE id = $id";
        mysqli_query($conn ,$update );
        header("location: index.php");
    }
}


//----status----//
if (isset($_GET['status'])) {
    $id = $_GET['status'];
    $update = "UPDATE products SET status = 'soldOut' where id =$id ";
    mysqli_query($conn, $update);
    header("location: index.php ");
}

//-----dark Mode----//



?>

<!---------------------------------------------------------------------------------------------------->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.xyz/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">
    <link rel="stylesheet" href="./css/all.min.css">
    <link rel="stylesheet" href="./css/main.css">

</head>
<body>


<!---------------------------------------------------------------------------------------------------->
<?php if($update): ?>
    <h1 class="text-center mt-5 text-danger"> update product <?=$name?> </h1>
<?php else: ?>
    <h1 class="text-center mt-5  text-success "> Create new  </h1>
<?php endif ?>
                    <!----------------------->

<div class="container col-md-6">
    <div class="card">
        <div class="card-body">
            <form action="" method="post">
                            <!------------------------>
                <div class="form-group">
                    <label for="">Name</label>
                    <input type="text" class="form-control" name="productName" value="<?= $name ?>">
                </div>
                            <!------------------------>

                <div class="form-group">
                    <label for="">Category</label>
                    <select class="form-control" id="exampleFormControlSelect1" name="productCategory">
                    <?php if($update): ?>
                        <option  value="<?= $category?>"><?= $category?></option>
                    <?php endif ?>
                        <option value="male">male</option>
                        <option value="female">female</option>
                        <option value="kids">kids</option>
                    </select>                
                </div>
                            <!------------------------>

                <div class="form-group">
                    <label for=""> Price </label>
                    <input type="number" class="form-control" name="productPrice"  value="<?= $price ?>" >
                </div>
                            <!------------------------>


            
        <div class="d-grid">
            <?php if($update): ?> 
                <button class="btn1 btn-danger" name="update">Update </button>
            <?php else: ?>
                <button class="btn1 btn-success btn1" name="send">Create New</button>
            <?php endif;?>
        </div>



<!-- message of save data in database-->
        <?php if ($message != null) : ?>
        <div class="alert alert-success">
            <h6>  <?= $message ?> </h6>
        </div>
        <?php endif ?>
<!-- ----------------------------------- -->
        </form>            
    </div>
</div>
</div>

<!---------------------------------------------------------------------------------------------------------->
<?php if(!$update): ?>
<h1 class="text-center"> List All Product </h1>
<div class="container col-md-6">
    <div class="card">
        <div class="card-body">
            <table class=" layo table table-dark">
                <tr>
                    <th>NO.</th>
                    <th>Id</th>
                    <th>Name</th>
                    <th>Category</th>
                    <th>Price</th>
                    <th>status</th>
                    <th colspan="2">Action</th>
                </tr>
                                <!------------------------>

                <?php foreach($allData as $items):?>
                    <tr>
                        <td><?= $count ++ ?></td>
                        
                        <td><?=$items['id']?></td>

                        <td><?=$items['name']?></td>

                        <td>
                            <?php if ($items['category'] =='male') { ?>
                                <i class="text-info fa-solid fa-mars-stroke"></i>
                            <?php } else if ($items['category'] == 'kids') { ?>
                                <i class="text-success fa-solid fa-child-reaching"></i>
                            <?php } else { ?>
                                <i class=" text-danger fa-solid fa-venus"></i> 
                            <?php } ?>
                        </td>

                        <td><?=$items['price']?></td>
                        <td>
                            <?php if ($items['status'] == 'notSold') : ?>
                                <a class="btn btn-danger" href="?status=<?= $items['id'] ?>">On Stock</a>
                            <?php else : ?>
                                <a class="btn btn-success" href="?status=<?= $items['id'] ?>">SoldOut</a>
                            <?php endif; ?>
                        </td>

                        


                        <td><a href="?edit=<?=$items['id']?>"> <i title="edit" class="text-info fa-solid fa-pen-to-square"></i></a></td>

                        <td><a onclick="return confirm('Are you sure ')" href="?delete=<?=$items['id']?>"> <i title="remove" class="text-primary fa-solid fa-trash"></i></a></td>
                    </tr>
                <?php endforeach ?>

            </table>
        </div>
    </div>
</div> 
    <?php endif; ?>                            

<!----------------------------------------------------------------------------------------------->





























</div>
<script src="./js/bootstrap.min.js"></script>   
</body>
</html>

