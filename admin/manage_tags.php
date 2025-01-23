<?php
session_start();
require_once '../classes/Admin.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: ../auth/login.php");
    exit;
}

$admin = new Admin($_SESSION['user_id']);

require_once '../database.php';
$db = new Database();
$conn = $db->getConnection();

if (isset($_POST['mass_tags'])) {
    $raw = trim($_POST['mass_tags']);
    $tagNames = array_map('trim', explode(',', $raw));
    foreach ($tagNames as $t) {
        if (!empty($t)) {
            $stmt = $conn->prepare("INSERT INTO tags (name) VALUES (:name)");
            $stmt->execute(['name' => $t]);
        }
    }
    header("Location: manage_tags.php");
    exit;
}

if (isset($_GET['delete'])) {
    $tagId = (int) $_GET['delete'];
    $stmt = $conn->prepare("DELETE FROM tags WHERE id = :id");
    $stmt->execute(['id' => $tagId]);
    header("Location: manage_tags.php");
    exit;
}

$stmt = $conn->prepare("SELECT * FROM tags");
$stmt->execute();
$tags = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Manage Tags</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body>
    <header class="bg-gray-800 text-white p-4">
        <h1>Manage Tags</h1>
    </header>
    <div class="p-4">
        <a href="dashboard.php" class="text-blue-600">Back to Dashboard</a> |
        <a href="manage_categories.php" class="text-blue-600">Manage Categories</a>
        <hr class="my-4">

        <form method="POST" class="mb-4">
            <label class="block mb-2">Enter comma-separated tags:</label>
            <textarea name="mass_tags" rows="3" class="border p-2 w-1/2" placeholder="tag1, tag2, tag3"></textarea>
            <br>
            <button type="submit" class="bg-blue-500 text-white px-4 py-2 mt-2">Add Tags</button>
        </form>

        <table class="border-collapse w-full">
            <thead>
                <tr class="bg-gray-200">
                    <th class="border p-2">ID</th>
                    <th class="border p-2">Tag Name</th>
                    <th class="border p-2">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($tags as $tag): ?>
                    <tr>
                        <td class="border p-2"><?php echo $tag['id']; ?></td>
                        <td class="border p-2"><?php echo htmlspecialchars($tag['name']); ?></td>
                        <td class="border p-2">
                            <a href="?delete=<?php echo $tag['id']; ?>" class="text-red-600"
                                onclick="return confirm('Delete this tag?');">Delete</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>

</html>