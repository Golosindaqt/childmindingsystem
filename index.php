<!DOCTYPE html>
<html lang="en">
   <head>
      <?php include('./components/head.php'); ?>
   </head>
</html>
   <body id="top" >
      <div id="preloader">
         <div class="container h-100">
            <div class="row h-100 justify-content-center align-items-center">
               <div class="preloader-logo">
                  <div class="spinner">
                     <div class="dot1" style="background: #FFD626;"></div>
                     <div class="dot2" style="background: #52B0FF"></div>
                  </div>
               </div>
            </div>
         </div>
      </div>
      <?php include('./components/nav.php'); ?>
      <div id="page-wrapper">
         <div class="container-fluid p-0" id="fordesktop">
            <div id="slider" class="parallax-slider" style="width:1200px;margin:0 auto;margin-bottom: 0px;">
               <div class="ls-slide" data-ls="duration:4000; transition2d:7;">
                  <img src="img/slider/parallaxslider/slide1.jpg" class="ls-bg" alt="" />
                  <img  src="img/slider/parallaxslider/clouds.png" class="ls-l" alt="" style="top:56px;left:-100px; " data-ls="parallax:true; parallaxlevel:-5;">
                  <img  src="img/slider/parallaxslider/butterflies.png" class="ls-l" alt="" style="top:16px;left:0px;" data-ls=" parallax:true; parallaxlevel:4;">
                  <img  src="img/slider/parallaxslider/sun.png" class="ls-l" alt="" style="top:-190px;left:650px;" data-ls="parallax:true; parallaxlevel:-3;">
                  <img  src="img/slider/children.png" class="ls-l" alt="" style="top:166px;left:520px;" data-ls="offsetxin:10; offsetyin:120; durationin:1100; rotatein:5; transformoriginin:59.3% 80.3% 0; offsetxout:-80; durationout:400; parallax:true; parallaxlevel:10;">
                  <div class="ls-l header-wrapper" data-ls="offsetyin:150; durationin:700; delayin:200; easingin:easeOutQuint; rotatexin:20; scalexin:1.4; offsetyout:600; durationout:400;">
                     <div class="header-text" >
                        <span>Welcome to</span> 
                        <h1> Child Minding GAD Resource Center!</h1>
                        <div class="hidden-small">
                           <p class="header-p">Nurturing Minds, Growing Hearts</p>
                           <a class="btn btn-secondary" href="enrollment">Enroll Now</a>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
            <svg version="1.1" id="divider"  class="slider-divider" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
               viewBox="0 0 1440 126" preserveAspectRatio="none slice" xml:space="preserve">
               <path class="st0" d="M685.6,38.8C418.7-11.1,170.2,9.9,0,30v96h1440V30C1252.7,52.2,1010,99.4,685.6,38.8z"/>
            </svg>
         </div>
          <div id="formobile">
                     <div class="header-text2" >
                        <span>Welcome to</span> 
                        <h6> Child Minding GAD Resource Center!</h6>
                           <p class="header-p">Nurturing Minds, Growing Hearts</p>
                           <a class="btn btn-secondary" href="enrollment">Enroll Now</a>
                     </div>
          </div>
         <section id="about-home" class="container-fluid pb-0">
            <div class="container">
               <div class="section-heading text-center">
                     <div class="ornament-rainbow" data-aos="zoom-out"></div>
                  <h2>About Us</h2>
                  <p class="subtitle">Get to know us</p>
               </div>
               <div class="row">
                  <div class="col-lg-7 ">
                     <h3>Who we are</h3>
                     <p class="mt-4 res-margin">At the USTP Child Minding Center, our mission is to provide a safe, nurturing, and supportive environment for children while their parents focus on work or studies. We strive to offer high-quality care through structured activities and educational support, ensuring every child receives the attention and care they deserve.</p>
                     <p>The USTP Child Minding Center is part of the Gender and Development (GAD) Resource Center at the University of Science and Technology of Southern Philippines. Our center caters to children aged 3-8 years old, providing child-minding services for parents who are either employed or studying within the university.</p>
                     <p>We have a dedicated team of trained professionals committed to fostering a stimulating environment where children can grow and thrive. With a focus on safety, learning, and fun, our center offers peace of mind to parents, knowing that their children are in good hands</p>
                     <a href="aboutus" class="btn btn-secondary ">Read more</a>
                  </div>
                  <div class="col-lg-5 res-margin">
                     <img class="img-fluid rounded" src="img/verticallogo.png" alt="">
                  </div>
               </div>
            </div>
         </section>
         <?php include('./components/services.php'); ?>
        
        <?php include('./components/callout.php'); ?>
        <?php include('./components/contactus.php'); ?>
      </div>
      <svg version="1.1" id="footer-divider"  class="secondary" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
         viewBox="0 0 1440 126" xml:space="preserve" preserveAspectRatio="none slice">
         <path class="st0" d="M685.6,38.8C418.7-11.1,170.2,9.9,0,30v96h1440V30C1252.7,52.2,1010,99.4,685.6,38.8z"/>
      </svg>
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
</body>
</html>