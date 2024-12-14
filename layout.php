<!DOCTYPE html>  
<html lang="en">  
<head>  
    <meta charset="UTF-8">  
    <meta name="viewport" content="width=device-width, initial-scale=1.0">  
    <meta http-equiv="X-UA-Compatible" content="ie=edge">  
    <!-- Bootstrap 5 CSS -->  
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">  
    <!-- Bootstrap Icons -->  
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">  
    <link rel="stylesheet" href="css/style.css">  
    <title>Web PDF</title>  
</head>  
<body>  
    <!-- Navbar with added shadow class -->  
    <nav class="navbar navbar-expand-lg navbar-light bg-light navbar-shadow" style="background-color: #ffffff">  
        <div class="container">  
            <!-- Site Name Only -->  
            <a class="navbar-brand" href="index.php"><h3>Web Upload</h3></a>  

            <!-- Navbar Toggle for Mobile -->  
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">  
                <span class="navbar-toggler-icon"></span>  
            </button>  

            <!-- Navigation Links -->  
            <div class="collapse navbar-collapse justify-content-end" id="navbarNav">  
                <ul class="navbar-nav">  
                    <li class="nav-item">  
                        <a class="nav-link" href="index.php"><i class="bi bi-house me-1 px-1"></i>Halaman Utama</a>  
                    </li>  
                    <li class="nav-item">  
                        <a class="nav-link" href="upload.php"><i class="bi bi-cloud-upload me-1 px-1"></i>Halaman Upload</a>  
                    </li>  
                </ul>  
            </div>  
        </div>  
    </nav>  

    <?php echo $content; ?>  

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>  
</body>  
</html>