<?php
session_start();
require_once '../classes/Teacher.php';
require_once '../database.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'teacher') {
    header("Location: ../auth/login.php");
    exit;
}

$teacher = new Teacher($_SESSION['user_id']);
$db = new Database();
$conn = $db->getConnection();

$catStmt = $conn->prepare("SELECT * FROM categories");
$catStmt->execute();
$allCategories = $catStmt->fetchAll(PDO::FETCH_ASSOC);

$tagStmt = $conn->prepare("SELECT * FROM tags");
$tagStmt->execute();
$allTags = $tagStmt->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title']);
    $description = trim($_POST['description']);
    $content = trim($_POST['content']);
    $format = trim($_POST['format']);
    $categoryId = (int) ($_POST['category_id'] ?? 0);

    $tagIds = isset($_POST['tag_ids']) ? $_POST['tag_ids'] : [];

    $teacher->addCourse($title, $description, $content, $categoryId, $format, $tagIds);

    $_SESSION['message'] = "Course added successfully!";
    header("Location: my_courses.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Add Course</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body>
    <header class="bg-gray-800 text-white p-4">
        <h1>Add Course</h1>
    </header>
    <div class="p-4">
        <a href="dashboard.php" class="text-blue-600">Back to Dashboard</a>
        <hr class="my-4">
        <form action="" method="POST" class="bg-white p-4">
            <div class="mb-4">
                <label class="block">Title</label>
                <input type="text" name="title" class="border p-2 w-full" required>
            </div>
            <div class="mb-4">
                <label class="block">Description</label>
                <textarea name="description" class="border p-2 w-full" required></textarea>
            </div>
            <div class="mb-4">
                <label class="block">Content</label>
                <textarea name="content" class="border p-2 w-full" required></textarea>
            </div>

            <div class="mb-4">
                <label class="block">Category</label>
                <select name="category_id" class="border p-2 w-full">
                    <option value="0">-- Select Category --</option>
                    <?php foreach ($allCategories as $cat): ?>
                        <option value="<?php echo $cat['id']; ?>">
                            <?php echo htmlspecialchars($cat['name']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="mb-4">
                <label class="block">Format</label>
                <select name="format" class="border p-2 w-full">
                    <option value="document">Document</option>
                    <option value="video">Video</option>
                </select>
            </div>

            <div class="mb-4">
                <label class="block">Tags (Ctrl+Click for multiple)</label>
                <select name="tag_ids[]" class="border p-2 w-full" multiple size="5">
                    <?php foreach ($allTags as $tag): ?>
                        <option value="<?php echo $tag['id']; ?>">
                            <?php echo htmlspecialchars($tag['name']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <button type="submit" class="bg-blue-500 text-white px-4 py-2">Add Course</button>
        </form>
    </div>
</body>

</html>