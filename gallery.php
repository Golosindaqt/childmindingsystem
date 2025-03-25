<!-- <!DOCTYPE html>
<html lang="en">
   <head>
      <?php include('./components/head.php'); ?>

      <style type="text/css">

#hideinmobile {
  display: none; 
}

@media (min-width: 768px) { 
  #hideinmobile {
    display: block; 
  }
}

      </style>
   </head>
</html>
   <body id="top" >

      <?php include('./components/nav.php'); ?>
      <div id="page-wrapper">

         <div id="hideinmobile">
             <?php include('./components/counter.php'); ?>
            </div>
            <section id="gallery-home" class="container-fluid bg-tertiary no-bg-sm">
    <div class="container">
        <div class="section-heading text-center text-light">
            <h2>Gallery</h2>
            <p class="subtitle">Activities and learning.</p>
        </div>

        <div id="gallery-isotope" class="row mt-5 magnific-popup">
            <?php

            if (session_status() == PHP_SESSION_NONE) {
                session_start();
            }

            include 'db_conn.php';

            $sql = "SELECT * FROM activity_report WHERE visibility = 'public' OR visibility = 'both'"; 
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {

                while ($row = $result->fetch_assoc()) {

                    $image = $row['fileImgsrc'];  
                    $title = $row['title'];
                    $description = $row['description'];
                    $visibility = $row['visibility'];
                    $activityId = $row['activity_id']; 
            ?>
                    <div class="col-sm-6 col-md-6 col-lg-4 <?= strtolower($visibility) ?>">
    <div class="portfolio-item">
        <div class="gallery-thumb">
            <img class="img-fluid" src="dashboard/admin/gallery/<?= htmlspecialchars($image) ?>" alt="<?= htmlspecialchars($title) ?>" style="object-fit: cover; width: 100%; height: 300px;">
            <span class="overlay-mask"></span>
            <a href="dashboard/admin/gallery/<?= htmlspecialchars($image) ?>" class="link centered"  title="<?= htmlspecialchars($title) ?>">
                <i class="fa fa-expand"></i>
            </a>
        </div>
    </div>
</div>

            <?php
                }
            } else {
                echo "<p>No images found in the gallery.</p>";
            }

            $conn->close();
            ?>
        </div>
    </div>
</section>

         <?php include('./components/callout.php'); ?>

      </div>
      <?php include('./components/footer.php'); ?>
      <script src="vendor/jquery/jquery.min.js"></script>
      <script src="vendor/bootstrap/js/bootstrap.min.js"></script>
      <script src="js/custom.js"></script>
      <script src="js/plugins.js"></script>
      <script src="js/prefixfree.min.js"></script>
      <script src="js/counter.js"></script>
      <script src="vendor/layerslider/js/greensock.js"></script>
      <script src="vendor/layerslider/js/layerslider.transitions.js"></script>
      <script src="vendor/layerslider/js/layerslider.kreaturamedia.jquery.js"></script>
      <script src="vendor/layerslider/js/layerslider.load.js"></script> -->