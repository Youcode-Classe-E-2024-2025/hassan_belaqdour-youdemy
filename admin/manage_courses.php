<?php
session_start();
require_once '../classes/Admin.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: ../auth/login.php");
    exit;
}

$admin = new Admin($_SESSION['user_id']);
$db = new Database();
$conn = $db->getConnection();

if (isset($_GET['delete'])) {
    $cid = (int) $_GET['delete'];
    $stmt = $conn->prepare("DELETE FROM courses WHERE id = :cid");
    $stmt->execute(['cid' => $cid]);
    header("Location: manage_courses.php");
    exit;
}

$sql = "
    SELECT c.id,
           c.title,
           u.username AS teacher_name,
           cat.name AS category_name
    FROM courses c
    JOIN users u ON c.teacher_id = u.id
    LEFT JOIN categories cat ON c.category_id = cat.id
";
$stmt = $conn->prepare($sql);
$stmt->execute();
$courses = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Manage Courses</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body>
    <header class="bg-gray-800 text-white p-4">
        <h1>Manage Courses</h1>
    </header>
    <div class="p-4">
        <a href="dashboard.php" class="text-blue-600">Back to Dashboard</a>
        <hr class="my-4">
        <table class="border-collapse w-full">
            <thead>
                <tr class="bg-gray-200">
                    <th class="border p-2">ID</th>
                    <th class="border p-2">Title</th>
                    <th class="border p-2">Teacher</th>
                    <th class="border p-2">Category</th>
                    <th class="border p-2">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($courses as $course): ?>
                    <tr>
                        <td class="border p-2"><?php echo $course['id']; ?></td>
                        <td class="border p-2"><?php echo htmlspecialchars($course['title']); ?></td>
                        <td class="border p-2"><?php echo htmlspecialchars($course['teacher_name']); ?></td>
                        <td class="border p-2">
                            <?php echo htmlspecialchars($course['category_name'] ?? ''); ?>
                        </td>
                        <td class="border p-2">
                            <a class="text-red-600" href="?delete=<?php echo $course['id']; ?>"
                                onclick="return confirm('Are you sure you want to delete this course?');">
                                Delete
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>

</html>