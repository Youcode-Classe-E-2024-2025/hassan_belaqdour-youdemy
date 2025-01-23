<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'student') {
    header("Location: ../auth/login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Student Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body>
    <header class="bg-gray-800 text-white p-4">
        <h1>Student Dashboard</h1>
    </header>
    <div class="p-4">
        <nav>
            <a class="text-blue-600" href="my_courses.php">My Courses</a> |
            <a class="text-blue-600" href="../public/index.php">View Catalog</a> |
            <a href="../public/index.php" class="text-blue-600">Home</a> |
            <a class="text-blue-600" href="../auth/logout.php">Logout</a>
        </nav>
    </div>
</body>

</html>