<?php
require_once 'includes/config.php';

$page_title = 'About Us';
include 'includes/header.php';
?>

<div class="card mb-4 shadow-sm">
    <div class="card-body">
        <h1 class="card-title">About Us</h1>
       

<style>
  .my-image {
    width: 50%;
    max-width: 300px;
    height: auto;
  }
</style>


        <div class="row">
            <div class="col-md-6">
            Vestibulum finibus malesuada ullamcorper. Nam imperdiet dolor lacinia lectus fringilla, quis volutpat risus efficitur. Integer nec nunc non ex tincidunt dapibus. Praesent imperdiet lacus vel lacinia tincidunt. Vestibulum nibh sapien, accumsan at fringilla vitae, pellentesque a velit. Quisque viverra... 

          </div>
            <div class="col-md-6">
                <p class="lead">
                    Welcome to <?php echo SITE_TITLE; ?>, your number one source for all things about web development and design.</p>
                <p>We're dedicated to providing you the very best of tutorials, with an emphasis on PHP, MySQL, JavaScript, and modern web technologies.</p>
                <p>Founded in 2023 by John Doe, <?php echo SITE_TITLE; ?> has come a long way from its beginnings. When John first started out, his passion for web development drove him to start his own blog.</p>
                <p>We hope you enjoy our content as much as we enjoy offering it to you. If you have any questions or comments, please don't hesitate to contact us.</p>
                <p>Sincerely,<br>The <?php echo SITE_TITLE; ?> Team</p>
            </div>
        </div>
    </div>
</div>

<div class="container mt-4">
  <!-- <h1>More text here or logo</h1> -->
    <div class="row">
        <div class="col-md-12">
       <!--      <p>more text here....</p>
-->
        </div>
    </div>
    
</div>

<?php 
include 'includes/footer.php';
?>
