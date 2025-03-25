   <!DOCTYPE html>
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
         <?php include('./components/testimonials.php'); ?>
         
          <div style="margin-top: 50px;">
         <?php include('./components/callout.php'); ?>
         </div>
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
      <script src="vendor/layerslider/js/layerslider.load.js"></script>