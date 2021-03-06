<?php session_start(); ?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>NEWS FORUM</title>
    <!-- Bootstrap core CSS -->
   <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <!-- Custom fonts for this template -->
    <link href="https://fonts.googleapis.com/css?family=Catamaran:100,200,300,400,500,600,700,800,900" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Lato:100,100i,300,300i,400,400i,700,700i,900,900i" rel="stylesheet">
    <!-- Custom styles for this template -->
    <link href="css/one-page-wonder.min.css" rel="stylesheet">
  <style>
    a:link {color:#ff0000;}
    a:hover {color:#ffcc00;}
    body {text-align: justify;}
  </style>
  </head>
  <body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark navbar-custom fixed-top">
      <div class="container">
          <?php if(isset($_SESSION['id'])){
            ?>
            <a class="navbar-brand" href="../login/logout_script.php"> <?php echo $_SESSION['username']; echo " | LOGOUT";  ?></a>
          <?php
            } else{ ?>
        <a class="navbar-brand" href="login/login.php"> LOGIN | SIGN UP</a>
          <?php } ?>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarResponsive">
          <ul class="navbar-nav ml-auto">
            <li class="nav-item">
              <a class="nav-link" href="news.php">News Feed</a>
            </li>
          </ul>
        </div>
      </div>
    </nav>
    <br>
    <br>
    <br>
    <section>
      <div class="container">
        <div class="row align-items-center">
        <?php
include "../includes/common.php";
?>
<html>
    <head>
        <title>Like Unlike</title>
        <link href="style.css" type="text/css" rel="stylesheet" />
        <script src="jquery-3.3.1.js" type="text/javascript"></script>

        <script src="script.js" type="text/javascript"></script>

    </head>
    <body>
        <div class="content">
        <center> <h1> NewsFeed </h1> </center>
        <hr>
       
            <?php
                $userid = $_SESSION['id'];
                $rec_limit = 10;
                $query = "SELECT count(id) as rec_count FROM articles";
                $result = mysqli_query($con,$query);
                $row = mysqli_fetch_array($result);
                $rec_count = $row[rec_count];
                if(isset($_GET{'page'})){
                    $page = $_GET{'page'} + 1;
                    $offset = $rec_limit * $page ;
                }else {
                    $page = 0;
                    $offset = 0;
                 }
                 $left_rec = $rec_count - ($page * $rec_limit);
                 $orderby=0;
                 if(isset($_GET["orderby"])){
                    $orderby=$_GET["orderby"];
                 }else {
                    $orderby=0;
                 }
                 
                if($orderby==0) $query = "SELECT * FROM articles ORDER BY time_stamp DESC LIMIT ".$offset.", ".$rec_limit;
                elseif ($orderby==1) {
                    $query = "SELECT * FROM articles LEFT JOIN (SELECT article_id ,SUM(like_type) as s from user_likes group by article_id) as t ON articles.id = t.article_id ORDER BY s DESC LIMIT ".$offset.", ".$rec_limit;
                } ?>
            
             <center>
        <form action="#" method="GET">
            <label class="radio-inline"><input type="radio" name="orderby" value="0" <?php if($orderby==0){echo "checked";} ?> >By Date</label>
<label class="radio-inline"><input type="radio" name="orderby" value="1" <?php if($orderby==1){echo "checked";} ?> >By likes</label>
  <input type="submit" value="Go">
</form></center>
            <?php
                $result = mysqli_query($con,$query);
                while($row = mysqli_fetch_array($result)){
                    $postid = $row['id'];
                    $title = $row['Title'];
                    $content = $row['description'];
                    $type = -1;
                    // Checking user status
                    $status_query = "SELECT id ,like_type,COUNT(*) as cntStatus FROM user_likes WHERE user_id=".$userid." and article_id=".$postid." GROUP BY id";
                    $status_result = mysqli_query($con,$status_query);
                    $status_row = mysqli_fetch_array($status_result);
                    $count_status = $status_row['cntStatus'];
                    if($count_status > 0){
                        $type = $status_row['like_type'];
                    }
                    
                    // Count post total likes and unlikes
                    $like_query = "SELECT COUNT(*) AS cntLikes FROM user_likes WHERE like_type=1 and article_id=".$postid;
                    $like_result = mysqli_query($con,$like_query);
                    $like_row = mysqli_fetch_array($like_result);
                    $total_likes = $like_row['cntLikes'];
                    $unlike_query = "SELECT COUNT(*) AS cntUnlikes FROM user_likes WHERE like_type=0 and article_id=".$postid;
                    $unlike_result = mysqli_query($con,$unlike_query);
                    $unlike_row = mysqli_fetch_array($unlike_result);
                    $total_unlikes = $unlike_row['cntUnlikes'];
            ?>   <div class="post">
                        <h1><?php echo $title; ?></h1>
                        <div class="post-text">
                            <?php echo substr($content, 0, 240); ?> <a href="forum.php?postid=<?php echo $postid; ?>"> .. Read More</a>
                        </div>
                        <div class="post-action">

                            <?php if(!(isset($_SESSION['id']))) echo "<a href='login/login.php'>"; ?>
                            <input type="button" value="Like" id="like_<?php echo $postid; ?>" class="like" style="<?php if($type == 1){ echo "color: #ffa449;"; } ?>" />&nbsp;(<span id="likes_<?php echo $postid; ?>"><?php echo $total_likes; ?></span>)&nbsp;

                            <input type="button" value="Unlike" id="unlike_<?php echo $postid; ?>" class="unlike" style="<?php if($type == 0){ echo "color: #ffa449;"; } ?>" />&nbsp;(<span id="unlikes_<?php echo $postid; ?>"><?php echo $total_unlikes; ?></span>);
                            
                            <?php if(!(isset($_SESSION['id']))) echo "</a>"; ?>
                            

                        </div>
                    </div>
            <?php
                }
            ?>
            <?php
                if( $page > 0 ) {
            $last = $page - 2;
            echo "<a href = \"$_PHP_SELF?page=$last&&orderby=$orderby\">Last Posts</a> |";
            if ($left_rec > $rec_limit) echo "<a href = \"$_PHP_SELF?page=$page&&orderby=$orderby\">Next Posts</a>";
         }else if( $page == 0 ) {
            echo "<a href = \"$_PHP_SELF?page=$page&&orderby=$orderby\">Next Posts</a>";
         }else if( $left_rec < $rec_limit ) {
            $last = $page - 2;
            echo "<a href = \"$_PHP_SELF?page=$last&&orderby=$orderby \">Last Posts</a>";
         }
            ?>

        </div>
    </body>
        </div>

      </div>
    </section>

    <!-- Footer -->
    <footer class="py-5 bg-black">
      <div class="container">
        <p class="m-0 text-center text-white small">Contibutors:<br>Girish Kumar, Chirag Khurana, Karan Seghal ,Arunaksha Tlukadar</p>
      </div>
      <!-- /.container -->
    </footer>

     <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>

        <script src="jquery-3.3.1.js" type="text/javascript"></script>


  </body>

</html>
