<?php
// Koneksi Database
$koneksi = mysqli_connect("localhost", "root", "", "oasis");
$home="index";

// membuat fungsi query dalam bentuk array
function query($query)
{
    // Koneksi database
    global $koneksi;

    $result = mysqli_query($koneksi, $query);

    // membuat varibale array
    $rows = [];

    // mengambil semua data dalam bentuk array
    while ($row = mysqli_fetch_assoc($result)) {
        $rows[] = $row;
    }

    return $rows;
}

// Membuat fungsi tambah
function tambah($data)
{
    global $koneksi;
    $kategori = $data['kategori'];
    $pilih = mysqli_query($koneksi, "SELECT * FROM product where kategori=$kategori order by kategori DESC LIMIT 1");
    $id = mysqli_fetch_array($pilih);
    $id_produk = $id+1; 
    $nama_produk = htmlspecialchars($data['nama']);
    $deskripsi_produk = htmlspecialchars($data['deskripsi']);
    $jumlah_produk = $data['jumlah'];
    $harga_produk = $data['harga'];
    $gambar = upload();

    if (!$gambar) {
        return false;
    }

    $sql = "INSERT INTO product VALUES ('$id_produk','$kategori','$nama_produk','$gambar','$deskripsi_produk','$jumlah_produk','$harga_produk')";

    mysqli_query($koneksi, $sql);

    return mysqli_affected_rows($koneksi);
}


// Membuat fungsi hapus
function hapus($id_produk)
{
    global $koneksi;
    $pilih = mysqli_query($koneksi, "SELECT * FROM product WHERE id_produk= $id_produk");
    $datafoto = mysqli_fetch_array($pilih);
    $foto = $datafoto['gambar_produk'];
    unlink("assets/img/produk/".$foto);
    mysqli_query($koneksi, "DELETE FROM product WHERE id_produk = $id_produk");
    return mysqli_affected_rows($koneksi);
}

// Membuat fungsi ubah
function ubah($data)
{
    global $koneksi;

    $kategori = $data['id_kt'];
    $id_produk = $data['id_br'];
    $nama_produk = htmlspecialchars($data['nama']);
    $deskripsi_produk = htmlspecialchars($data['deskripsi']);
    $jumlah_produk = $data['jumlah'];
    $harga_produk = $data['harga'];
    $gambarLama = $data['gambarLama'];

    if ($_FILES['gambar']['error'] === 4) {
        $gambar = $gambarLama;
    } else {
        $gambar = upload();
    }
    unlink("assets/img/produk/".$gambarLama);

    $sql = "UPDATE product SET nama_produk = '$nama_produk', gambar_produk = '$gambar', deskripsi_produk = '$deskripsi_produk', jumlah_produk = '$jumlah_produk', harga_produk = '$harga_produk' WHERE id_produk = $id_produk";

    mysqli_query($koneksi, $sql);

    return mysqli_affected_rows($koneksi);
}

// Membuat fungsi upload gambar
function upload()
{
    // Syarat
    $namaFile = $_FILES['gambar']['name'];
    $ukuranFile = $_FILES['gambar']['size'];
    $error = $_FILES['gambar']['error'];
    $tmpName = $_FILES['gambar']['tmp_name'];

    // Jika tidak mengupload gambar atau tidak memenuhi persyaratan diatas maka akan menampilkan alert dibawah
    if ($error === 4) {
        echo "<script>alert('Pilih gambar terlebih dahulu!');</script>";
        return false;
    }

    // format atau ekstensi yang diperbolehkan untuk upload gambar adalah
    $extValid = ['jpg', 'jpeg', 'png'];
    $ext = explode('.', $namaFile);
    $ext = strtolower(end($ext));

    // Jika format atau ekstensi bukan gambar maka akan menampilkan alert dibawah
    if (!in_array($ext, $extValid)) {
        echo "<script>alert('Yang anda upload bukanlah gambar!');</script>";
        return false;
    }

    // Jika ukuran gambar lebih dari 3.000.000 byte maka akan menampilkan alert dibawah
    if ($ukuranFile > 3000000) {
        echo "<script>alert('Ukuran gambar anda terlalu besar!');</script>";
        return false;
    }

    // nama gambar akan berubah angka acak/unik jika sudah berhasil tersimpan
    $namaFileBaru = uniqid();
    $namaFileBaru .= '.';
    $namaFileBaru .= $ext;

    // memindahkan file ke dalam folde img dengan nama baru
    move_uploaded_file($tmpName, 'assets/img/produk/' . $namaFileBaru);

    return $namaFileBaru;
}
