<?php 

include 'header.php';
date_default_timezone_set('Asia/Makassar'); // Setel zona waktu ke WITA
$nama_material = array(); // Inisialisasi variabel $nama_material sebagai array kosong

// Ambil parameter pencarian jika ada
$search = isset($_GET['search']) ? $_GET['search'] : '';

// Ambil parameter jumlah data per halaman jika ada
$per_page = isset($_GET['per_page']) ? (int)$_GET['per_page'] : 10;

// Ambil parameter halaman saat ini
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $per_page;

// Query untuk mendapatkan daftar pesanan, dibatasi sesuai pilihan pengguna atau berdasarkan pencarian
$query_condition = "";
if (!empty($search)) {
    $query_condition = " WHERE invoice LIKE '%$search%' OR kode_customer LIKE '%$search%' OR tanggal LIKE '%$search%'";
}

$query = "SELECT DISTINCT invoice, kode_customer, status, kode_produk, qty, terima, tolak, cek, tanggal 
            FROM produksi 
            $query_condition
            GROUP BY invoice 
            ORDER BY invoice ASC 
            LIMIT $offset, $per_page";

$result = mysqli_query($conn, $query);

// Query untuk mendapatkan total data
$total_query = "SELECT COUNT(DISTINCT invoice) as total FROM produksi $query_condition";
$total_result = mysqli_query($conn, $total_query);
$total_row = mysqli_fetch_assoc($total_result);
$total_data = $total_row['total'];
$total_pages = ceil($total_data / $per_page);

$sortage = mysqli_query($conn, "SELECT * FROM produksi WHERE cek = '1'");
$cek_sor = mysqli_num_rows($sortage);
?>

