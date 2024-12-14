<?php  
include 'includes/db.php';  

// Periksa apakah ID diberikan  
if (!isset($_GET['id'])) {  
    die("ID tidak valid");  
}  

$id = intval($_GET['id']);  

try {  
    // Ambil data makalah untuk mendapatkan path file  
    $stmt = $pdo->prepare("SELECT pdf FROM makalah WHERE id = ?");  
    $stmt->execute([$id]);  
    $makalah = $stmt->fetch(PDO::FETCH_ASSOC);  

    if (!$makalah) {  
        die("Makalah tidak ditemukan");  
    }  

    // Hapus file PDF  
    if (file_exists($makalah['pdf'])) {  
        unlink($makalah['pdf']);  
    }  

    // Hapus dari database  
    $stmt = $pdo->prepare("DELETE FROM makalah WHERE id = ?");  
    $stmt->execute([$id]);  

    // Redirect dengan pesan sukses  
    header("Location: index.php?delete=success");  
    exit();  
} catch (PDOException $e) {  
    die("Error: " . $e->getMessage());  
}  
?>