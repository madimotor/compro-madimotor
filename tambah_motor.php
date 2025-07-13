<?php
$page_title = 'Jual Motor Anda';
include 'templates/header.php';

// Pastikan pengguna sudah login
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$error_message = '';
$success_message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_SESSION['user_id'];
    $merk = mysqli_real_escape_string($conn, $_POST['merk']);
    $model = mysqli_real_escape_string($conn, $_POST['model']);
    $tahun = (int)$_POST['tahun'];
    $harga = (int)$_POST['harga'];
    $deskripsi = mysqli_real_escape_string($conn, $_POST['deskripsi']);

    // Proses upload gambar
    $gambar = $_FILES['gambar'];
    $gambar_nama = time() . '_' . basename($gambar['name']);
    $gambar_target = "assets/uploads/" . $gambar_nama;
    
    // Validasi file
    $imageFileType = strtolower(pathinfo($gambar_target, PATHINFO_EXTENSION));
    $allowed_types = ['jpg', 'jpeg', 'png', 'gif'];
    if (in_array($imageFileType, $allowed_types) && $gambar['size'] > 0) {
        if (move_uploaded_file($gambar['tmp_name'], $gambar_target)) {
            // Gambar berhasil diupload, simpan ke database
            $query = "INSERT INTO motor (user_id, merk, model, tahun, harga, deskripsi, gambar) 
                      VALUES ('$user_id', '$merk', '$model', '$tahun', '$harga', '$deskripsi', '$gambar_nama')";
            
            if(mysqli_query($conn, $query)) {
                $success_message = "Motor berhasil ditambahkan!";
            } else {
                $error_message = "Gagal menyimpan data ke database: " . mysqli_error($conn);
            }
        } else {
            $error_message = "Gagal mengupload gambar.";
        }
    } else {
        $error_message = "Hanya file JPG, JPEG, PNG & GIF yang diizinkan dan file tidak boleh kosong.";
    }
}
?>

<h2>Jual Motor Anda</h2>
<p>Isi formulir di bawah ini untuk menjual motor Anda.</p>

<?php if ($error_message): ?>
    <div class="alert alert-danger"><?php echo $error_message; ?></div>
<?php endif; ?>
<?php if ($success_message): ?>
    <div class="alert alert-success"><?php echo $success_message; ?></div>
<?php endif; ?>


<form action="tambah_motor.php" method="post" enctype="multipart/form-data">
    <div class="mb-3">
        <label for="merk" class="form-label">Merk Motor</label>
        <input type="text" class="form-control" id="merk" name="merk" required>
    </div>
    <div class="mb-3">
        <label for="model" class="form-label">Model Motor</label>
        <input type="text" class="form-control" id="model" name="model" required>
    </div>
    <div class="row">
        <div class="col-md-6 mb-3">
            <label for="tahun" class="form-label">Tahun</label>
            <input type="number" class="form-control" id="tahun" name="tahun" min="1950" max="<?php echo date('Y'); ?>" required>
        </div>
        <div class="col-md-6 mb-3">
            <label for="harga" class="form-label">Harga (Rp)</label>
            <input type="number" class="form-control" id="harga" name="harga" required>
        </div>
    </div>
    <div class="mb-3">
        <label for="deskripsi" class="form-label">Deskripsi</label>
        <textarea class="form-control" id="deskripsi" name="deskripsi" rows="4"></textarea>
    </div>
    <div class="mb-3">
        <label for="gambar" class="form-label">Foto Motor</label>
        <input class="form-control" type="file" id="gambar" name="gambar" required>
    </div>
    <button type="submit" class="btn btn-primary">Jual Motor</button>
</form>

<?php include 'templates/footer.php'; ?>