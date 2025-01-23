<?php
session_start();
require_once '../classes/Admin.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: ../auth/login.php");
    exit;
}

$admin = new Admin($_SESSION['user_id']);

if (isset($_GET['suspend'])) {
    $admin->updateStatus((int) $_GET['suspend'], 'suspended');
    header("Location: manage_users.php");
    exit;
}
if (isset($_GET['activate'])) {
    $admin->updateStatus((int) $_GET['activate'], 'active');
    header("Location: manage_users.php");
    exit;
}

$allUsers = $admin->getAllUsers();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Manage Users</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body>
    <header class="bg-gray-800 text-white p-4">
        <h1>Manage Users</h1>
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
                    <th class="border p-2">Role</th>
                    <th class="border p-2">Status</th>
                    <th class="border p-2">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($allUsers as $user): ?>
                    <tr>
                        <td class="border p-2"><?php echo $user['id']; ?></td>
                        <td class="border p-2"><?php echo htmlspecialchars($user['username']); ?></td>
                        <td class="border p-2"><?php echo htmlspecialchars($user['email']); ?></td>
                        <td class="border p-2"><?php echo $user['role']; ?></td>
                        <td class="border p-2"><?php echo $user['status']; ?></td>
                        <td class="border p-2">
                            <?php if ($user['status'] === 'active'): ?>
                                <a class="text-red-600" href="?suspend=<?php echo $user['id']; ?>">Suspend</a>
                            <?php else: ?>
                                <a class="text-green-600" href="?activate=<?php echo $user['id']; ?>">Activate</a>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>

</html>