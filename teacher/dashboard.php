<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'teacher') {
    header("Location: ../auth/login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Teacher Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body>
    <header class="bg-gray-800 text-white p-4">
        <h1>Teacher Dashboard</h1>
    </header>
    <div class="p-4">
        <nav>
            <a href="add_course.php" class="text-blue-600">Add Course</a> |
            <a href="my_courses.php" class="text-blue-600">My Courses</a> |
            <a href="stats.php" class="text-blue-600">Course Stats</a> |
            <a href="../public/index.php" class="text-blue-600">Home</a> |
            <a href="../auth/logout.php" class="text-blue-600">Logout</a>
        </nav>
    </div>
</body>

</html>