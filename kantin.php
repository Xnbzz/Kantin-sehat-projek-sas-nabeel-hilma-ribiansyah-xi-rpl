<?php
session_start();

/* ------------------ LOGIN ---------------------- */
if (!isset($_SESSION['login'])) {
    if (isset($_POST['user'])) {
        if ($_POST['user'] == "admin" && $_POST['pass'] == "123") {
            $_SESSION['login'] = true;
        } else {
            $msg = "Username atau password salah!";
        }
    }
    ?>

<!DOCTYPE html>
<html>
<head>
<title>Kantin Sehat - Login</title>
<style>
body{
    margin:0; padding:0;
    background:white;
    font-family:Arial;
}
.login-box{
    width:340px; padding:25px;
    background:#e8f5e9;
    margin:120px auto;
    border-radius:14px;
    box-shadow:0 0 15px rgba(0,0,0,.15);
    animation:fade .6s ease;
}
h2{
    text-align:center;
    color:#2e7d32;
}
input{
    width:100%; padding:12px; margin-top:10px;
    border-radius:8px; border:1px solid #81c784;
}
button{
    width:100%; background:#2e7d32; color:white;
    padding:12px; border:none; border-radius:8px;
    margin-top:15px; font-size:16px;
    transition:.2s;
}
button:hover{ background:#1b5e20; transform:scale(1.03); }

@keyframes fade { from{opacity:0;} to{opacity:1;} }

</style>
</head>
<body>
<div class="login-box">
    <h2>Login Kantin Sehat</h2>
    <?php if (isset($msg)) echo "<p style='color:red;text-align:center;'>$msg</p>"; ?>
    <form method="post">
        <input type="text" name="user" placeholder="Username">
        <input type="password" name="pass" placeholder="Password">
        <button type="submit">Masuk</button>
    </form>
</div>
</body>
</html>

<?php
exit;
}
/* ------------------ PRODUK ---------------------- */

$produk = [
    ["Aqua", 4000, "https://solvent-production.s3.amazonaws.com/media/images/products/2021/03/IMG20210201153205.jpg"],
    ["Milo UHT", 6000, "https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQs9iSGqAOcVTf4n0y1aahd1DnLqywY3YZt4w&s"],
    ["Sari Roti Tawar Kupas", 12000, "https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQGPVTfie-gd6zeEJRu-hdcXa2xUdrLU45MPg&s"],
    ["Beng-Beng Wafer", 3500, "https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTD-yQCqvYII-fCeWJXk2TX9GrWcvx7QOq4nA&s"],
    ["Pop Mie Mini", 7000, "https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQ5MAn_C0ukh2SXRNiHFiGkfCwNIDNw1U0jHw&s"]
];


if (!isset($_SESSION['keranjang'])) $_SESSION['keranjang'] = [];
if (!isset($_SESSION['riwayat'])) $_SESSION['riwayat'] = [];

if (isset($_POST['tambah'])) {
    $id = $_POST['id'];
    $_SESSION['keranjang'][] = [$produk[$id][0], $produk[$id][1]];
}

if (isset($_POST['hapus_last'])) {
    array_pop($_SESSION['keranjang']);
}

if (isset($_POST['checkout'])) {
    $total = 0;
    foreach ($_SESSION['keranjang'] as $i) $total += $i[1];

    $_SESSION['riwayat'][] = [
        "waktu" => date("d-m-Y H:i"),
        "items" => $_SESSION['keranjang'],
        "total" => $total
    ];

    $_SESSION['struk'] = $_SESSION['keranjang'];
    $_SESSION['total'] = $total;

    $_SESSION['keranjang'] = [];
    header("Location: kantin.php?struk=1");
    exit;
}

?>
<!DOCTYPE html>
<html>
<head>
<title>Kantin Sehat</title>
<style>
body{
    margin:0; padding:0;
    background:white;
    font-family:Arial;
}

/* SIDEBAR */
.sidebar{
    width:230px; height:100vh;
    position:fixed; top:0; left:0;
    background:#1b5e20;
    color:white; padding-top:25px;
    animation:slide .6s;
}
@keyframes slide{
    from{transform:translateX(-80px); opacity:0;}
    to{transform:translateX(0); opacity:1;}
}

.sidebar h1{
    text-align:center;
    margin-bottom:30px;
    font-size:26px;
    text-transform:uppercase;
    text-shadow:0 0 10px #66bb6a;
}

.menu a{
    display:block;
    padding:14px 20px;
    color:white; text-decoration:none;
    margin:6px 12px;
    background:#2e7d32;
    border-radius:10px;
    transition:.2s;
}
.menu a:hover{
    background:#43a047;
    transform:scale(1.04);
}

/* CONTENT */
.content{
    margin-left:240px;
    padding:25px;
}

/* PRODUK GRID */
.grid{
    display:grid;
    grid-template-columns:repeat(3, 1fr);
    gap:20px;
    animation:fade .6s;
}

.card{
    border:1px solid #c8e6c9;
    background:#f1f8e9;
    border-radius:14px;
    text-align:center;
    padding:15px;
    animation:pop .4s;
}
.card img{
    width:100px;
    margin-bottom:10px;
}
.card button{
    margin-top:10px;
    padding:10px 20px;
    background:#2e7d32;
    border:none; border-radius:8px;
    color:white;
    transition:.2s;
}
.card button:hover{ background:#1b5e20; transform:scale(1.06); }

@keyframes fade{from{opacity:0;}to{opacity:1;}}
@keyframes pop{0%{transform:scale(.8);opacity:0;}100%{transform:scale(1);opacity:1;}}

/* TABEL */
.table{
    border:1px solid #81c784;
    padding:15px;
    border-radius:10px;
    background:#f1f8e9;
}

/* STRUK */
.strukbox{
    width:380px; margin:auto;
    padding:20px;
    border:1px solid #2e7d32;
    border-radius:12px;
    background:white;
    animation:pop .4s;
}
</style>
</head>
<body>

<div class="sidebar">
    <h1>Kantin Sehat</h1>
    <div class="menu">
        <a href="kantin.php">Menu</a>
        <a href="kantin.php?page=keranjang">Keranjang</a>
        <a href="kantin.php?page=riwayat">Riwayat</a>
        <a href="kantin.php?page=logout">Logout</a>
    </div>
</div>

<div class="content">

<?php

if (isset($_GET['page']) && $_GET['page']=="logout") {
    session_destroy();
    header("Location: kantin.php");
}

if (isset($_GET['struk'])) {
    echo "<h2>Struk Pembelian</h2>";
    echo "<div class='strukbox'>";
    echo "<h3 style='text-align:center;'>KANTIN SEHAT</h3><hr>";

    foreach ($_SESSION['struk'] as $i) {
        echo "<div style='display:flex; justify-content:space-between;'>
                <span>{$i[0]}</span>
                <span>Rp ".number_format($i[1])."</span>
              </div>";
    }

    echo "<hr><h3>Total: Rp ".number_format($_SESSION['total'])."</h3>";
    echo "<br><button onclick='window.print()' style='padding:10px 20px;background:#2e7d32;color:white;border:none;border-radius:8px;'>Print</button>";
    echo "</div>";
    exit;
}

if (isset($_GET['page']) && $_GET['page']=="keranjang") {
    echo "<h2>Keranjang</h2>";
    echo "<div class='table'>";

    if (empty($_SESSION['keranjang'])) {
        echo "Keranjang kosong.";
    } else {
        foreach ($_SESSION['keranjang'] as $i) {
            echo "{$i[0]} — Rp ".number_format($i[1])."<br>";
        }

        echo "<br><form method='post'>
                <button name='hapus_last' style='background:#d32f2f;color:white;padding:10px;border:none;border-radius:8px;'>Hapus Terakhir</button>
              </form><br>";

        echo "<form method='post'>
                <button name='checkout' style='background:#2e7d32;color:white;padding:12px;border:none;border-radius:8px;'>Checkout</button>
              </form>";
    }

    echo "</div>";
    exit;
}

if (isset($_GET['page']) && $_GET['page']=="riwayat") {
    echo "<h2>Riwayat Transaksi</h2>";
    echo "<div class='table'>";

    if (empty($_SESSION['riwayat'])) echo "Belum ada pesanan.";
    else foreach ($_SESSION['riwayat'] as $r) {
        echo "<b>{$r['waktu']}</b><br>";
        foreach ($r['items'] as $i) {
            echo "- {$i[0]} (Rp ".number_format($i[1]).")<br>";
        }
        echo "<b>Total: Rp ".number_format($r['total'])."</b><br><br>";
    }

    echo "</div>";
    exit;
}

?>

<h2>Menu Produk</h2>
<div class="grid">
<?php foreach ($produk as $i=>$p): ?>
    <div class="card">
        <img src="<?= $p[2] ?>">
        <h3><?= $p[0] ?></h3>
        <p>Rp <?= number_format($p[1]) ?></p>

        <form method="post">
            <input type="hidden" name="id" value="<?= $i ?>">
            <button type="submit" name="tambah">Tambah</button>
        </form>
    </div>
<?php endforeach; ?>
</div>

</div>
</body>
</html>
