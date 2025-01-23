<?php
session_start();
require_once '../classes/Teacher.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'teacher') {
    header("Location: ../auth/login.php");
    exit;
}

$teacher = new Teacher($_SESSION['user_id']);
$myCourses = $teacher->getMyCourses();

if (isset($_GET['delete'])) {
    $teacher->deleteCourse((int) $_GET['delete']);
    header("Location: my_courses.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>My Courses (Teacher)</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body>
    <header class="bg-gray-800 text-white p-4">
        <h1>My Courses</h1>
    </header>
    <div class="p-4">
        <a href="dashboard.php" class="text-blue-600">Back to Dashboard</a>
        <hr class="my-4">

        <?php if (!empty($_SESSION['message'])): ?>
            <p class="text-green-500"><?php echo $_SESSION['message'];
            unset($_SESSION['message']); ?></p>
        <?php endif; ?>

        <?php foreach ($myCourses as $course): ?>
            <div class="bg-white p-4 mb-2 border">
                <h2 class="font-bold"><?php echo htmlspecialchars($course['title']); ?></h2>
                <p><?php echo htmlspecialchars($course['description']); ?></p>
                <a class="text-blue-600" href="edit_course.php?id=<?php echo $course['id']; ?>">Edit</a> |
                <a class="text-red-600" href="?delete=<?php echo $course['id']; ?>"
                    onclick="return confirm('Are you sure you want to delete this course?');">
                    Delete
                </a>
            </div>
        <?php endforeach; ?>
    </div>
</body>

</html>