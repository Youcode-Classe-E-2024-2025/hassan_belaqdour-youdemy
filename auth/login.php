<?php
session_start();
require_once '../classes/User.php';
require_once '../classes/Teacher.php';
require_once '../classes/Student.php';
require_once '../classes/Admin.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    $user = new User();
    if ($user->login($email, $password)) {
        $_SESSION['user_id'] = $user->getId();
        $_SESSION['user_role'] = $user->getRole();
        $_SESSION['is_approved'] = $user->getIsApproved();
        $_SESSION['status'] = $user->getStatus();

        if ($user->getRole() === 'admin') {
            header("Location: ../admin/dashboard.php");
            exit;
        }
        if ($user->getRole() === 'teacher') {
            if (!$user->getIsApproved()) {
                $_SESSION['message'] = "Your teacher account is not approved yet.";
                header("Location: login.php");
                exit;
            }
            header("Location: ../teacher/dashboard.php");
            exit;
        }
        header("Location: ../student/dashboard.php");
        exit;

    } else {
        $error = "Invalid email or password.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Login - Youdemy</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 flex items-center justify-center h-screen">
    <div class="bg-white p-6 rounded shadow-md w-1/3">
        <div class="mb-4">
            <a href="../public/index.php" class="text-blue-600">Back to Home</a>
        </div>
        <h1 class="text-2xl mb-4">Login</h1>
        <?php if (!empty($error)): ?>
            <p class="text-red-500"><?php echo $error; ?></p>
        <?php endif; ?>
        <?php if (!empty($_SESSION['message'])): ?>
            <p class="text-green-500"><?php echo $_SESSION['message'];
            unset($_SESSION['message']); ?></p>
        <?php endif; ?>

        <form action="" method="POST">
            <div class="mb-4">
                <label class="block">Email</label>
                <input type="text" name="email" required class="border p-2 w-full">
            </div>
            <div class="mb-4">
                <label class="block">Password</label>
                <input type="password" name="password" required class="border p-2 w-full">
            </div>
            <button type="submit" class="bg-blue-500 text-white px-4 py-2">Login</button>
        </form>
        <p class="mt-4">Don't have an account? <a href="signup.php" class="text-blue-600">Sign up</a>.</p>
    </div>
</body>

</html>