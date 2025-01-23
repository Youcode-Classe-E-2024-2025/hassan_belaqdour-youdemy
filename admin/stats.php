<?php
session_start();
require_once '../classes/Admin.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: ../auth/login.php");
    exit;
}

$admin = new Admin($_SESSION['user_id']);
$stats = $admin->getGlobalStats();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Global Stats</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body>
    <header class="bg-gray-800 text-white p-4">
        <h1>Global Stats</h1>
    </header>
    <div class="p-4">
        <a class="text-blue-600" href="dashboard.php">Back to Dashboard</a>
        <hr class="my-4">
        <div class="mb-4">
            <strong>Total Courses:</strong> <?php echo $stats['total_courses']; ?>
        </div>
        <div class="mb-4">
            <strong>Course with Most Students:</strong>
            <?php
            if ($stats['most_popular_course']) {
                echo $stats['most_popular_course']['title'] . " (" . $stats['most_popular_course']['total_students'] . " students)";
            } else {
                echo "No enrollments yet.";
            }
            ?>
        </div>
        <div>
            <strong>Top 3 Teachers by Enrollment:</strong>
            <ul>
                <?php foreach ($stats['top_teachers'] as $tt): ?>
                    <li><?php echo $tt['username'] . " (" . $tt['total_enrollments'] . " enrollments)"; ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>
</body>

</html>