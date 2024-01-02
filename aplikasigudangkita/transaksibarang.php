<!DOCTYPE html>
<html lang="en">
<head>
  <title>SIM Gudang V.2023 - Form Transaksi Barang</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
  <script>
function cari(){
    //membuat variabel val_cari dan mengisinya dengan nilai pada field cari
    var val_cari = $('#KodeBarang').val();
 
    //kode 1
    var request = $.ajax ({
        url : "caribarang.php",
        data : "KodeBarang="+val_cari,
        type : "POST",
        dataType: "html"
    });
 
    //menampilkan pesan Sedang mencari saat aplikasi melakukan proses pencarian
    $('#NamaBarang').html('Sedang Mencari…');
 
    //Jika pencarian selesai
    request.done(function(output) {
        //Tampilkan hasil pencarian pada tag div dengan id hasil-cari
        $('#NamaBarang').html(output);
    });
 
}
</script>
</head>
<body>
  
<div class="container">
  <h1>Form Transaksi Barang</h1>
<form method="post">
  <div class="form-group row">
    <label for="KodeGudang" class="col-4 col-form-label">KodeGudang</label> 
    <div class="col-8">
      <select id="KodeGudang" name="KodeGudang" class="form-control custom-select" required="required">
        <option value="">Silahkan pilih</option>
        <?php include('koneksi.db.php');
        $sql="select * from gudang";
        $q=mysqli_query($koneksi,$sql);
        $r=mysqli_fetch_array($q);
        if (!empty($r)) {
        do {
          echo '<option value="'.$r['KodeGudang'].'"> Gudang '.$r['KodeGudang'].' '.$r['Alamat'].'</option>';
        } while($r=mysqli_fetch_array($q));
        }
        mysqli_close($koneksi);
        ?>
      </select>
    </div>
  </div>
  <div class="form-group row">
    <label for="KodeBarang" class="col-4 col-form-label">KodeBarang</label> 
    <div class="col-8">
      <input id="KodeBarang" name="KodeBarang" type="text" class="form-control" required="required" onkeyup="cari()">
    </div>
  </div>
  <div class="form-group row">
    <label class="col-4 cor-form-label">Nama Barang</label>
    <div class="col-8">
     <div id="NamaBarang"><!--Nama Barang--></div>  
    </div>
  </div>
  <div class="form-group row">
    <label for="WaktuTransaksi" class="col-4 col-form-label">WaktuTransaksi</label> 
    <div class="col-8">
      <input id="WaktuTransaksi" name="WaktuTransaksi" type="date" class="form-control" value="<?php echo date('Y-m-d');?>">
    </div>
  </div>
  <div class="form-group row">
    <label for="StatusTransaksi" class="col-4 col-form-label">StatusTransaksi</label> 
    <div class="col-8">
      <select id="StatusTransaksi" name="StatusTransaksi" class="custom-select form-control" required="required">
        <option value="Masuk">Masuk</option>
        <option value="Keluar">Keluar</option>
      </select>
    </div>
  </div>
  <div class="form-group row">
    <label for="Jumlah" class="col-4 col-form-label">Jumlah</label> 
    <div class="col-8">
      <input id="Jumlah" name="Jumlah" type="text" class="form-control" required="required">
    </div>
  </div>
  <div class="form-group row">
    <label for="Keterangan" class="col-4 col-form-label">Keterangan</label> 
    <div class="col-8">
      <textarea id="Keterangan" name="Keterangan" cols="40" rows="5" class="form-control"></textarea>
    </div>
  </div> 
  <div class="form-group row">
    <div class="offset-4 col-8">
      <button name="submit" type="submit" class="btn btn-primary">Submit</button>
    </div>
  </div>
</form>
<?php if (isset($_POST['submit'])) {
  $KodeGudang=filter_var($_POST['KodeGudang'],FILTER_SANITIZE_STRING);
  $KodeBarang=filter_var($_POST['KodeBarang'],FILTER_SANITIZE_STRING);
  $WaktuTransaksi=filter_var($_POST['WaktuTransaksi'],FILTER_SANITIZE_STRING);
  $StatusTransaksi=filter_var($_POST['StatusTransaksi'],FILTER_SANITIZE_STRING);
  $Jumlah=filter_var($_POST['Jumlah'],FILTER_SANITIZE_STRING);
  $Keterangan=filter_var($_POST['Keterangan'],FILTER_SANITIZE_STRING);
  include('koneksi.db.php');
  $sql="INSERT INTO `barangdigudang`(`WaktuTransaksi`, `StatusTransaksi`, `Jumlah`, `Keterangan`, `KodeGudang`, `KodeBarang`) VALUES ('".$WaktuTransaksi."','".$StatusTransaksi."','".$Jumlah."','".$Keterangan."','".$KodeGudang."','".$KodeBarang."')";
  $q=mysqli_query($koneksi,$sql);
  if ($q) {
    if ($StatusTransaksi=="Masuk") {
      $sqlbarang="update barang set JumlahStok=JumlahStok+".$Jumlah." where KodeBarang='".$KodeBarang."'";
    } else {
      $sqlbarang="update barang set JumlahStok=JumlahStok-".$Jumlah." where KodeBarang='".$KodeBarang."'";
    }
    $qbarang=mysqli_query($koneksi,$sqlbarang);
    echo '<div class="alert alert-success alert-dismissible">
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    <strong>Success!</strong> Rekord sukses disimpan !.
  </div>';
  } else {
    echo '<div class="alert alert-danger alert-dismissible">
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    <strong>Gagal!</strong> Rekord gagal disimpan !.
  </div>';
  }
}
?>
</div>
</body>
</html>