<?php
session_start();
require 'config.php';

$logged_in = isset($_SESSION['user_id']);
$user_id = $logged_in ? $_SESSION['user_id'] : 1;

$total = $pdo->prepare("SELECT COUNT(*) FROM sneaker WHERE user_id = ?");
$total->execute([$user_id]);
$total_count = $total->fetchColumn();

$avg = $pdo->prepare("SELECT AVG(price) FROM sneaker WHERE user_id = ?");
$avg->execute([$user_id]);
$avg_price = $avg->fetchColumn();

$sum = $pdo->prepare("SELECT SUM(price) FROM sneaker WHERE user_id = ?");
$sum->execute([$user_id]);
$total_value = $sum->fetchColumn();

$per_brand = $pdo->prepare("SELECT b.brand, COUNT(*) as count FROM sneaker s JOIN model m ON s.model_id = m.model_id JOIN brand b ON m.brand_id = b.brand_id WHERE s.user_id = ? GROUP BY b.brand ORDER BY count DESC");
$per_brand->execute([$user_id]);
$brand_stats = $per_brand->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Statistieken</title>
    <link rel="icon" type="image/x-icon" href="img/favicon.ico" />
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-[#0d1b2a] min-h-screen text-[#e0e1dd]">
    <div class="max-w-4xl mx-auto py-8 px-4">
        <div class="flex justify-between items-center mb-8">
            <h1 class="text-3xl font-bold">Collectie Statistieken</h1>
            <a href="index.php" class="bg-[#415a77] hover:bg-[#778da9] text-white py-2 px-4 rounded">Terug naar collectie</a>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6 mb-8">
            <div class="bg-white p-6 rounded shadow text-black">
                <h2 class="text-lg font-semibold">Totale Sneakers</h2>
                <p class="text-2xl mt-2 font-bold"><?= $total_count ?></p>
            </div>
            <div class="bg-white p-6 rounded shadow text-black">
                <h2 class="text-lg font-semibold">Gemiddelde prijs</h2>
                <p class="text-2xl mt-2 font-bold">€<?= number_format($avg_price, 2, ',', '.') ?></p>
            </div>
            <div class="bg-white p-6 rounded shadow text-black">
                <h2 class="text-lg font-semibold">Totale waarde</h2>
                <p class="text-2xl mt-2 font-bold">€<?= number_format($total_value, 2, ',', '.') ?></p>
            </div>
        </div>

        <div class="bg-white p-6 rounded shadow text-black">
            <h2 class="text-lg font-semibold mb-4">Sneakers per merk</h2>
            <ul class="space-y-2">
                <?php foreach ($brand_stats as $brand): ?>
                    <li class="flex justify-between border-b border-gray-300 pb-2">
                        <span><?= htmlspecialchars($brand['brand']) ?></span>
                        <span class="font-semibold"><?= $brand['count'] ?></span>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>
</body>
</html>