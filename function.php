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
    $id_kategori = $data['id_kategori'];
    $pilih = mysqli_query($koneksi, "SELECT * FROM dagangan where id_kategori=$id_kategori order by id_kategori DESC LIMIT 1");
    $nilai = mysqli_fetch_array($pilih);
    $jumlah =mysqli_num_rows($pilih);
    if($jumlah > 0){
        $a = $nilai['id_barang'];
        $b = 1;
        $id= $a + $b;
    } else{
        $a = $id_kategori;
        $b = 1;
        $id = "$a$b";
    }
    // echo "<script>alert('$a $b $id')</script>"; 
    $id_barang = $id; 
    $nama_barang = htmlspecialchars($data['nama']);
    $deskripsi_barang = htmlspecialchars($data['deskripsi']);
    $jumlah_barang = $data['jumlah'];
    $harga_barang = $data['harga'];
    $gambar = upload();

    if (!$gambar) {
        return false;
    }

    $sql = "INSERT INTO dagangan VALUES ('$id_barang','$id_kategori','$nama_barang','$gambar','$deskripsi_barang','$jumlah_barang','$harga_barang')";

    mysqli_query($koneksi, $sql);

    return mysqli_affected_rows($koneksi);
}


// Membuat fungsi hapus
function hapus($id_barang)
{
    global $koneksi;
    $pilih = mysqli_query($koneksi, "SELECT * FROM dagangan WHERE id_barang= $id_barang");
    $datafoto = mysqli_fetch_array($pilih);
    $foto = $datafoto['gambar_barang'];
    unlink("asset/images/produk/".$foto);
    mysqli_query($koneksi, "DELETE FROM dagangan WHERE id_barang = $id_barang");
    return mysqli_affected_rows($koneksi);
}

// Membuat fungsi ubah
function ubah($data)
{
    global $koneksi;

    $id_kategori = $data['id_kt'];
    $id_barang = $data['id_br'];
    $nama_barang = htmlspecialchars($data['nama']);
    $deskripsi_barang = htmlspecialchars($data['deskripsi']);
    $jumlah_barang = $data['jumlah'];
    $harga_barang = $data['harga'];
    $gambarLama = $data['gambarLama'];

    if ($_FILES['gambar']['error'] === 4) {
        $gambar = $gambarLama;
    } else {
        $gambar = upload();
    }
    unlink("asset/images/produk/".$gambarLama);

    $sql = "UPDATE dagangan SET nama_barang = '$nama_barang', gambar_barang = '$gambar', deskripsi_barang = '$deskripsi_barang', jumlah_barang = '$jumlah_barang', harga_barang = '$harga_barang' WHERE id_barang = $id_barang";

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
    move_uploaded_file($tmpName, 'asset/images/produk/' . $namaFileBaru);

    return $namaFileBaru;
}
