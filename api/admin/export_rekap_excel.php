<?php
session_start();
require_once __DIR__ . "/../../config/connection.php";

if (!isset($_SESSION['id_akun']) || ($_SESSION['role'] ?? '') !== 'admin') {
    header("Location: ../../admin/login_admin.php");
    exit;
}

$periode = $_GET['periode'] ?? 'custom';
if ($periode === 'hari_ini') {
    $start = date('Y-m-d');
    $end = date('Y-m-d');
} elseif ($periode === '7_hari') {
    $start = date('Y-m-d', strtotime('-6 days'));
    $end = date('Y-m-d');
} elseif ($periode === 'bulan_ini') {
    $start = date('Y-m-01');
    $end = date('Y-m-d');
} elseif ($periode === 'semua') {
    $start = '2020-01-01';
    $end = date('Y-m-d', strtotime('+1 year'));
} else {
    $start = $_GET['start_date'] ?? '2026-01-01';
    $end = $_GET['end_date'] ?? date('Y-m-d');
}

$startEsc = mysqli_real_escape_string($connect, $start);
$endEsc = mysqli_real_escape_string($connect, $end);

$whereTrx = "WHERE t.status = 'selesai' AND DATE(t.tanggal_masuk) BETWEEN '$startEsc' AND '$endEsc'";

