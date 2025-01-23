<?php
session_start();
require_once '../classes/Student.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'student') {
    header("Location: ../auth/login.php");
    exit;
}

$student = new Student($_SESSION['user_id']);
$myCourses = $student->getMyCourses();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>My Courses</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body>
    <header class="bg-gray-800 text-white p-4">
        <h1>My Courses</h1>
    </header>
    <div class="p-4">
        <a class="text-blue-600" href="dashboard.php">Back to Dashboard</a>
        <hr class="my-4">
        <?php foreach ($myCourses as $course): ?>
            <div class="bg-white p-4 mb-2 border">
                <h2 class="font-bold"><?php echo htmlspecialchars($course['title']); ?></h2>
                <p><?php echo htmlspecialchars($course['description']); ?></p>
            </div>
        <?php endforeach; ?>
    </div>
</body>

</html>