<div class="container">
    <style>
        .d-flex {
            display: flex;
        }

        .justify-content-between {
            justify-content: space-between;
        }

        .align-items-center {
            align-items: center;
        }

        .mb-3 {
            margin-bottom: 1rem;
        }

        .form-inline {
            display: flex;
            flex-wrap: nowrap;
        }

        .ml-3 {
            margin-left: 1rem;
        }

        .center-content {
            display: flex;
            align-items: center;
            justify-content: center;
            flex: 1;
        }

        #reload-btn {
            margin-left: auto;
        }
    </style>

    <h2 style="width: 100%; border-bottom: 4px solid gray"><b>Daftar Pesanan</b></h2>
    <br>
    <h5 class="bg-success" style="padding: 7px; width: 710px; font-weight: bold;">
        <marquee>Lakukan Reload Setiap Masuk Halaman ini, untuk menghindari terjadinya kesalahan data dan informasi</marquee>
    </h5>
    <div class="d-flex justify-content-between align-items-center mb-3">
        <form method="GET" action="" class="form-inline d-flex align-items-center">
            <div class="input-group">
                <input type="text" name="search" class="form-control" placeholder="Cari berdasarkan invoice, kode customer, atau tanggal" value="<?= $search ?>">
                <div class="input-group-append">
                    <button class="btn btn-primary" type="submit">Cari</button>
                </div>
            </div>
        </form>
        <div class="center-content">
            <!-- <span>Show</span> -->
            <!-- <div class="input-group ml-3">
                <select name="per_page" class="form-control" onchange="this.form.submit()">
                    <option value="10" <?= $per_page == 10 ? 'selected' : '' ?>>10</option>
                    <option value="15" <?= $per_page == 15 ? 'selected' : '' ?>>15</option>
                    <option value="20" <?= $per_page == 20 ? 'selected' : '' ?>>20</option>
                </select>
            </div> -->
        </div>
        <a href="produksi.php" class="btn btn-default ml-3" id="reload-btn"><i class="glyphicon glyphicon-refresh"></i> Reload</a>
    </div>

    <br>
    <!-- Tambahkan indikator pesan jika data tidak ditemukan -->
    <?php if (mysqli_num_rows($result) == 0 && !empty($search)) : ?>
        <div class="alert alert-warning" role="alert">
            Data yang Anda cari tidak ditemukan.
        </div>
    <?php endif; ?>

    <table class="table table-striped">
        <thead>
            <tr>
                <th scope="col">No</th>
                <th scope="col">Invoice</th>
                <th scope="col">Kode Customer</th>
                <th scope="col">Status</th>
                <th scope="col">Tanggal</th>
                <th scope="col">Action</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            $no = $offset + 1;
            while($row = mysqli_fetch_assoc($result)){
                $kodep = $row['kode_produk'];
                $inv = $row['invoice'];
                ?>
                <tr>
                    <td><?= $no; ?></td>
                    <td><?= $row['invoice']; ?></td>
                    <td><?= $row['kode_customer']; ?></td>
                    <td>
                        <?php if($row['terima'] == 1){ ?>
                            <span style="color: green; font-weight: bold;">Pesanan Diterima (Siap Kirim)</span>
                        <?php } elseif($row['tolak'] == 1){ ?>
                            <span style="color: red; font-weight: bold;">Pesanan Ditolak</span>
                        <?php } else { ?>
                            <span style="color: orange; font-weight: bold;"><?= $row['status']; ?></span>
                        <?php } ?>
                    </td>
                    <td><?= $row['tanggal']; ?></td>
                    <td>
                        <?php 
                        $t_bom = mysqli_query($conn, "SELECT * FROM bom_produk WHERE kode_produk = '$kodep'");
                        while($row1 = mysqli_fetch_assoc($t_bom)){
                            $kodebk = $row1['kode_bk'];
                            $inventory = mysqli_query($conn, "SELECT * FROM inventory WHERE kode_bk = '$kodebk'");
                            $r_inv = mysqli_fetch_assoc($inventory);
                            
                            if ($r_inv) {
                                $kebutuhan = $row1['kebutuhan'];    
                                $qtyorder = $row['qty'];
                                $inventory_qty = $r_inv['qty'];
                                $bom = ($kebutuhan * $qtyorder);
                                $hasil = $inventory_qty - $bom;
                                if($hasil < 0 && $row['tolak'] == 0){
                                    $nama_material[] = $r_inv['nama'];
                                    mysqli_query($conn, "UPDATE produksi SET cek = '1' WHERE invoice = '$inv'");
                                }
                            }
                        }
                        ?>
                        <?php if($row['tolak'] == 0 && $row['cek'] == 1 && $row['terima'] == 0){ ?>
                            <a href="inventory.php?cek=0" id="rq" class="btn btn-warning"><i class="glyphicon glyphicon-warning-sign"></i> Request Material</a> 
                            <a href="proses/tolak.php?inv=<?= $row['invoice']; ?>" class="btn btn-danger" onclick="return confirm('Yakin Ingin Menolak?')"><i class="glyphicon glyphicon-remove-sign"></i> Tolak</a> 
                        <?php } elseif($row['terima'] == 0 && $row['cek'] == 0){ ?>
                            <a href="proses/terima.php?inv=<?= $row['invoice']; ?>&kdp=<?= $row['kode_produk']; ?>" class="btn btn-success"><i class="glyphicon glyphicon-ok-sign"></i> Terima</a> 
                            <a href="proses/tolak.php?inv=<?= $row['invoice']; ?>" class="btn btn-danger" onclick="return confirm('Yakin Ingin Menolak?')"><i class="glyphicon glyphicon-remove-sign"></i> Tolak</a> 
                        <?php } ?>
                        <a href="detailorder.php?inv=<?= $row['invoice']; ?>&cs=<?= $row['kode_customer']; ?>" type="submit" class="btn btn-primary"><i class="glyphicon glyphicon-eye-open"></i> Detail Pesanan</a>
                    </td>
                </tr>
                <?php
                $no++; 
            }
            ?>
        </tbody>
    </table>

    <!-- Pagination -->
    <nav>
        <ul class="pagination">
            <?php for ($i = 1; $i <= $total_pages; $i++) { ?>
                <li class="page-item <?= $i == $page ? 'active' : '' ?>">
                    <a class="page-link" href="?page=<?= $i ?>&per_page=<?= $per_page ?>&search=<?= $search ?>"><?= $i ?></a>
                </li>
            <?php } ?>
        </ul>
    </nav>

    <?php 
    if($cek_sor > 0 && !empty($nama_material)){
    ?>
    <br>
    <br>
    <div class="row">
        <div class="col-md-4 bg-danger" style="padding:10px;">
            <h4>Kekurangan Material </h4>
            <h5 style="color: red; font-weight: bold;">Silahkan Tambah Stok Material dibawah ini:</h5>
            <table class="table table-striped">
                <tr>
                    <th>No</th>
                    <th>Material</th>
                </tr>
                <?php 
                $arr = array_values(array_unique($nama_material));
                for ($i = 0; $i < count($arr); $i++) { 
                ?>
                <tr>
                    <td><?= $i + 1 ?></td>
                    <td><?= $arr[$i]; ?></td>
                </tr>
                <?php } ?>
            </table>
        </div>
    </div>
    <?php 
    }
    ?>

</div>

<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>

<?php 
include 'footer.php';
?>
