<?php

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (isset($_SESSION['parent_logged_email'])) {
   echo "<script>window.location.href='dashboard/parent'</script>";
}

$current_page = basename($_SERVER['PHP_SELF'], ".php"); 
?>

<nav id="main-nav" class="navbar-expand-xl fixed-top">
    <div class="row">
        <div class="navbar container-fluid">
            <div class="container">
                <a class="nav-brand" href="index">
                    <img src="img/horizontallogo.png" alt="" class="img-fluid" style="width:150px;height:100px;">
                </a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggle-icon">
                        <i class="fas fa-bars"></i>
                    </span>
                </button>
                <div class="collapse navbar-collapse" id="navbarResponsive">
                    <ul class="navbar-nav ml-auto">
                        <li class="nav-item <?= $current_page == 'index' || $current_page == '' ? 'active' : '' ?>">
                            <a class="nav-link" href="index">Home</a>
                        </li>
                        <li class="nav-item <?= $current_page == 'aboutus' ? 'active' : '' ?>">
                            <a href="aboutus" class="nav-link">About Us</a>
                        </li>
                        <li class="nav-item <?= $current_page == 'services' ? 'active' : '' ?>">
                            <a href="services" class="nav-link">Services</a>
                        </li>
                        <!-- <li class="nav-item <?= $current_page == 'gallery' ? 'active' : '' ?>">
                            <a href="gallery" class="nav-link">Gallery</a>
                        </li> -->
                        <!-- <li class="nav-item <?= $current_page == 'testimonials' ? 'active' : '' ?>">
                            <a href="testimonials" class="nav-link">Testimonials</a>
                        </li> -->
                        <li class="nav-item <?= $current_page == 'contact' ? 'active' : '' ?>">
                            <a href="contact" class="nav-link">Contact</a>
                        </li>
                        <li class="nav-item">
                            <a href="login" class="nav-link">Login</a>
                        </li>
                        <?php if ($current_page !== 'enrollment'): ?>
                        <li class="nav-item" style="pointer-events: none;">
                            <a href="" class="nav-link"></a>
                        </li>
                        <li class="nav-item" style="display: grid; place-items: center; cursor: default;" 
                            onmouseover="this.style.background='none'; this.style.color='inherit';" 
                            onmouseout="this.style.background=''; this.style.color='';">
                            <a href="enrollment" class="btn btn-secondary" style="margin: auto; padding: 5px 20px;">
                                Enroll Now
                            </a>
                        </li>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</nav>
