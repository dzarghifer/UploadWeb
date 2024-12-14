<?php  
// Aktifkan pelaporan error untuk debugging  
error_reporting(E_ALL);  
ini_set('display_errors', 1);  

// Sertakan koneksi database  
include 'includes/db.php';  

// Fungsi untuk membersihkan input  
function sanitizeInput($input) {  
    return htmlspecialchars(strip_tags(trim($input)));  
}  

try {  
    // Validasi ID  
    if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {  
        throw new Exception("ID tidak valid");  
    }  

    $id = intval($_GET['id']);  

    // Ambil data makalah berdasarkan ID  
    $stmt = $pdo->prepare("SELECT * FROM makalah WHERE id = ?");  
    $stmt->execute([$id]);  
    $makalah = $stmt->fetch(PDO::FETCH_ASSOC);  

    // Periksa apakah makalah ditemukan  
    if (!$makalah) {  
        throw new Exception("Makalah tidak ditemukan");  
    }  

    // Dapatkan path file PDF  
    $pdfPath = $makalah['pdf'];  

    // Validasi file  
    if (!file_exists($pdfPath)) {  
        throw new Exception("File PDF tidak ditemukan");  
    }  

    // Validasi tipe file  
    $allowedExtensions = ['pdf'];  
    $fileExtension = strtolower(pathinfo($pdfPath, PATHINFO_EXTENSION));  

    if (!in_array($fileExtension, $allowedExtensions)) {  
        throw new Exception("Hanya file PDF yang diizinkan");  
    }  

    // Validasi ukuran file (maks 50MB)  
    $maxFileSize = 50 * 1024 * 1024; // 50MB  
    $fileSize = filesize($pdfPath);  

    if ($fileSize > $maxFileSize) {  
        throw new Exception("Ukuran file terlalu besar");  
    }  

    // Validasi file dapat dibaca  
    if (!is_readable($pdfPath)) {  
        throw new Exception("File tidak dapat dibaca");  
    }  

    // Dapatkan informasi file  
    $fileName = basename($pdfPath);  

    // Set header HTTP untuk PDF  
    // Mencegah caching  
    header('Cache-Control: no-cache, no-store, must-revalidate');  
    header('Pragma: no-cache');  
    header('Expires: 0');  

    // Header untuk konten PDF  
    header('Content-Type: application/pdf');  
    
    // Tampilkan di browser (inline)  
    header('Content-Disposition: inline; filename="' . $fileName . '"');  
    
    // Header tambahan untuk keamanan dan kompatibilitas  
    header('Content-Transfer-Encoding: binary');  
    header('Accept-Ranges: bytes');  
    header('X-Content-Type-Options: nosniff');  
    header('X-Frame-Options: SAMEORIGIN');  

    // Set header panjang konten  
    header('Content-Length: ' . $fileSize);  

    // Baca dan keluarkan file  
    $file = @fopen($pdfPath, 'rb');  
    
    if ($file) {  
        // Keluarkan isi file secara aman  
        fpassthru($file);  
        fclose($file);  
        exit;  
    } else {  
        throw new Exception("Gagal membuka file PDF");  
    }  

} catch (Exception $e) {  
    // Tangani error dengan halaman error yang informatif  
    header('HTTP/1.1 500 Internal Server Error');  
    
    // Tampilkan halaman error dengan Bootstrap  
    ?>  
    <!DOCTYPE html>  
    <html lang="en">  
    <head>  
        <meta charset="UTF-8">  
        <meta name="viewport" content="width=device-width, initial-scale=1.0">  
        <title>Error Membuka PDF</title>  
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">  
    </head>  
    <body>  
        <div class="container mt-5">  
            <div class="row justify-content-center">  
                <div class="col-md-6">  
                    <div class="card border-danger">  
                        <div class="card-header bg-danger text-white">  
                            <h4 class="mb-0">Kesalahan Membuka PDF</h4>  
                        </div>  
                        <div class="card-body">  
                            <div class="alert alert-danger">  
                                <strong>Error:</strong> <?php echo htmlspecialchars($e->getMessage()); ?>  
                            </div>  
                            <div class="text-center">  
                                <a href="index.php" class="btn btn-primary">  
                                    Kembali ke Halaman Utama  
                                </a>  
                            </div>  
                        </div>  
                    </div>  
                </div>  
            </div>  
        </div>  
    </body>  
    </html>  
    <?php  
    exit;  
}  
?>