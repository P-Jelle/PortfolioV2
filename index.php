<?php
session_start();
require 'config.php';

$logged_in = isset($_SESSION['user_id']);
$user_id = $logged_in ? $_SESSION['user_id'] : 1;

$stmt = $pdo->prepare("SELECT s.*, m.model, b.brand FROM sneaker s
    JOIN model m ON s.model_id = m.model_id
    JOIN brand b ON m.brand_id = b.brand_id
    WHERE s.user_id = ?
    ORDER BY s.purchase_date DESC");
$stmt->execute([$user_id]);
$sneakers = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sneaker Collectie</title>
    <link rel="icon" type="image/x-icon" href="img/favicon.ico" />
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-[#0d1b2a] min-h-screen text-[#e0e1dd]">
    <div class="max-w-6xl mx-auto py-8 px-4">
        <div class="flex flex-col sm:flex-row justify-between items-center mb-4 gap-4">
            <h1 class="text-3xl font-bold">Sneaker Collectie</h1>
            <div class="flex flex-col w-full sm:w-auto sm:flex-row gap-2 text-center">
                <a href="stats.php" class="bg-[#415a77] hover:bg-[#778da9] text-white py-2 px-4 rounded shadow-md">
                        Statistieken
                    </a>
                <?php if ($logged_in): ?>
                    <a href="sneakers/add.php" class="bg-[#415a77] hover:bg-[#778da9] text-white py-2 px-4 rounded shadow-md">
                        Sneaker Toevoegen
                    </a>
                <?php endif; ?>
                <?php if ($logged_in): ?>
                    <a href="logout.php" class="bg-[#415a77] hover:bg-[#778da9] text-white py-2 px-4 rounded">Uitloggen</a>
                <?php else: ?>
                    <a href="login.php" class="bg-[#415a77] hover:bg-[#778da9] text-white py-2 px-4 rounded">Inloggen</a>
                <?php endif; ?>
            </div>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
            <?php foreach ($sneakers as $sneaker): ?>
                <div class="bg-white text-black p-4 rounded shadow hover:shadow-lg transition-shadow duration-300">
                    <img src="<?= htmlspecialchars($sneaker['image']) ?>" alt="<?= htmlspecialchars($sneaker['model']) ?>" class="w-full h-48 object-contain mb-2">
                    <h3 class="text-lg font-semibold"><?= htmlspecialchars($sneaker['brand']) ?> - <?= htmlspecialchars($sneaker['model']) ?></h3>
                    <p class="text-sm text-gray-700">Maat: <?= $sneaker['size'] ?> | Prijs: €<?= number_format($sneaker['price'], 2, ',', '.') ?></p>
                    <p class="text-sm text-gray-600">
                        Release: <?= date('d-m-Y', strtotime($sneaker['release_date'])) ?> |
                        Gekocht: <?= date('d-m-Y', strtotime($sneaker['purchase_date'])) ?>
                    </p>

                    <?php if ($logged_in): ?>
                        <div class="mt-4 flex justify-between text-sm">
                            <a href="sneakers/edit.php?id=<?= $sneaker['sneaker_id'] ?>" class="text-blue-600 hover:underline">Edit</a>
                            <a href="sneakers/delete.php?id=<?= $sneaker['sneaker_id'] ?>" class="text-red-500 hover:underline" onclick="return confirm('Are you sure you want to delete this sneaker?')">Delete</a>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</body>
</html>
