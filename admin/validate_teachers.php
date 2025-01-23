<?php
session_start();
require_once '../classes/Admin.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: ../auth/login.php");
    exit;
}

$admin = new Admin($_SESSION['user_id']);

if (isset($_GET['approve'])) {
    $admin->approveTeacher((int)$_GET['approve']);
    header("Location: validate_teachers.php");
    exit;
}

$pendingTeachers = $admin->getAllPendingTeachers();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Validate Teachers</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body>
<header class="bg-gray-800 text-white p-4">
    <h1>Validate Teachers</h1>
</header>
<div class="p-4">
    <a href="dashboard.php" class="text-blue-600">Back to Dashboard</a>
    <hr class="my-4">
    <table class="border-collapse w-full">
        <thead>
            <tr class="bg-gray-200">
                <th class="border p-2">ID</th>
                <th class="border p-2">Username</th>
                <th class="border p-2">Email</th>
                <th class="border p-2">Action</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($pendingTeachers as $teacher): ?>
            <tr>
                <td class="border p-2"><?php echo $teacher['id']; ?></td>
                <td class="border p-2"><?php echo htmlspecialchars($teacher['username']); ?></td>
                <td class="border p-2"><?php echo htmlspecialchars($teacher['email']); ?></td>
                <td class="border p-2">
                    <a class="text-blue-600" href="?approve=<?php echo $teacher['id']; ?>">Approve</a>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>
</body>
</html>
