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

<body class="bg-gray-50">
    <header class="bg-gradient-to-r from-purple-600 to-indigo-700 text-white p-6 shadow-lg">
        <div class="container mx-auto">
            <h1 class="text-3xl font-bold">Teacher Dashboard</h1>
        </div>
    </header>

    <div class="container mx-auto p-6">
        <nav class="flex space-x-6 mb-8">
            <a href="add_course.php" class="text-purple-600 hover:text-purple-800 transition duration-300">Add
                Course</a>
            <a href="my_courses.php" class="text-purple-600 hover:text-purple-800 transition duration-300">My
                Courses</a>
            <a href="stats.php" class="text-purple-600 hover:text-purple-800 transition duration-300">Course Stats</a>
            <a href="../public/index.php" class="text-purple-600 hover:text-purple-800 transition duration-300">Home</a>
            <a href="../auth/logout.php" class="text-red-600 hover:text-red-800 transition duration-300">Logout</a>
        </nav>

        <div class="bg-white p-6 rounded-lg shadow-md">
            <h2 class="text-2xl font-bold text-gray-800 mb-4">Welcome, Teacher!</h2>
            <p class="text-gray-600">Here, you can manage your courses, track student progress, and add new content.</p>
        </div>
    </div>
</body>

</html>