<?php
session_start();
require_once '../classes/User.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $role = trim($_POST['role']);

    $user = new User();
    $newUserId = $user->create($username, $email, $password, $role);

    if ($newUserId) {
        $_SESSION['message'] = "Account created successfully. You can now login.";
        header("Location: login.php");
        exit;
    } else {
        $error = "Error: Could not create account.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Sign Up - Youdemy</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 flex items-center justify-center h-screen">
    <div class="bg-white p-6 rounded shadow-md w-1/3">
        <div class="mb-4">
            <a href="../public/index.php" class="text-blue-600">Back to Home</a>
        </div>
        <h1 class="text-2xl mb-4">Sign Up</h1>
        <?php if (!empty($error)): ?>
            <p class="text-red-500"><?php echo $error; ?></p>
        <?php endif; ?>

        <form action="" method="POST">
            <div class="mb-4">
                <label class="block">Username</label>
                <input type="text" name="username" required class="border p-2 w-full">
            </div>
            <div class="mb-4">
                <label class="block">Email</label>
                <input type="email" name="email" required class="border p-2 w-full">
            </div>
            <div class="mb-4">
                <label class="block">Password</label>
                <input type="password" name="password" required class="border p-2 w-full">
            </div>
            <div class="mb-4">
                <label class="block">Role</label>
                <select name="role" class="border p-2 w-full">
                    <option value="student">Student</option>
                    <option value="teacher">Teacher</option>
                </select>
            </div>
            <button type="submit" class="bg-blue-500 text-white px-4 py-2">Sign Up</button>
        </form>

        <p class="mt-4">Already have an account? <a href="login.php" class="text-blue-600">Login here</a>.</p>
    </div>
</body>

</html>