$summary = mysqli_fetch_assoc(mysqli_query($connect, "
    SELECT
        COALESCE(SUM(t.total_pembayaran), 0) AS omzet,
        COUNT(DISTINCT t.id_transaksi) AS total_trx
    FROM transaksi t
    $whereTrx
"));

$katSummary = mysqli_query($connect, "
    SELECT p.kategori, COALESCE(SUM(dt.jumlah), 0) AS qty, COALESCE(SUM(dt.subtotal), 0) AS nominal
    FROM detail_transaksi dt
    JOIN transaksi t ON dt.id_transaksi = t.id_transaksi
    JOIN produk p ON dt.id_produk = p.id_produk
    $whereTrx
    GROUP BY p.kategori
");

$qtyHC = 0; $nomHC = 0; $qtyMN = 0; $nomMN = 0;
while ($katSummary && $r = mysqli_fetch_assoc($katSummary)) {
    if ($r['kategori'] === 'minuman') { $qtyMN = (int)$r['qty']; $nomMN = (float)$r['nominal']; }
    else { $qtyHC = (int)$r['qty']; $nomHC = (float)$r['nominal']; }
}
$totalQty = $qtyHC + $qtyMN;
$omzet = (float) ($summary['omzet'] ?? 0);
$keuntungan = $omzet * 0.22;

$topProduk = mysqli_query($connect, "
    SELECT p.kode_produk, p.nama_produk, p.kategori, p.harga, SUM(dt.jumlah) AS terjual, SUM(dt.subtotal) AS pendapatan
    FROM detail_transaksi dt
    JOIN transaksi t ON dt.id_transaksi = t.id_transaksi
    JOIN produk p ON dt.id_produk = p.id_produk
    $whereTrx
    GROUP BY p.id_produk
    ORDER BY terjual DESC
");

$allTrx = mysqli_query($connect, "
    SELECT t.*, a.nama AS nama_fo,
           (SELECT GROUP_CONCAT(CONCAT(p.nama_produk, ' (', dt.jumlah, 'x Rp', FORMAT(dt.harga_satuan, 0, 'id_ID'), ')') SEPARATOR '; ')
            FROM detail_transaksi dt JOIN produk p ON dt.id_produk = p.id_produk
            WHERE dt.id_transaksi = t.id_transaksi) AS daftar_item,
           (SELECT COALESCE(SUM(dt.jumlah), 0) FROM detail_transaksi dt WHERE dt.id_transaksi = t.id_transaksi) AS total_item
    FROM transaksi t
    LEFT JOIN akun a ON t.id_fo = a.id_akun
    $whereTrx
    ORDER BY t.tanggal_masuk DESC, t.id_transaksi DESC
");

$filename = "Rekap_Penjualan_TOKO33_" . $start . "_sd_" . $end . ".xls";

header("Content-Type: application/vnd.ms-excel; charset=utf-8");
header("Content-Disposition: attachment; filename=" . $filename);
header("Pragma: no-cache");
header("Expires: 0");

echo "\xEF\xBB\xBF"; // UTF-8 BOM for Excel
?>
<html xmlns:o="urn:schemas-microsoft-com:office:office"
      xmlns:x="urn:schemas-microsoft-com:office:excel"
      xmlns="http://www.w3.org/TR/REC-html40">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <style>
        body { font-family: Arial, sans-serif; font-size: 11pt; }
        table { border-collapse: collapse; width: 100%; margin-bottom: 20px; }
        th, td { border: 1px solid #333333; padding: 6px 10px; vertical-align: middle; }
        .title { font-size: 14pt; font-weight: bold; color: #176B4D; border: none; }
        .subtitle { font-size: 10pt; color: #333333; border: none; }
        .th-green { background-color: #176B4D; color: #FFFFFF; font-weight: bold; text-align: center; }
        .th-soft { background-color: #DDF3E5; color: #176B4D; font-weight: bold; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .bold { font-weight: bold; }
    </style>
</head>
<body>
    <table>
        <tr><td colspan="9" class="title">LAPORAN REKAPITULASI PENJUALAN TOKO 33</td></tr>
        <tr><td colspan="9" class="subtitle">Alamat: Jl. Medayu Selatan XV No.33 | Email: tokotigatiga@gmail.com | Telp: 081234567890</td></tr>
        <tr><td colspan="9" class="subtitle">Periode Laporan: <?= htmlspecialchars($start) ?> s/d <?= htmlspecialchars($end) ?> (Dicetak oleh: <?= htmlspecialchars($_SESSION['nama'] ?? 'Admin') ?> pada <?= date('d-m-Y H:i') ?> WIB)</td></tr>
    </table>

    <h3>1. RINGKASAN EKSEKUTIF PENJUALAN</h3>
    <table>
        <thead>
            <tr>
                <th class="th-green">Total Transaksi Selesai</th>
                <th class="th-green">Total Produk Terjual (Pcs)</th>
                <th class="th-green">Terjual Home Care (Pcs)</th>
                <th class="th-green">Omzet Home Care (Rp)</th>
                <th class="th-green">Terjual Minuman (Pcs)</th>
                <th class="th-green">Omzet Minuman (Rp)</th>
                <th class="th-green">Total Omzet Keseluruhan (Rp)</th>
                <th class="th-green">Estimasi Keuntungan 22% (Rp)</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td class="text-center bold"><?= (int)($summary['total_trx'] ?? 0) ?></td>
                <td class="text-center bold"><?= $totalQty ?></td>
                <td class="text-center"><?= $qtyHC ?></td>
                <td class="text-right"><?= number_format($nomHC, 0, ',', '.') ?></td>
                <td class="text-center"><?= $qtyMN ?></td>
                <td class="text-right"><?= number_format($nomMN, 0, ',', '.') ?></td>
                <td class="text-right bold"><?= number_format($omzet, 0, ',', '.') ?></td>
                <td class="text-right bold"><?= number_format($keuntungan, 0, ',', '.') ?></td>
            </tr>
        </tbody>
    </table>

    <h3>2. REKAPITULASI PENJUALAN PER PRODUK</h3>
    <table>
        <thead>
            <tr>
                <th class="th-green">No</th>
                <th class="th-green">Kode Produk</th>
                <th class="th-green">Nama Produk</th>
                <th class="th-green">Kategori</th>
                <th class="th-green">Harga Satuan (Rp)</th>
                <th class="th-green">Jumlah Terjual (Pcs)</th>
                <th class="th-green">Total Pendapatan (Rp)</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $noProd = 1;
            if ($topProduk && mysqli_num_rows($topProduk) > 0):
                while ($tp = mysqli_fetch_assoc($topProduk)):
            ?>
            <tr>
                <td class="text-center"><?= $noProd++ ?></td>
                <td class="text-center"><?= htmlspecialchars($tp['kode_produk'] ?: '-') ?></td>
                <td><?= htmlspecialchars($tp['nama_produk']) ?></td>
                <td class="text-center"><?= ucfirst(htmlspecialchars($tp['kategori'])) ?></td>
                <td class="text-right"><?= number_format((float)$tp['harga'], 0, ',', '.') ?></td>
                <td class="text-center bold"><?= (int)$tp['terjual'] ?></td>
                <td class="text-right bold"><?= number_format((float)$tp['pendapatan'], 0, ',', '.') ?></td>
            </tr>
            <?php
                endwhile;
            else:
            ?>
            <tr><td colspan="7" class="text-center">Belum ada produk terjual pada periode ini.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>

    <h3>3. RINCIAN TRANSAKSI PENJUALAN LENGKAP</h3>
    <table>
        <thead>
            <tr>
                <th class="th-green">No</th>
                <th class="th-green">ID Transaksi</th>
                <th class="th-green">Waktu Pemesanan</th>
                <th class="th-green">Waktu Selesai</th>
                <th class="th-green">Nama Pelanggan</th>
                <th class="th-green">Petugas FO</th>
                <th class="th-green">Metode Bayar</th>
                <th class="th-green">Rincian Produk Dibeli</th>
                <th class="th-green">Total Qty</th>
                <th class="th-green">Total Pembayaran (Rp)</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $noTrx = 1;
            if ($allTrx && mysqli_num_rows($allTrx) > 0):
                while ($tr = mysqli_fetch_assoc($allTrx)):
            ?>
            <tr>
                <td class="text-center"><?= $noTrx++ ?></td>
                <td class="text-center bold">#TRX-<?= str_pad((string)$tr['id_transaksi'], 4, '0', STR_PAD_LEFT) ?></td>
                <td class="text-center"><?= $tr['tanggal_masuk'] ? date('d-m-Y H:i', strtotime($tr['tanggal_masuk'])) : '-' ?></td>
                <td class="text-center"><?= $tr['tanggal_selesai'] ? date('d-m-Y H:i', strtotime($tr['tanggal_selesai'])) : '-' ?></td>
                <td><?= htmlspecialchars($tr['nama_customer']) ?></td>
                <td><?= htmlspecialchars($tr['nama_fo'] ?: 'FO TOKO 33') ?></td>
                <td class="text-center"><?= htmlspecialchars($tr['metode_pembayaran'] ?: 'Cash') ?></td>
                <td><?= htmlspecialchars($tr['daftar_item'] ?: '-') ?></td>
                <td class="text-center"><?= (int)$tr['total_item'] ?></td>
                <td class="text-right bold"><?= number_format((float)$tr['total_pembayaran'], 0, ',', '.') ?></td>
            </tr>
            <?php
                endwhile;
            else:
            ?>
            <tr><td colspan="10" class="text-center">Belum ada transaksi selesai pada periode ini.</td></tr>
            <?php endif; ?>
            <tr>
                <td colspan="8" class="th-soft text-right">GRAND TOTAL OMZET PENJUALAN</td>
                <td class="th-soft text-center"><?= $totalQty ?></td>
                <td class="th-soft text-right">Rp <?= number_format($omzet, 0, ',', '.') ?></td>
            </tr>
        </tbody>
    </table>
</body>
</html>
