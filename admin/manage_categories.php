<?php
session_start();
require_once '../classes/Admin.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: ../auth/login.php");
    exit;
}

$admin = new Admin($_SESSION['user_id']);

require_once '../database.php';
$db    = new Database();
$conn  = $db->getConnection();

if (isset($_POST['add_category'])) {
    $name = trim($_POST['category_name']);
    if (!empty($name)) {
        $stmt = $conn->prepare("INSERT INTO categories (name) VALUES (:name)");
        $stmt->execute(['name' => $name]);
    }
    header("Location: manage_categories.php");
    exit;
}

if (isset($_GET['delete'])) {
    $catId = (int)$_GET['delete'];
    $stmt = $conn->prepare("DELETE FROM categories WHERE id = :id");
    $stmt->execute(['id' => $catId]);
    header("Location: manage_categories.php");
    exit;
}

if (isset($_POST['edit_category_id'])) {
    $catId = (int)$_POST['edit_category_id'];
    $newName = trim($_POST['edit_category_name']);
    $stmt = $conn->prepare("UPDATE categories SET name = :name WHERE id = :id");
    $stmt->execute(['name' => $newName, 'id' => $catId]);
    header("Location: manage_categories.php");
    exit;
}

$stmt = $conn->prepare("SELECT * FROM categories");
$stmt->execute();
$categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Manage Categories</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body>
<header class="bg-gray-800 text-white p-4">
  <h1>Manage Categories</h1>
</header>
<div class="p-4">
  <a href="dashboard.php" class="text-blue-600">Back to Dashboard</a> |
  <a href="manage_tags.php" class="text-blue-600">Manage Tags</a>
  <hr class="my-4">

  <form method="POST" class="mb-4 flex gap-2">
      <input type="text" name="category_name" placeholder="New Category Name" required class="border p-2">
      <button type="submit" name="add_category" class="bg-blue-500 text-white px-4 py-2">Add Category</button>
  </form>

  <table class="border-collapse w-full">
    <thead>
      <tr class="bg-gray-200">
        <th class="border p-2">ID</th>
        <th class="border p-2">Name</th>
        <th class="border p-2">Actions</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($categories as $cat): ?>
      <tr>
        <td class="border p-2"><?php echo $cat['id']; ?></td>
        <td class="border p-2"><?php echo htmlspecialchars($cat['name']); ?></td>
        <td class="border p-2">
          <form method="POST" style="display:inline;">
            <input type="hidden" name="edit_category_id" value="<?php echo $cat['id']; ?>">
            <input type="text" name="edit_category_name" value="<?php echo htmlspecialchars($cat['name']); ?>" class="border p-1">
            <button type="submit" class="bg-yellow-500 text-white px-2 py-1">Update</button>
          </form>
          |
          <a href="?delete=<?php echo $cat['id']; ?>" 
             class="text-red-600"
             onclick="return confirm('Delete this category?');">
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
