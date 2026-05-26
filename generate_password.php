<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Password Hash Generator - Traveloop</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        body {
            background: linear-gradient(135deg, #0f172a, #1e3a5f);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .generator-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            max-width: 600px;
            width: 100%;
        }
        .hash-output {
            background: #f8fafc;
            border: 2px dashed #cbd5e1;
            border-radius: 10px;
            padding: 15px;
            font-family: 'Courier New', monospace;
            font-size: 0.85rem;
            word-break: break-all;
            max-height: 150px;
            overflow-y: auto;
        }
        .copy-btn {
            transition: all 0.3s;
        }
        .copy-btn:hover {
            transform: translateY(-2px);
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="generator-card p-5">
            <div class="text-center mb-4">
                <i class="bi bi-shield-lock-fill text-warning" style="font-size: 3rem;"></i>
                <h2 class="fw-bold mt-3">Password Hash Generator</h2>
                <p class="text-muted">Generate password hash untuk admin Traveloop</p>
            </div>

            <form method="POST" action="">
                <div class="mb-4">
                    <label class="form-label fw-bold">
                        <i class="bi bi-key-fill text-primary me-2"></i>Password yang ingin di-hash
                    </label>
                    <input type="text" name="password" class="form-control form-control-lg" 
                           placeholder="Masukkan password..." required 
                           value="<?= isset($_POST['password']) ? htmlspecialchars($_POST['password']) : '' ?>">
                    <small class="text-muted">Contoh: admin123, superadmin, traveloop2024</small>
                </div>

                <button type="submit" name="generate" class="btn btn-warning btn-lg w-100 fw-bold shadow">
                    <i class="bi bi-gear-fill me-2"></i>Generate Hash
                </button>
            </form>

            <?php
            if (isset($_POST['generate']) && !empty($_POST['password'])) {
                $password = $_POST['password'];
                $hash = password_hash($password, PASSWORD_DEFAULT);
                ?>
                <div class="mt-4 p-4 bg-light rounded-4 border">
                    <h5 class="fw-bold mb-3">
                        <i class="bi bi-check-circle-fill text-success me-2"></i>Hash Berhasil Dibuat!
                    </h5>
                    
                    <div class="mb-3">
                        <label class="form-label fw-bold small text-muted">PASSWORD ASLI:</label>
                        <div class="alert alert-info mb-0 py-2">
                            <code><?= htmlspecialchars($password) ?></code>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold small text-muted">PASSWORD HASH:</label>
                        <div class="hash-output" id="hashOutput"><?= $hash ?></div>
                        <button type="button" class="btn btn-sm btn-dark mt-2 copy-btn" onclick="copyHash()">
                            <i class="bi bi-clipboard me-1"></i>Copy Hash
                        </button>
                    </div>

                    <div class="alert alert-warning border-0 shadow-sm">
                        <h6 class="fw-bold mb-2">
                            <i class="bi bi-info-circle-fill me-2"></i>Cara Menggunakan:
                        </h6>
                        <ol class="mb-0 small">
                            <li>Copy hash di atas</li>
                            <li>Buka phpMyAdmin → database <code>db_traveloop_fix</code></li>
                            <li>Klik tab "SQL"</li>
                            <li>Jalankan query berikut:</li>
                        </ol>
                    </div>

                    <div class="bg-dark text-light p-3 rounded-3 mt-3" style="font-family: monospace; font-size: 0.85rem;">
<pre class="mb-0 text-light">INSERT INTO `admin` 
(`nama_lengkap`, `email`, `password`) 
VALUES 
('Nama Admin', 'email@example.com', '<?= $hash ?>');</pre>
                    </div>
                </div>
                <?php
            }
            ?>

            <hr class="my-4">

            <div class="text-center">
                <a href="admin/admin.php" class="btn btn-outline-dark">
                    <i class="bi bi-arrow-left me-2"></i>Kembali ke Admin Panel
                </a>
            </div>

            <div class="mt-4 p-3 bg-light rounded-3">
                <h6 class="fw-bold mb-2">
                    <i class="bi bi-lightbulb-fill text-warning me-2"></i>Tips Keamanan:
                </h6>
                <ul class="small text-muted mb-0">
                    <li>Gunakan password minimal 8 karakter</li>
                    <li>Kombinasikan huruf besar, kecil, angka, dan simbol</li>
                    <li>Jangan gunakan password yang mudah ditebak</li>
                    <li>Hapus file ini setelah selesai digunakan untuk keamanan</li>
                </ul>
            </div>
        </div>
    </div>

    <script>
        function copyHash() {
            const hashText = document.getElementById('hashOutput').textContent;
            navigator.clipboard.writeText(hashText).then(() => {
                const btn = event.target.closest('button');
                const originalHTML = btn.innerHTML;
                btn.innerHTML = '<i class="bi bi-check-lg me-1"></i>Copied!';
                btn.classList.add('btn-success');
                btn.classList.remove('btn-dark');
                
                setTimeout(() => {
                    btn.innerHTML = originalHTML;
                    btn.classList.remove('btn-success');
                    btn.classList.add('btn-dark');
                }, 2000);
            });
        }
    </script>
</body>
</html>
