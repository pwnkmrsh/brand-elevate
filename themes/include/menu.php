  <header id="header" class="header d-flex align-items-center fixed-top">
    <div class="container-fluid container-xl position-relative d-flex align-items-center">

      <a href="index.php" class="logo d-flex align-items-center me-auto">
         <img src="../assets/img/logo.png" alt="Brand Elevate" style="height:200px;">  
        <!-- Uncomment the line below if you also wish to use an text logo
        <h1 class="sitename"><?php //echo SITE_NAME; ?></h1>  -->
      </a>

      <nav id="navmenu" class="navmenu">
        <ul>
          <li><a href="#hero" class="active">Home<br></a></li>
          <li><a href="#about">About</a></li>
           <li><a href="#features">Features</a></li>
          <li><a href="#benefits">Who Benefits Most?</a></li>
          <li><a href="#gallery">Success Stories</a></li>
          <li><a href="#pricing">Pricing</a></li>
          <li><a href="#faq">FAQs</a></li>
          <?php
          echo '<li><a href="dashboard.php">Tools</a></li>';
          echo ' <li><a href="posts">History</a></li> ';
            if (isset($_SESSION['image']) && !empty($_SESSION['image'])) {
            echo "<img  class='rounded-circle img-thumbnail' src='" . htmlspecialchars($_SESSION['image']) . "' width='40' />";
          }
          if (isset($_SESSION['name']) && !empty($_SESSION['name'])) {
                echo  '<li><a href="#faq">' . htmlspecialchars($_SESSION['name']) . '</a></li>';
            echo "<li><a href='logout.php'>Logout</a></li>";
          }
          
          ?>
             
        </ul>
        <i class="mobile-nav-toggle d-xl-none bi bi-list"></i>
      </nav> 
    </div>
  </header>