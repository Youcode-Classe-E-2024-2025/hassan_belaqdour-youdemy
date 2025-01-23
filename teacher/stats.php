<?php
session_start();
require_once '../classes/Teacher.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'teacher') {
    header("Location: ../auth/login.php");
    exit;
}

$teacher = new Teacher($_SESSION['user_id']);
$stats = $teacher->getCourseStats();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Teacher Stats</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body>
    <header class="bg-gray-800 text-white p-4">
        <h1>Teacher Stats</h1>
    </header>
    <div class="p-4">
        <a href="dashboard.php" class="text-blue-600">Back to Dashboard</a>
        <hr class="my-4">
        <table class="border-collapse w-full">
            <thead>
                <tr class="bg-gray-200">
                    <th class="border p-2">Course Title</th>
                    <th class="border p-2">Enrolled Students</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($stats as $row): ?>
                    <tr>
                        <td class="border p-2"><?php echo htmlspecialchars($row['title']); ?></td>
                        <td class="border p-2"><?php echo $row['total_students']; ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>

</html>