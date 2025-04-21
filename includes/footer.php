</div> <!-- Close content column -->
            <?php include 'sidebar.php'; ?>
        </div> <!-- Close content row -->
    </div> <!-- Close container -->
    </main>

    <footer class="bg-dark text-white py-4 mt-auto">
        <div class="container">
            <div class="row">
                <div class="col-md-4 mb-3 mb-md-0">
                    <h5><?php echo SITE_TITLE; ?></h5>
                    <p>A modern PHP blog with Bootstrap and rich text editing.</p>
                </div>
                <div class="col-md-4 mb-3 mb-md-0">
                    <h5>Quick Links</h5>
                    <ul class="list-unstyled">
                        <li><a href="<?php echo SITE_URL; ?>" class="text-white">Home</a></li>
                        <li><a href="<?php echo SITE_URL; ?>/about.php" class="text-white">About</a></li>
                        <li><a href="<?php echo SITE_URL; ?>/contact.php" class="text-white">Contact</a></li>
                                           </ul>
                </div>
                <div class="col-md-4">
                    <h5>Connect</h5>
                    <ul class="list-unstyled">
                        <li><a href="#" class="text-white"><i class="bi bi-twitter"></i> Twitter</a></li>
                        <li><a href="#" class="text-white"><i class="bi bi-facebook"></i> Facebook</a></li>
                        <li><a href="#" class="text-white"><i class="bi bi-instagram"></i> Instagram</a></li>
                        <li><a href="#" class="text-white"><i class="bi bi-github"></i> GitHub</a></li>
                    </ul>
                </div>
            </div>
            <hr class="my-3">
            <div class="text-center">
                <p class="mb-0">&copy; <?php echo date('Y'); ?> <?php echo SITE_TITLE; ?>. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Custom JS -->
    <script src="<?php echo SITE_URL; ?>/assets/js/main.js"></script>
</body>
</html>