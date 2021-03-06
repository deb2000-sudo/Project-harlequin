<?php require_once("Includes/DB.php"); ?>
<?php require_once("Includes/Functions.php"); ?>
<?php require_once("Includes/Sessions.php"); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <script src="https://kit.fontawesome.com/7f6ee3d237.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-giJF6kkoqNQ00vy+HMDP7azOuL0xtbfIcaT9wjKHr8RbDVddVHyTfAAsrekwKmP1" crossorigin="anonymous">
    <link rel="stylesheet" href="css/main.css">
    <link rel="stylesheet" href="css/card.css">
    <link rel="stylesheet" href="css/card-2.css">
    <title>Mind Saga</title>
    <style>
        .heading{
            font-family: Bitter,Georgia,"Times New Roman",Times,serif;
            font-weight: bold;
            color: blue;
        }
    </style>
</head>
<body>
<!--NAVIGATION BAR STARTS-->
<div class="navbar navbar-expand-lg navbar-dark bg-custom">
    <div class="container-fluid">
        <a href="#" class="navbar-brand " style= "color:aliceblue; font-family: mindsagacustom;">MindSaga</a>
        <button class="navbar-toggler custom-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#NavbarContent" aria-controls="NavbarContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
<!--        CONTENT WHEN NAVBAR IS COLLAPSE-->
        <div class="collapse navbar-collapse" id="NavbarContent">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item">
                    <a href="index.php" class="nav-link active" style= "color:white ; font-weight: bolder; ">Home</a>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link " style= "color:white ; font-weight: bolder; ">About Us</a>
                </li>
                <li class="nav-item">
                    <a href="index.php" class="nav-link " style= "color:white ; font-weight: bolder; ">Blog</a>
                </li>
                <li class="nav-item">
                    <a href="Login.php" class="nav-link " style= "color:white ; font-weight: bolder; " >Log In</a>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link" style= "color:white ; font-weight: bolder; ">Features</a>
                </li>
            </ul>
            <ul class="navbar-nav ml-auto">
                <form class="form-inline d-none d-sm-block" action="index.php">
                    <div class="form-group">
                        <input class="form-control mr-2" type="text" name="Search" placeholder="Search here" value="">
                        <button  class="btn btn-primary" name="SearchButton">Go</button>
                    </div>
                </form>
            </ul>
        </div>
    </div>
</div>
<!--NAVIGATION BAR ends-->

<!--HEADER STARTS-->
<div class="container-fluid">
    <div class="">
        <div class="headtitle">
            <h1>MindSaga</h1>
        </div>
            <!--main area starts---->

            <div class="float-container">
            <?php
            global $ConnectingDB;
             //sql query when search button is active
            if(isset($_GET["SearchButton"])){
                $Search = $_GET["Search"];
                $sql="SELECT * FROM posts 
                WHERE datetime LIKE :Search
                OR title LIKE :Search
                OR category LIKE :Search 
                OR post LIKE :Search";
                $stmt = $ConnectingDB->prepare($sql);
                $stmt->bindvalue(':Search','%'.$Search.'%');
                $stmt->execute();

            }//Query when pagination is active
            elseif (isset($_GET["page"])){
                $Page = $_GET["page"];
                if($Page==0||$Page<1){
                    $ShowPostFrom=0;
                }else {
                    $ShowPostFrom = ($Page * 10) - 10;
                }
                $sql = "SELECT * FROM posts ORDER BY id desc LIMIT $ShowPostFrom,10";
                $stmt = $ConnectingDB->query($sql);
            }
