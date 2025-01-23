<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: ../auth/login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body>
    <header class="bg-gray-800 text-white p-4">
        <h1>Admin Dashboard</h1>
    </header>
    <div class="p-4">
        <nav>
            <a href="validate_teachers.php" class="text-blue-600">Validate Teachers</a> |
            <a href="manage_users.php" class="text-blue-600">Manage Users</a> |
            <a href="manage_courses.php" class="text-blue-600">Manage Courses</a> |
            <a href="manage_categories.php" class="text-blue-600">Manage Categories</a> |
            <a href="manage_tags.php" class="text-blue-600">Manage Tags</a> |
            <a href="stats.php" class="text-blue-600">Global Stats</a> |
            <a href="../auth/logout.php" class="text-blue-600">Logout</a>
        </nav>
    </div>
</body>

</html>