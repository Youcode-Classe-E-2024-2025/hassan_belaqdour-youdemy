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

<body class="bg-gradient-to-r from-purple-500 to-indigo-600 flex items-center justify-center h-screen">
    <div
        class="bg-white p-8 rounded-2xl shadow-2xl w-full max-w-md transform transition-all duration-500 hover:scale-105">
        <div class="mb-6">
            <a href="../public/index.php"
                class="text-indigo-600 hover:text-indigo-800 transition duration-300 flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd"
                        d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z"
                        clip-rule="evenodd" />
                </svg>
                Back to Home
            </a>
        </div>

        <h1 class="text-3xl font-bold text-gray-800 mb-6 text-center">Welcome Back!</h1>

        <?php if (!empty($error)): ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-6 animate-fade-in">
                <?php echo $error; ?>
            </div>
        <?php endif; ?>
        <?php if (!empty($_SESSION['message'])): ?>
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-6 animate-fade-in">
                <?php echo $_SESSION['message'];
                unset($_SESSION['message']); ?>
            </div>
        <?php endif; ?>

        <form action="" method="POST">
            <div class="mb-6">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="email">Email</label>
                <input type="text" name="email" id="email" required
                    class="w-full px-4 py-3 border rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition duration-300"
                    placeholder="Enter your email">
            </div>

            <div class="mb-6">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="password">Password</label>
                <input type="password" name="password" id="password" required
                    class="w-full px-4 py-3 border rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition duration-300"
                    placeholder="Enter your password">
            </div>

            <button type="submit"
                class="w-full bg-gradient-to-r from-purple-500 to-indigo-600 text-white py-3 px-4 rounded-lg hover:from-purple-600 hover:to-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition-all duration-300">
                Login
            </button>
        </form>
        <p class="mt-6 text-center text-gray-600">
            Don't have an account?
            <a href="signup.php"
                class="text-indigo-600 hover:text-indigo-800 font-semibold transition duration-300">Sign up</a>.
        </p>
    </div>

    <style>
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-fade-in {
            animation: fadeIn 0.5s ease-in-out;
        }
    </style>
</body>

</html>