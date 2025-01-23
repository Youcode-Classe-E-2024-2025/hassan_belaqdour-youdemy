<?php
session_start();
require_once '../classes/Course.php';
require_once '../classes/Student.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'student') {
    header("Location: ../auth/login.php");
    exit;
}

if (!isset($_GET['id'])) {
    die("Course not found!");
}

$courseId = (int) $_GET['id'];
$courseObj = new Course($courseId);

$student = new Student($_SESSION['user_id']);
$isEnrolled = $student->isEnrolled($courseId);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Course Details</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-50">
    <header class="bg-gray-800 text-white p-4 flex justify-between">
        <div>
            <a href="index.php">Youdemy</a>
        </div>
        <nav>
            <a href="../public/index.php" class="text-blue-600">Back to Home</a>
            <a class="ml-6" href="../auth/logout.php">Logout</a>
        </nav>
    </header>

    <div class="container mx-auto mt-8 bg-white p-4 rounded shadow">
        <h1 class="text-2xl font-bold"><?php echo htmlspecialchars($courseObj->getTitle()); ?></h1>
        <p class="mt-2"><?php echo nl2br(htmlspecialchars($courseObj->getDescription())); ?></p>
        <p class="mt-2 font-semibold">
            Category:
            <?php
            echo htmlspecialchars($courseObj->getCategory() ?? 'Uncategorized');
            ?>
        </p>

        <div class="mt-4">
            <p><?php echo nl2br(htmlspecialchars($courseObj->getContent())); ?></p>
        </div>

        <?php if ($isEnrolled): ?>
            <button class="bg-blue-500 text-white px-4 py-2 mt-4">
                Download Resource
            </button>
        <?php else: ?>
            <p class="mt-4 text-red-600">You must enroll in this course before accessing its resources.</p>
        <?php endif; ?>
    </div>
</body>

</html>