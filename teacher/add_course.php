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

<body class="bg-gray-50">
    <header class="bg-gradient-to-r from-purple-600 to-indigo-700 text-white p-6 shadow-lg">
        <div class="container mx-auto">
            <h1 class="text-3xl font-bold">Add Course</h1>
        </div>
    </header>

    <div class="container mx-auto p-6">
        <a href="dashboard.php" class="text-purple-600 hover:text-purple-800 transition duration-300 flex items-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd"
                    d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z"
                    clip-rule="evenodd" />
            </svg>
            Back to Dashboard
        </a>
        <hr class="my-6 border-gray-200">

        <form action="" method="POST" class="bg-white p-8 rounded-xl shadow-lg">
            <div class="mb-6">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="title">Title</label>
                <input type="text" name="title" id="title" required
                    class="w-full px-4 py-3 border rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent transition duration-300"
                    placeholder="Enter course title">
            </div>

            <div class="mb-6">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="description">Description</label>
                <textarea name="description" id="description" required
                    class="w-full px-4 py-3 border rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent transition duration-300"
                    placeholder="Enter course description" rows="4"></textarea>
            </div>

            <div class="mb-6">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="content">Content</label>
                <textarea name="content" id="content" required
                    class="w-full px-4 py-3 border rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent transition duration-300"
                    placeholder="Enter course content" rows="6"></textarea>
            </div>

            <div class="mb-6">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="category_id">Category</label>
                <select name="category_id" id="category_id"
                    class="w-full px-4 py-3 border rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent transition duration-300">
                    <option value="0">-- Select Category --</option>
                    <?php foreach ($allCategories as $cat): ?>
                        <option value="<?php echo $cat['id']; ?>">
                            <?php echo htmlspecialchars($cat['name']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="mb-6">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="format">Format</label>
                <select name="format" id="format"
                    class="w-full px-4 py-3 border rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent transition duration-300">
                    <option value="document">Document</option>
                    <option value="video">Video</option>
                </select>
            </div>

            <div class="mb-6">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="tag_ids">Tags (Ctrl+Click for
                    multiple)</label>
                <select name="tag_ids[]" id="tag_ids" multiple size="5"
                    class="w-full px-4 py-3 border rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent transition duration-300">
                    <?php foreach ($allTags as $tag): ?>
                        <option value="<?php echo $tag['id']; ?>">
                            <?php echo htmlspecialchars($tag['name']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <button type="submit"
                class="w-full bg-gradient-to-r from-purple-600 to-indigo-700 text-white px-6 py-3 rounded-lg hover:from-purple-700 hover:to-indigo-800 focus:outline-none focus:ring-2 focus:ring-purple-500 transition duration-300">
                Add Course
            </button>
        </form>
    </div>
</body>

</html>