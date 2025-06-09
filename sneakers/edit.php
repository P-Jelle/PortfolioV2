<?php
session_start();
require '../config.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: ../login.php');
    exit;
}

$id = $_GET['id'] ?? null;
if (!$id) {
    header('Location: ../index.php');
    exit;
}

// Get sneaker
$stmt = $pdo->prepare("SELECT s.*, m.model, b.brand FROM sneaker s
    JOIN model m ON s.model_id = m.model_id
    JOIN brand b ON m.brand_id = b.brand_id
    WHERE s.sneaker_id = ? AND s.user_id = ?");
$stmt->execute([$id, $_SESSION['user_id']]);
$sneaker = $stmt->fetch();

if (!$sneaker) {
    header('Location: ../index.php');
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

    // Insert or get brand_id
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

    // Insert or get model_id
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

    // Update sneaker
    $stmt = $pdo->prepare("UPDATE sneaker SET model_id = ?, size = ?, release_date = ?, purchase_date = ?, price = ?, image = ? WHERE sneaker_id = ? AND user_id = ?");
    $stmt->execute([$model_id, $size, $release_date, $purchase_date, $price, $image, $id, $_SESSION['user_id']]);

    header('Location: ../index.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Sneaker</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-[#0d1b2a] py-8 px-4 text-[#e0e1dd]">
    <div class="max-w-xl mx-auto bg-white text-black p-6 rounded shadow">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold">Edit Sneaker</h2>
            <a href="add.php" class="bg-[#415a77] hover:bg-[#778da9] text-white py-2 px-4 rounded text-sm">+ Add Sneaker</a>
        </div>
        <form method="post">
            <label class="block mb-2">Merk</label>
            <input type="text" name="brand" value="<?= htmlspecialchars($sneaker['brand']) ?>" class="w-full p-2 border border-[#415a77] rounded mb-4" required>

            <label class="block mb-2">Model</label>
            <input type="text" name="model" value="<?= htmlspecialchars($sneaker['model']) ?>" class="w-full p-2 border border-[#415a77] rounded mb-4" required>

            <label class="block mb-2">Maat</label>
            <input type="number" step="0.1" name="size" value="<?= $sneaker['size'] ?>" class="w-full p-2 border border-[#415a77] rounded mb-4" required>

            <label class="block mb-2">Release Datum</label>
            <input type="date" name="release_date" value="<?= $sneaker['release_date'] ?>" class="w-full p-2 border border-[#415a77] rounded mb-4" required>

            <label class="block mb-2">Aankoop Datum</label>
            <input type="date" name="purchase_date" value="<?= $sneaker['purchase_date'] ?>" class="w-full p-2 border border-[#415a77] rounded mb-4" required>

            <label class="block mb-2">Prijs</label>
            <input type="number" step="0.01" name="price" value="<?= $sneaker['price'] ?>" class="w-full p-2 border border-[#415a77] rounded mb-4" required>

            <label class="block mb-2">Foto URL</label>
            <input type="url" name="image" value="<?= htmlspecialchars($sneaker['image']) ?>" class="w-full p-2 border border-[#415a77] rounded mb-6" required>

            <button type="submit" class="bg-[#415a77] hover:bg-[#778da9] text-white py-2 px-4 rounded">Update</button>
            <a href="../index.php" class="ml-4 text-[#415a77] hover:underline">Cancel</a>
        </form>
    </div>
</body>
</html>