<?php
session_start();
require '../config.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: ../login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $brand = trim($_POST['brand']);
    $model = trim($_POST['model']);
    $size = $_POST['size'];
    $release_date = $_POST['release_date'];
    $purchase_date = $_POST['purchase_date'];
    $price = $_POST['price'];
    $image = $_POST['image'];

    $stmt = $pdo->prepare("SELECT brand_id FROM brand WHERE brand = ?");
    $stmt->execute([$brand]);
    $brandRow = $stmt->fetch();

    if ($brandRow) {
        $brand_id = $brandRow['brand_id'];
    } else {
        $stmt = $pdo->prepare("INSERT INTO brand (brand) VALUES (?)");
        $stmt->execute([$brand]);
        $brand_id = $pdo->lastInsertId();
    }

    $stmt = $pdo->prepare("SELECT model_id FROM model WHERE model = ? AND brand_id = ?");
    $stmt->execute([$model, $brand_id]);
    $modelRow = $stmt->fetch();

    if ($modelRow) {
        $model_id = $modelRow['model_id'];
    } else {
        $stmt = $pdo->prepare("INSERT INTO model (model, brand_id) VALUES (?, ?)");
        $stmt->execute([$model, $brand_id]);
        $model_id = $pdo->lastInsertId();
    }

    $stmt = $pdo->prepare("INSERT INTO sneaker (user_id, model_id, size, release_date, purchase_date, price, image) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([$_SESSION['user_id'], $model_id, $size, $release_date, $purchase_date, $price, $image]);

    header('Location: ../index.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Sneaker</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-[#0d1b2a] py-8 px-4 text-[#e0e1dd]">
    <div class="max-w-xl mx-auto bg-white text-black p-6 rounded shadow">
        <h2 class="text-2xl font-bold mb-6">Add Sneaker</h2>
        <form method="post">
            <label class="block mb-2">Merk</label>
            <input type="text" name="brand" class="w-full p-2 border border-[#415a77] rounded mb-4" required>

            <label class="block mb-2">Model</label>
            <input type="text" name="model" class="w-full p-2 border border-[#415a77] rounded mb-4" required>

            <label class="block mb-2">Maat</label>
            <input type="number" step="0.1" name="size" class="w-full p-2 border border-[#415a77] rounded mb-4" required>

            <label class="block mb-2">Release Datum</label>
            <input type="date" name="release_date" class="w-full p-2 border border-[#415a77] rounded mb-4" required>

            <label class="block mb-2">Aankoop Datum</label>
            <input type="date" name="purchase_date" class="w-full p-2 border border-[#415a77] rounded mb-4" required>

            <label class="block mb-2">Prijs</label>
            <input type="number" step="0.01" name="price" class="w-full p-2 border border-[#415a77] rounded mb-4" required>

            <label class="block mb-2">Foto URL</label>
            <input type="url" name="image" class="w-full p-2 border border-[#415a77] rounded mb-6" required>

            <button type="submit" class="bg-[#415a77] hover:bg-[#778da9] text-white py-2 px-4 rounded">Add Sneaker</button>
            <a href="../index.php" class="ml-4 text-[#415a77] hover:underline">Cancel</a>
        </form>
    </div>
</body>
</html>