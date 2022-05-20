<?php
session_start();
// Jika tidak bisa login maka balik ke login.php
// jika masuk ke halaman ini melalui url, maka langsung menuju halaman login
if (!isset($_SESSION['login'])) {
    header('location:login.php');
    exit;
}

// Memanggil atau membutuhkan file function.php
require 'function.php';

// Mengambil data dari nis dengan fungsi get
$id_barang = $_GET['id_barang'];

// Menampilkan semua data dari table siswa berdasarkan nis secara Descending
$dagangan = query("SELECT * FROM dagangan WHERE id_barang=$id_barang")[0];
$idk=$dagangan['id_kategori'];
$kate = mysqli_query($koneksi,"SELECT * FROM kategori WHERE id_kategori=$idk");
$kategori = mysqli_fetch_array($kate);

// Jika fungsi tambah lebih dari 0/data tersimpan, maka munculkan alert dibawah
if (isset($_POST['rubah'])) {
    if (ubah($_POST) > 0) {
        echo "<script>
                alert('Data Dagangan berhasil diubah!');
                document.location.href = 'dagangan';
            </script>";
    } else {
        // Jika fungsi tambah dari 0/data tidak tersimpan, maka munculkan alert dibawah
        echo "<script>
                alert('Data Dagangan gagal diubah!');
            </script>";
    }
}
$id_kt=$dagangan['id_kategori'];
$id_br=$dagangan['id_barang'];

?>
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>WK Admin - Edit Product</title>
    <!--====== Favicon Icon ======-->
    <link rel="shortcut icon" href="asset/images/icon/Icon WK.png" type="image/png">

    <!----===== Boxicons CSS ===== -->
    <link href="asset/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="asset/css/sb-admin-2.min.css" rel="stylesheet">

    <!-- Custom styles for this page -->
    <link href="asset/vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <?php include 'side.blade.php';?>

                <!-- Begin Page Content -->
                <div class="container-fluid">

                    <!-- Page Heading -->
                    <h1 class="h3 mb-2 text-gray-800">Edit Product</h1>
                    <p class="mb-2"></p>
                    
                    <!-- form -->
                    <form action="" method="post" enctype="multipart/form-data">
                        <div class="row mt-2">
                            <div class="col-8">
                                <h4>Tipe Produk</h4>
                                <div class="form-group ml-4">                                
                                    <input type="text" name="id_kt" class="form-control" value="<?php echo "$id_kt" ?>">
                                    <label class="mt-4" for="">id Produk</label>
                                    <input type="text" name="id_br" class="form-control" value="<?php echo "$id_br" ?>">
                                    
                                    <label class="mt-4" for="">Nama Produk</label>
                                        <input type="text" name="nama" class="form-control" value="<?= $dagangan['nama_barang'];?>">                
                                </div>
                                <div class="form-group mt-4">
                                    <h4>Informasi Produk</h4>
                                    <div class="col">
                                        <label for="gambar" class="form-label">Gambar <i>(Saat ini)</i></label> <br>
                                        <img src="asset/images/produk/<?= $dagangan['gambar_barang'];?>" class="thumbnail-view" alt=""><br>
                                        <input type="hidden" name="gambarLama" value="<?= $dagangan['gambar_barang']; ?>">
                                        <label >Gambar Produk</label><br>
                                        <label class="imagepicker imagepicker-add thumbnail">
                                        <input type='file' id="gambar" name="gambar">
                                    </div>
                                </div>
                                <div class="form-group mt-4">
                                    <div class="col">
                                        <div class="mb-3 ">
                                            <label for="validationTextarea">Deskripsi Produk</label>
                                            <textarea class="form-control" name="deskripsi" value="<?= $dagangan['deskripsi_barang'];?>"><?= $dagangan['deskripsi_barang'];?></textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group mt-4">
                                    <h4>Informasi Stok dan Harga</h4>
                                    <div class="col">
                                        <label >Harga Produk</label><br>
                                        <div class=" input-group mb-3">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">Rp.</span>
                                            </div>
                                            <input type="text" name="harga" class="form-control" value="<?= $dagangan['harga_barang'];?>">
                                        </div>
                                        <label for="">Stok</label>
                                        <input type="text" name="jumlah" class="form-control" value="<?= $dagangan['jumlah_barang'];?>">
                                    </div>
                                </div>
                                <div class="col text-align-center mb-5">
                                    <button type="submit" class="btn btn-lg btn-primary btn-block" name="rubah">Ubah Dagangan</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <!-- /.container-fluid -->

            </div>
            <!-- End of Main Content -->

            <!-- Footer -->
            <footer class="sticky-footer bg-white">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
                        <span>Copyright &copy; WarungKu </span>
                    </div>
                </div>
            </footer>
            <!-- End of Footer -->

        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    

    <!-- Bootstrap core JavaScript-->
    <script src="asset/vendor/jquery/jquery.min.js"></script>
    <script src="asset/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="asset/vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="asset/js/sb-admin-2.min.js"></script>

</body>

</html>