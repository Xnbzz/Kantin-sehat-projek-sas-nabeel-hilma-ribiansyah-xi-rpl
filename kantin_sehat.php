<?php
session_start();

if (!isset($_SESSION['login'])) {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if ($_POST['username'] === 'admin' && $_POST['password'] === '123') {
            $_SESSION['login'] = true;
            header("Location: ".$_SERVER['PHP_SELF']);
            exit;
        } else {
            $login_error = "Username atau Password salah!";
        }
    }
}
if (!isset($_SESSION['keranjang'])) $_SESSION['keranjang'] = [];
if (!isset($_SESSION['riwayat'])) $_SESSION['riwayat'] = [];

$produk = [
    ["Aqua", 4000],
    ["Milo UHT", 6000],
    ["Sari Roti Tawar Kupas", 12000],
    ["Beng-Beng Wafer", 3500],
    ["Pop Mie Mini", 7000]
];

if (isset($_GET['add'])) {
    $id = (int)$_GET['add'];
    $_SESSION['keranjang'][] = $produk[$id];
    $_SESSION['notif'] = "Produk berhasil ditambahkan!";
}

if (isset($_GET['hapus'])) {
    array_pop($_SESSION['keranjang']);
    $_SESSION['notif'] = "Produk terakhir dihapus!";
}

if (isset($_GET['bayar'])) {
    if (!empty($_SESSION['keranjang'])) {
        $_SESSION['riwayat'][] = $_SESSION['keranjang'];
        $_SESSION['keranjang'] = [];
        $_SESSION['notif'] = "Pembayaran berhasil!";
    }
}

if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: ".$_SERVER['PHP_SELF']);
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Kantin Sehat</title>
<style>
    body {
        margin: 0; font-family: Arial, sans-serif;
        background: #0c0f0c; color: white;
    }
    .sidebar {
        width: 200px; background: #0f3a0f;
        height: 100vh; position: fixed;
        padding: 20px 10px; box-shadow: 0 0 20px #0f0;
    }
    .sidebar h2 {
        color: #24ff2a; text-align: center;
        font-size: 25px; font-weight: bold;
        text-transform: uppercase;
        text-shadow: 0 0 10px #24ff2a;
    }
    .menu {
        background: #092d09; margin: 10px 0;
        padding: 12px; text-align: center;
        border-radius: 10px; cursor: pointer;
        transition: 0.3s; font-weight: bold;
    }
    .menu:hover {
        background: #00ff00; color: black;
        transform: scale(1.05);
        box-shadow: 0 0 15px #24ff2a;
    }
    .content {
        margin-left: 220px; padding: 20px;
    }
    .produk {
        display: inline-block; width: 220px;
        background: black; color: #00ff9d;
        margin: 10px; padding: 15px;
        text-align: center; border-radius: 10px;
        cursor: pointer; transition: 0.3s;
    }
    .produk:hover {
        background: #144d14;
        transform: translateY(-5px);
        box-shadow: 0 0 10px #00ff9d;
    }
    .keranjang, .riwayat, .struk {
        background: #062006; padding: 20px;
        border-radius: 10px; width: 350px;
    }
    .btn {
        background: #00ff5e; border: none;
        padding: 10px 15px; cursor: pointer;
        margin: 5px; border-radius: 6px;
        font-weight: bold; transition: 0.3s;
    }
    .btn:hover { background: #00d954; color: black; }

    .notif {
        position: fixed; top: 20px; right: -200px;
        background: #00ff6e; color: black;
        padding: 10px 20px; border-radius: 8px;
        font-weight: bold; box-shadow: 0 0 10px #00ff6e;
        animation: slideNotif 3s forwards;
    }
    @keyframes slideNotif {
        0% { right: -200px; opacity: 0; }
        10% { right: 20px; opacity: 1; }
        90% { right: 20px; opacity: 1; }
        100% { right: -200px; opacity: 0; }
    }
</style>
</head>
<body>

<?php if (!isset($_SESSION['login'])) : ?>
<div style="display:flex; justify-content:center; align-items:center; height:100vh;">
    <form method="POST" style="background:#062006; padding:30px; border-radius:10px; width:280px;">
        <h3 style="text-align:center;color:#00ff6e">Login Kantin</h3>
        <input type="text" name="username" placeholder="Username"
        style="width:100%;padding:10px;margin:10px 0;">
        <input type="password" name="password" placeholder="Password"
        style="width:100%;padding:10px;margin:10px 0;">
        <button class="btn" style="width:100%;">Masuk</button>
        <?php if (isset($login_error)) echo "<p style='color:red'>$login_error</p>"; ?>
    </form>
</div>
<?php exit; endif; ?>

<div class="sidebar">
    <h2>Kantin Sehat</h2>
    <a href="?"><div class="menu">Produk</div></a>
    <a href="?lihatKeranjang"><div class="menu">Transaksi</div></a>
    <a href="?lihatRiwayat"><div class="menu">Riwayat</div></a>
    <a href="?lihatStruk"><div class="menu">Struk</div></a>
    <a href="?logout"><div class="menu" style="background:#1a0000;color:red;">Logout</div></a>
</div>

<div class="content">
<?php if (isset($_SESSION['notif'])) : ?>
<div class="notif"><?php echo $_SESSION['notif']; unset($_SESSION['notif']); ?></div>
<?php endif; ?>

<?php if (isset($_GET['lihatKeranjang'])): ?>
    <div class="keranjang">
        <h3>Keranjang</h3>
        <?php $total=0; foreach($_SESSION['keranjang'] as $item){ echo $item[0]." - Rp ".number_format($item[1])."<br>"; $total += $item[1]; } ?>
        <hr>
        Total: Rp <?php echo number_format($total); ?><br><br>
        <a href="?bayar"><button class="btn">Bayar</button></a>
        <a href="?hapus"><button class="btn" style="background:red;">Hapus Produk Terakhir</button></a>
    </div>

<?php elseif (isset($_GET['lihatRiwayat'])): ?>
    <div class="riwayat">
        <h3>Riwayat Pembelian</h3>
        <?php foreach($_SESSION['riwayat'] as $r){ echo "- <span style='color:#00ff9d'>".count($r)." produk dibeli</span><br>"; } ?>
    </div>

<?php elseif (isset($_GET['lihatStruk'])): ?>
    <div class="struk" id="strukArea">
        <h3>Struk Terbaru</h3>
        <?php 
        if (!empty($_SESSION['riwayat'])) {
            $struk = end($_SESSION['riwayat']);
            $total = 0;
            foreach ($struk as $s) { echo $s[0]." - Rp ".number_format($s[1])."<br>"; $total += $s[1]; }
            echo "<hr>Total: Rp ".number_format($total);
        } else echo "Belum ada transaksi";
        ?>
        <br><br>
        <button class="btn" onclick="window.print()">Print Struk</button>
    </div>

<?php else: ?>
    <?php foreach ($produk as $i => $p): ?>
        <a href="?add=<?php echo $i; ?>">
        <div class="produk"><?php echo $p[0]; ?><br>Rp <?php echo number_format($p[1]); ?></div></a>
    <?php endforeach; ?>
<?php endif; ?>
</div>

</body>
</html>
