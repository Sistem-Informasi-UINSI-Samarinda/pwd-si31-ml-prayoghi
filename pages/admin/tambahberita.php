<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

include '../../config/koneksi.php';
$tes = "SELECT * FROM kategori_berita";
$kategori = mysqli_query($conn, $tes);

?>


<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard Admin</title>
  <link rel="stylesheet" href="../../assets/css/adminStyles.css">
</head>
<body>

  <div class="sidebar">
    <h2>Admin Panel</h2>
    <ul>
      <li><a href="#">Dashboard</a></li>
      <li><a href="#">User</a></li>
      <li><a href="berita.php">Berita</a></li>
      <li><a href="#">Galeri</a></li>
      <li><a href="logout.php" class="logout">Logout</a></li>
    </ul>
  </div>

  <div class="main-content">
    <header>
        <a href="kategori.php">List Kategori</a>
        <a href="tambahberita.php">+ Tambah Berita</a>
    </header>

    <section class="cards">
        <div class="card">
            <form action="" method="POST" enctype="multipart/form-data">
                <label for="">Judul Berita</label> <br>
                <input type="text" name="judul_berita" placeholder="isikan judul berita" required>
                <br>
                <br>

                <label for="">Kategori</label> <br>
                <select name="id_kategori" required>
                    <option value="">--Pilih Kategori --</option>
                    <?php while($row = mysqli_fetch_assoc($kategori)) {
                        ?>
                    <option value="<?= $row['id_kategori'] ?>"><?php echo $row['nama_kategori'] ?></option>
                    <?php } ?>
                </select>
                <br>
                <br>
                <label for="">Tanggal Berita</label> <br>
                <input type="date" name="tanggal_berita">
                <br>
                <br>

                <label for="">Isi Berita</label> <br>
                <textarea name="isi_berita" rows="15" cols="70"></textarea>
                <br>
                <br>

                <label for="">Gambar Berita</label> <br>
                <input type="file" name="foto_berita">

                <br>
                <br>
                <button type="submit" name="simpan">Simpan Berita</button>
            </form>
        </div>
    </section>
  </div>

<?php 
    if(isset($_POST['simpan'])){
        $id_kategori = $_POST['id_kategori'];
        $judul_berita = $_POST['judul_berita'];
        $isi_berita = $_POST['isi_berita'];
        $tanggal_berita = $_POST['tanggal_berita'];

        // Upload File
        $gambar = $_FILES['foto_berita']['name'];
        $tmp = $_FILES['foto_berita']['tmp_name'];
        $folder = '../../uploads/';

        // agar nama file unique
        $foto_berita = uniqid() .  "_" . $gambar;

        // opsional
        if($_FILES['foto_berita']['error'] !== UPLOAD_ERR_OK){
            echo "ERROR UPLOAD GAMBAR, KODE: ". $_FILES['foto_berita']['error'];
        }
        // eksekusi Upload
        move_uploaded_file($tmp, $folder . $foto_berita);

        $query = "
        INSERT INTO berita (id_kategori, judul_berita, isi_berita, foto_berita, tanggal_berita)
        VALUES ('$id_kategori', '$judul_berita', '$isi_berita', '$foto_berita', '$tanggal_berita')
        ";

        if(mysqli_query($conn, $query)){
            echo "<script>
                alert('Berita Telah di unggah');
                window.location.href='berita.php';
            </script>";
        }
        else{
             echo "<script>
                alert('Berita Gagal di unggah');
                window.location.href='berita.php';
            </script>";
        }
    }
?>

</body>
</html>
