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
    <title>Course Details - <?php echo htmlspecialchars($courseObj->getTitle()); ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50">
    <header class="bg-gradient-to-r from-purple-600 to-indigo-700 text-white p-6 shadow-lg">
        <div class="container mx-auto flex justify-between items-center">
            <a href="index.php" class="text-2xl font-bold hover:text-gray-200 transition duration-300">Youdemy</a>
            <nav class="flex items-center space-x-6">
                <a href="../public/index.php" class="text-gray-200 hover:text-white transition duration-300">Back to Home</a>
                <a href="../auth/logout.php" class="text-gray-200 hover:text-white transition duration-300">Logout</a>
            </nav>
        </div>
    </header>

    <div class="container mx-auto mt-8 p-6">
        <div class="bg-white p-8 rounded-xl shadow-lg">
            <h1 class="text-3xl font-bold text-gray-800 mb-4"><?php echo htmlspecialchars($courseObj->getTitle()); ?></h1>
            <p class="text-gray-600 mb-6"><?php echo nl2br(htmlspecialchars($courseObj->getDescription())); ?></p>
            <p class="text-gray-700 font-semibold mb-6">
                Category: <?php echo htmlspecialchars($courseObj->getCategory() ?? 'Uncategorized'); ?>
            </p>

            <div class="mt-6">
                <h2 class="text-2xl font-bold text-gray-800 mb-4">Course Content</h2>
                <div class="text-gray-600">
                    <?php echo nl2br(htmlspecialchars($courseObj->getContent())); ?>
                </div>
            </div>

            <div class="mt-8">
                <?php if ($isEnrolled): ?>
                    <button
                        class="bg-gradient-to-r from-purple-600 to-indigo-700 text-white px-6 py-3 rounded-lg hover:from-purple-700 hover:to-indigo-800 focus:outline-none focus:ring-2 focus:ring-purple-500 transition duration-300"
                    >
                        Download Resource
                    </button>
                <?php else: ?>
                    <div class="bg-red-50 p-4 rounded-lg border border-red-200">
                        <p class="text-red-600 font-semibold">You must enroll in this course to access its resources.</p>
                        <a
                            href="enroll.php?course_id=<?php echo $courseObj->getId(); ?>"
                            class="mt-2 inline-block bg-purple-600 text-white px-6 py-2 rounded-lg hover:bg-purple-700 transition duration-300"
                        >
                            Enroll Now
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>
</html>
