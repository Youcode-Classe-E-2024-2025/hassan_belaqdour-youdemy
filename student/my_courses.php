<?php
session_start();
require_once '../classes/Student.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'student') {
    header("Location: ../auth/login.php");
    exit;
}

$student = new Student($_SESSION['user_id']);
$myCourses = $student->getMyCourses();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>My Courses</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-50">
    <header class="bg-gradient-to-r from-purple-600 to-indigo-700 text-white p-6 shadow-lg">
        <div class="container mx-auto">
            <h1 class="text-3xl font-bold">My Courses</h1>
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

        <div class="space-y-4">
            <?php foreach ($myCourses as $course): ?>
                <div class="bg-white p-6 rounded-lg shadow-md hover:shadow-lg transition-shadow duration-300">
                    <h2 class="text-2xl font-bold text-gray-800 mb-2"><?php echo htmlspecialchars($course['title']); ?></h2>
                    <p class="text-gray-600 mb-4"><?php echo htmlspecialchars($course['description']); ?></p>
                    <a href="course_details.php?id=<?php echo $course['id']; ?>"
                        class="text-purple-600 hover:text-purple-800 font-semibold transition duration-300">
                        View Course Details
                    </a>
                </div>
            <?php endforeach; ?>
        </div>

        <?php if (empty($myCourses)): ?>
            <div class="bg-white p-6 rounded-lg shadow-md text-center">
                <p class="text-gray-600">You are not enrolled in any courses yet.</p>
                <a href="../public/index.php"
                    class="mt-4 inline-block bg-purple-600 text-white px-6 py-2 rounded-lg hover:bg-purple-700 transition duration-300">
                    Explore Courses
                </a>
            </div>
        <?php endif; ?>
    </div>
</body>

</html>