<?php
session_start();
require 'config.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    $stmt = $pdo->prepare("SELECT * FROM user WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch();

    if ($user && hash('sha256', $password) === $user['password']) {
        $_SESSION['user_id'] = $user['user_id'];
        $_SESSION['username'] = $user['username'];
        header('Location: index.php');
        exit;
    } else {
        $error = 'Invalid username or password';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="icon" type="image/x-icon" href="img/favicon.ico" />
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-[#0d1b2a] flex items-center justify-center h-screen">
    <div class="bg-[#1b263b] p-8 rounded shadow-md w-full max-w-sm text-[#e0e1dd]">
        <?php if ($error): ?>
            <div class="mb-4 text-red-500"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>
        <form method="post" class="space-y-4">
            <div>
                <label class="block mb-2">Gebruikersnaam</label>
                <input type="text" name="username" class="w-full p-2 border border-[#415a77] rounded bg-[#e0e1dd] text-black" required>
            </div>
            <div>
                <label class="block mb-2">Wachtwoord</label>
                <input type="password" name="password" class="w-full p-2 border border-[#415a77] rounded bg-[#e0e1dd] text-black" required>
            </div>
            
            <button type="submit" class="w-full bg-[#415a77] hover:bg-[#778da9] text-white font-bold py-2 px-4 rounded">
                Inloggen
            </button>
            <a href="index.php" class="block w-full bg-[#415a77] hover:bg-[#778da9] text-white font-bold py-2 px-4 rounded text-center">
                Terug
            </a>
        </form>
    </div>
</body>
</html>
