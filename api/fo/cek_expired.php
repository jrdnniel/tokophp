<?php
require_once __DIR__ . "/../../config/connection.php";

$q = mysqli_query($connect, "
    SELECT id_transaksi FROM transaksi
    WHERE status = 'menunggu_pembayaran'
    AND expired_at IS NOT NULL
    AND expired_at <= NOW()
");

if ($q) {
    while ($t = mysqli_fetch_assoc($q)) {
        $id = (int) $t['id_transaksi'];
        mysqli_query($connect, "
            UPDATE transaksi
            SET status = 'expired', tanggal_ditolak = NOW()
            WHERE id_transaksi = $id AND status = 'menunggu_pembayaran'
        ");
        if (mysqli_affected_rows($connect) !== 1) {
            continue;
        }
        mysqli_query($connect, "
            UPDATE produk p
            JOIN detail_transaksi d ON d.id_produk = p.id_produk
            SET p.stok = p.stok + d.jumlah
            WHERE d.id_transaksi = $id
        ");
    }
}
?>