//            query when category is active in URL tab
            elseif(isset($_GET["category"])){
                $Category = $_GET["category"];
                $sql = "SELECT * FROM posts  WHERE category='$Category' ORDER BY id desc";
                $stmt = $ConnectingDB->query($sql);
            }
              //the default SQL query
            else{
                $sql = "SELECT * FROM posts ORDER BY id desc LIMIT 0,10";
                $stmt = $ConnectingDB->query($sql);
            }
            $sql = "SELECT * FROM posts ORDER BY id desc LIMIT 0,4";
            $stmt = $ConnectingDB->query($sql);

            while ($DataRows = $stmt->fetch()){
                $PostId = $DataRows["id"];
                $DateTime = $DataRows["datetime"];
                $PostTitle = $DataRows["title"];
                $Category  = $DataRows["category"];
                $Admin = $DataRows["author"];
                $Image = $DataRows["image"];
                $PostDescription = $DataRows["post"];

            ?>
                <div class="card-2 float-child shadow">
                        <div class="image-data">
                            <div class="background-image">
                                <img src="Uploads/<?php echo htmlentities($Image); ?>" alt="image" class="background-image">
                            </div>
                            <div class="publication-details">
                                <a href="Profile.php?username=<?php echo htmlentities($Admin); ?>" class="author"><i class="fa fa-user"></i><?php echo htmlentities($Admin); ?></a>
                                <span class="date"><i class="fa fa-calendar" aria-hidden="true"></i><?php echo htmlentities($DateTime); ?></span>
                            </div>
                        </div>
                        <div class="post-data">
                            <h1 class="title"><?php echo htmlentities($PostTitle)?></h1>
                            <h2 class="subtitle"><a href="index.php?category=<?php echo htmlentities($Category); ?>"> <?php echo htmlentities($Category); ?> </a></h2>
                            <p class="description">
                                <?php if (strlen($PostDescription)>150){$PostDescription = substr($PostDescription,0,40).'...';} echo htmlentities($PostDescription) ?>
                            </p>
                            <div class="cta">
                                <a href="FullPost.php?id=<?php echo $PostId; ?>"> Read More &rarr;</a>
                            </div>
                        </div>
                    </div>
              <?php } ?>
            </div>

                <div class="headtitle-2">
                    <h1>More posts from MindSaga</h1>
                </div>
                <div class="float-container-2">
                    <?php
                    global $ConnectingDB;
                    $sql= "SELECT * FROM posts ORDER BY id desc LIMIT 0,4";
                    $stmt= $ConnectingDB->query($sql);
                    while ($DataRows=$stmt->fetch()) {
                        $PostId = $DataRows["id"];
                        $DateTime = $DataRows["datetime"];
                        $PostTitle = $DataRows["title"];
                        $Category  = $DataRows["category"];
                        $Admin = $DataRows["author"];
                        $Image = $DataRows["image"];
                        $PostDescription = $DataRows["post"];
                        ?>
                    <div class="card-3 float-child-2 shadow-2">
                        <div class="image-data-2">
                            <div class="background-image-2">
                                <img src="Uploads/<?php echo htmlentities($Image); ?>" alt="image" class="background-image-2">
                            </div>
                            <div class="publication-details-2">
                                <a href="Profile.php?username=<?php echo htmlentities($Admin); ?>" class="author"><i class="fa fa-user"></i><?php echo htmlentities($Admin); ?></a>
                                <span class="date-2"><i class="fa fa-calendar" aria-hidden="true"></i><?php echo htmlentities($DateTime); ?></span>
                            </div>
                        </div>
                        <div class="post-data-2">
                            <h1 class="title-2"><?php echo htmlentities($PostTitle)?></h1>
                            <h2 class="subtitle-2"><a href="index.php?category=<?php echo htmlentities($Category); ?>"> <?php echo htmlentities($Category); ?> </a></h2>
                            <p class="description-2">
                                <?php if (strlen($PostDescription)>150){$PostDescription = substr($PostDescription,0,40).'...';} echo htmlentities($PostDescription) ?>
                            </p>
                            <div class="cta-2">
                                <a href="FullPost.php?id=<?php echo $PostId; ?>"> Read More &rarr;</a>
                            </div>
                        </div>
                    </div>
                    <?php } ?>
                </div>
        <div class="card">
            <div class="card-header text-white" style = "background-color:#280038">
                <h2 class="lead"> Recent Posts</h2>
            </div>
            <div class="card-body col-xs-6 col-sm-4 col-lg-12" >
                <?php
                global $ConnectingDB;
                $sql= "SELECT * FROM posts ORDER BY id desc LIMIT 0,6";
                $stmt= $ConnectingDB->query($sql);
                while ($DataRows=$stmt->fetch()) {
                    $Id     = $DataRows['id'];
                    $Title  = $DataRows['title'];
                    $DateTime = $DataRows['datetime'];
                    $Image = $DataRows['image'];
                    ?>
                    <div class="media" style="display: inline-flex; flex-direction: row; margin-left: 30px; float: left;">
                        <div class="" style="flex-basis: 40%;">
                            <img src="Uploads/<?php echo htmlentities($Image); ?>" class="d-block img-fluid align-self-start"  width="90" height="94" alt="">
                        </div>
                        <div class="media-body" style="flex-basis: 60%;" >
                            <a style="text-decoration:none;"href="FullPost.php?id=<?php echo htmlentities($Id) ; ?>" target="_blank">
                                <h6 class="lead heading"><?php echo htmlentities($Title); ?></h6>
                            </a>
                            <p class="small"><?php echo htmlentities($DateTime); ?></p>
                        </div>
                    </div>
                <?php } ?>
            </div>
        </div>

            <!--pagination-->
             <nav style="float: left;">
                 <ul class="pagination pagination-lg">
<!--                     backward button-->
                     <?php
                     if(isset($Page)){
                         if($Page>1){

                             ?>
                             <li class="page-item">
                                 <a href="index.php?page=<?php echo $Page-1; ?>" class="page-link">&laquo;</a>
                             </li>
                         <?php } }?>
                    <?php
                      global $ConnectingDB;
                      $sql = "SELECT COUNT(*) FROM posts";
                      $stmt = $ConnectingDB->query($sql);
                      $RowPagination=$stmt->fetch();
                      $TotalPosts=array_shift($RowPagination);
                               //echo $TotalPosts."<br>";
                      $PostPagination=$TotalPosts/10;
                      $PostPagination=ceil($PostPagination);
                              // echo $PostPagination;
                       for ($i=1; $i<=$PostPagination ; $i++){
                          if(isset($Page)){
                             if($i==$Page){
                    ?>
                                  <li class="page-item active">
                                       <a href="index.php?page=<?php echo $i; ?>" class="page-link"><?php echo $i; ?></a>
                                  </li>

                                  <?php
                                     }else{
                                  ?>

                                 <li class="page-item">
                                       <a href="index.php?page=<?php echo $i; ?>" class="page-link"><?php echo $i; ?></a>
                                 </li>
                                 <?php
                                      } } }
                                 ?>
<!--                       forward button-->
                     <?php
                     if(isset($Page)&&!empty($Page)){
                          if($Page+1<=$PostPagination){

                     ?>
                     <li class="page-item">
                         <a href="index.php?page=<?php echo $Page+1; ?>" class="page-link">&raquo;</a>
                     </li>
                     <?php } }?>
                </ul>
            </nav>
        </div>
<!--        side area and footer-->
        <?php require_once ("footer.php");?>

