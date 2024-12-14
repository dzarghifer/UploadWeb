<?php  
// Aktifkan pelaporan error  
error_reporting(E_ALL);  
ini_set('display_errors', 1);  

include 'includes/db.php';  

// Periksa apakah ID diberikan  
if (!isset($_GET['id'])) {  
    die("ID tidak valid");  
}  

$id = intval($_GET['id']);  

try {  
    // Ambil data makalah berdasarkan ID  
    $stmt = $pdo->prepare("SELECT * FROM makalah WHERE id = ?");  
    $stmt->execute([$id]);  
    $makalah = $stmt->fetch(PDO::FETCH_ASSOC);  

    if (!$makalah) {  
        die("Makalah tidak ditemukan");  
    }  
} catch (PDOException $e) {  
    die("Error: " . $e->getMessage());  
}  

// Mulai output buffering  
ob_start();  
?>  

<!DOCTYPE html>  
<html lang="en">  
<head>  
    <meta charset="UTF-8">  
    <meta name="viewport" content="width=device-width, initial-scale=1.0">  
    <title>Detail Makalah</title>  
    <!-- Bootstrap CSS -->  
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">  
    <!-- Bootstrap Icons -->  
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">  
</head>  
<body>  
    <div class="container mt-4">  
        <div class="row">  
            <div class="col-md-8 offset-md-2">  
                <div class="card shadow-lg">  
                    <div class="card-header bg-primary text-white">  
                        <h3 class="mb-0">Detail Makalah</h3>  
                    </div>  
                    <div class="card-body">  
                        <div class="row">  
                            <div class="col-md-8">  
                                <h4 class="card-title"><?php echo htmlspecialchars($makalah['judul']); ?></h4>  
                                <p class="card-text">  
                                    <strong>Penulis:</strong> <?php echo htmlspecialchars($makalah['penulis']); ?>  
                                </p>  
                            </div>  
                            <div class="col-md-4 text-end">  
                                <a href="pdf_viewer.php?id=<?php echo $makalah['id']; ?>"   
                                   target="_blank"   
                                   class="btn btn-success">  
                                    <i class="bi bi-file-pdf"></i> Buka PDF  
                                </a>  
                            </div>  
                        </div>  
                    
                        <hr>  
                    
                        <div class="pdf-preview">  
                            <iframe   
                                src="pdf_viewer.php?id=<?php echo $makalah['id']; ?>"   
                                width="100%"   
                                height="600px"   
                                style="border: none;">  
                                <p>Browser Anda tidak mendukung preview PDF.   
                                   <a href="pdf_viewer.php?id=<?php echo $makalah['id']; ?>" target="_blank">Klik di sini untuk membuka PDF</a>  
                                </p>  
                            </iframe>  
                        </div>  
                    </div>  
                    <div class="card-footer">  
                        <a href="index.php" class="btn btn-secondary">  
                            <i class="bi bi-arrow-left"></i> Kembali ke Daftar  
                        </a>  
                    </div>  
                </div>  
            </div>  
        </div>  
    </div>  

    <!-- Bootstrap JS (optional) -->  
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>  
</body>  
</html>  

<?php  
// Simpan output ke variabel  
$content = ob_get_clean();  

// Sertakan layout jika diperlukan  
// include 'layout.php';  
?>