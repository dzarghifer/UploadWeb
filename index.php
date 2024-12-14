<?php  
include 'includes/db.php';  

// Ambil data makalah  
$stmt = $pdo->query("SELECT * FROM makalah ORDER BY id DESC");  
$makalah = $stmt->fetchAll(PDO::FETCH_ASSOC);  



// Mulai output buffering  
ob_start();  
?>  

<!-- Main Content -->  
<div class="container mt-4">  
    <div class="row">  
        <div class="col-12">  
            <div class="card p-5 shadow-lg rounded-5" id="card-wellcome">  
                <div class="card-body">  
                    <h1 class="card-title mb-4" id="wellcome">Selamat Datang di Website Pengumpulan Tugas</h1>  
                    <p class="card-text lead" id="wellcome-text">Aplikasi Website Pengumpulan Tugas.</p>  
                </div>  
            </div>  
        </div>  
    </div>  
</div>  

<!-- Makalah Cards Section -->  
<div class="container">  
    <div class="row" id="makalahSection">  
        <div class="col-12">  
            <div class="card mt-4 border-0">  
                <div class="card-header bg-transparent d-flex justify-content-between align-items-center">  
                    <h3 class="card-title px-5 py-3 mb-3" id="card-name">Daftar Makalah</h3>  
                </div>  
                <div class="card-body">  
                    <div class="row g-4" id="makalahCardsContainer">  
                    <?php foreach ($makalah as $m): ?>  
                      <div class="col-md-4">  
                          <div class="card h-100">  
                              <div class="card-body">  
                                  <div class="card-content">  
                                      <div class="mb-3">  
                                          <h5 class="card-title mb-1"><?php echo htmlspecialchars($m['judul']); ?></h5>  
                                      </div>  
                                  </div>  

                                  <div class="card-author">  
                                      <small class="text-muted">  
                                          <strong>Penulis:</strong> <?php echo htmlspecialchars($m['penulis']); ?><br>  
                                          <div class="d-flex justify-content-between mt-2">  
                                              <a href="view.php?id=<?php echo $m['id']; ?>" class="btn btn-sm btn-info">  
                                                  <i class="bi bi-eye"></i> Lihat Detail  
                                              </a>  
                                              <a href="<?php echo htmlspecialchars($m['pdf']); ?>" target="_blank" class="btn btn-sm btn-primary">  
                                                  <i class="bi bi-file-pdf"></i> Buka PDF  
                                              </a>  
                                              <a href="delete.php?id=<?php echo $m['id']; ?>"   
                                                class="btn btn-sm btn-danger"   
                                                onclick="return confirm('Anda yakin ingin menghapus makalah ini?');">  
                                                  <i class="bi bi-trash"></i>  
                                              </a>  
                                          </div>  
                                      </small>  
                                  </div>  
                              </div>  
                          </div>  
                      </div>  
                      <?php endforeach; ?>

                        <!-- Add Makalah Card -->  
                        <div class="col-md-4">  
                            <div class="card h-100 add-card text-center d-flex align-items-center justify-content-center" style="cursor: pointer;" onclick="window.location.href='upload.php'">  
                                <div class="card-body">  
                                    <i class="bi bi-plus-circle text-muted" style="font-size: 3rem;"></i>  
                                    <h5 class="card-title mt-3 text-muted">Tambah Makalah Baru</h5>  
                                </div>  
                            </div>  
                        </div>  
                    </div>  
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