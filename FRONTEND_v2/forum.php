<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>NEWS SCRAPPER</title>
    <!-- Bootstrap core CSS -->
    <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom fonts for this template -->
    <link href="https://fonts.googleapis.com/css?family=Catamaran:100,200,300,400,500,600,700,800,900" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Lato:100,100i,300,300i,400,400i,700,700i,900,900i" rel="stylesheet">
    <!-- Custom styles for this template -->
    <link href="css/one-page-wonder.min.css" rel="stylesheet">
  <style>
	a:link {color:#ff0000;}
	a:visited {color:#0000ff;}
	a:hover {color:#ffcc00;}
	body {text-align: justify;}
  </style>
  </head>
  <body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark navbar-custom fixed-top">
      <div class="container">
        <a class="navbar-brand" href="login/login.php">LOGIN / SIGN UP</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarResponsive">
          <ul class="navbar-nav ml-auto">
            <li class="nav-item">
              <a class="nav-link" href="news.html">NEWS FEED</a>
            </li>
          </ul>
        </div>
      </div>
    </nav>
      
      <?php include("../includes/common.php");
      $article_id = $_GET['postid'];
      //echo $article_id;
      $query = "SELECT Title, description, image_url, time_stamp, source FROM articles WHERE id = '$article_id'";
      $result_article = $con->query($query);
      if($result_article && $result_article->num_rows > 0){
          $content = $result_article->fetch_assoc();
          $title = $content['Title'];
          $descrp = $content['description'];
          $source = $content['source'];
          $timestamp = $content['time_stamp'];
          $imageurl = $content['image_url'];
          ?>
      
      <br>
    <br>
    <br>
    <section>
      <div class="container">
        <div class="row align-items-center">
          <!--<div class="col-lg-6 order-lg-2">
            <div class="p-5">
              <img class="img-fluid rounded-circle" src="login/css/logo.png" alt="">
            </div>
          </div> -->
          <div class="col-lg-12 order-lg-1">
            <div class="p-5">
              <h2 class="display-4" align="center"><?php  echo $title; ?></h2>
             <?php if($imageurl != null) { ?>   <img src="$imageurl" class="img-responsive"> <?php } ?>
              <p align="center"> <?php echo $descrp; ?> </p>
                
            </div>
          </div>
        </div>
      </div>
    </section>
      
      <?php
          
      }
        
      ?>
      
      
      
  <!-- Footer -->
    <footer class="py-5 bg-black">
      <div class="container">
        <p class="m-0 text-center text-white small">Contibutors: Girish, Chirag, Karan, Arunaksha</p>
      </div>
      <!-- /.container -->
    </footer>

    <!-- Bootstrap core JavaScript -->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

  </body>

</html>
