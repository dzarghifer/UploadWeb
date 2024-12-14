<?php  
// Aktifkan pelaporan error  
error_reporting(E_ALL);  
ini_set('display_errors', 1);  

// Sertakan file koneksi database  
include 'includes/db.php';  

// Fungsi validasi dan pembersihan input  
function cleanInput($input) {  
    $input = trim($input);  
    $input = stripslashes($input);  
    $input = htmlspecialchars($input);  
    return $input;  
}  

// Proses upload jika ada form submit  
if ($_SERVER['REQUEST_METHOD'] == 'POST') {  
    // Validasi input  
    $judul = cleanInput($_POST['judul']);  
    $penulis = cleanInput($_POST['penulis']);  
    
    // Validasi input dasar  
    $errors = [];  
    
    if (empty($judul)) {  
        $errors[] = "Judul harus diisi";  
    }  
    
    if (empty($penulis)) {  
        $errors[] = "Nama penulis harus diisi";  
    }  
    
    // Validasi file  
    if (!isset($_FILES['pdf']) || $_FILES['pdf']['error'] != UPLOAD_ERR_OK) {  
        $errors[] = "File PDF harus diunggah";  
    }  

    // Jika tidak ada error, lanjutkan proses upload  
    if (empty($errors)) {  
        $pdf = $_FILES['pdf'];  

        // Validasi dan upload file  
        $targetDir = "uploads/";  

        // Buat folder jika belum ada  
        if (!file_exists($targetDir)) {  
            mkdir($targetDir, 0777, true);  
        }  

        // Generate nama file unik  
        $fileExt = strtolower(pathinfo($pdf["name"], PATHINFO_EXTENSION));  
        $fileName = time() . '_' . preg_replace('/[^a-zA-Z0-9_.-]/', '_', $pdf["name"]);  
        $targetFile = $targetDir . $fileName;  

        // Validasi tipe file  
        $allowedTypes = ['pdf'];  
        if (!in_array($fileExt, $allowedTypes)) {  
            $errors[] = "Hanya file PDF yang diperbolehkan";  
        }  

        // Validasi ukuran file (maks 10MB)  
        $maxFileSize = 10 * 1024 * 1024; // 10MB  
        if ($pdf['size'] > $maxFileSize) {  
            $errors[] = "Ukuran file maksimal 10MB";  
        }  

        // Jika masih tidak ada error, lakukan upload  
        if (empty($errors)) {  
            // Upload file  
            if (move_uploaded_file($pdf["tmp_name"], $targetFile)) {  
                try {  
                    // Simpan ke database  
                    $stmt = $pdo->prepare("INSERT INTO makalah (judul, penulis, pdf) VALUES (?, ?, ?)");  
                    $stmt->execute([$judul, $penulis, $targetFile]);  
                    
                    // Redirect ke halaman utama dengan pesan sukses  
                    header("Location: index.php?success=1");  
                    exit();  
                } catch (PDOException $e) {  
                    $errors[] = "Gagal menyimpan data: " . $e->getMessage();  
                }  
            } else {  
                $errors[] = "Gagal mengunggah file";  
            }  
        }  
    }  
}  

// Mulai output buffering  
ob_start();  
?>  

<div class="container mt-4">  
    <div class="row justify-content-center">  
        <div class="col-md-6">  
            <div class="card shadow-lg">  
                <div class="card-header bg-primary text-white text-center">  
                    <h3>Tambah Makalah Baru</h3>  
                </div>  
                <div class="card-body p-4">  
                    <?php   
                    // Tampilkan error jika ada  
                    if (!empty($errors)): ?>  
                        <div class="alert alert-danger">  
                            <ul>  
                                <?php foreach ($errors as $error): ?>  
                                    <li><?php echo $error; ?></li>  
                                <?php endforeach; ?>  
                            </ul>  
                        </div>  
                    <?php endif; ?>  

                    <form action="upload.php" method="POST" enctype="multipart/form-data">  
                        <div class="mb-3">  
                            <label class="form-label">Judul Makalah</label>  
                            <input type="text" name="judul" class="form-control"   
                                   value="<?php echo isset($judul) ? htmlspecialchars($judul) : ''; ?>"   
                                   required>  
                        </div>  
                        <div class="mb-3">  
                            <label class="form-label">Nama Penulis</label>  
                            <input type="text" name="penulis" class="form-control"   
                                   value="<?php echo isset($penulis) ? htmlspecialchars($penulis) : ''; ?>"   
                                   required>  
                        </div>  
                        <div class="mb-3">  
                            <label class="form-label">Upload PDF</label>  
                            <input type="file" name="pdf" class="form-control" accept=".pdf" required>  
                            <small class="text-muted">Maks. 10MB, hanya file PDF</small>  
                        </div>  
                        <button type="submit" class="btn btn-primary w-100">  
                            <i class="bi bi-cloud-upload"></i> Unggah Makalah  
                        </button>  
                    </form>  
                </div>  
            </div>  
        </div>  
    </div>  
</div>  

<?php  
// Simpan output ke variabel  
$content = ob_get_clean();  

// Sertakan layout  
include 'layout.php';  
?>