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

        <?php if (!empty($_SESSION['message'])): ?>
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-6 animate-fade-in">
                <?php echo $_SESSION['message'];
                unset($_SESSION['message']); ?>
            </div>
        <?php endif; ?>

        <div class="space-y-4">
            <?php foreach ($myCourses as $course): ?>
                <div class="bg-white p-6 rounded-xl shadow-md hover:shadow-lg transition-shadow duration-300">
                    <h2 class="text-2xl font-bold text-gray-800 mb-2"><?php echo htmlspecialchars($course['title']); ?></h2>
                    <p class="text-gray-600 mb-4"><?php echo htmlspecialchars($course['description']); ?></p>

                    <div class="flex items-center space-x-4">
                        <a href="edit_course.php?id=<?php echo $course['id']; ?>"
                            class="text-purple-600 hover:text-purple-800 transition duration-300">
                            Edit
                        </a>
                        <span class="text-gray-400">|</span>
                        <a href="?delete=<?php echo $course['id']; ?>"
                            class="text-red-600 hover:text-red-800 transition duration-300"
                            onclick="return confirm('Are you sure you want to delete this course?');">
                            Delete
                        </a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <?php if (empty($myCourses)): ?>
            <div class="bg-white p-6 rounded-lg shadow-md text-center">
                <p class="text-gray-600">You haven't created any courses yet.</p>
                <a href="add_course.php"
                    class="mt-4 inline-block bg-purple-600 text-white px-6 py-2 rounded-lg hover:bg-purple-700 transition duration-300">
                    Add a Course
                </a>
            </div>
        <?php endif; ?>
    </div>

    <style>
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-fade-in {
            animation: fadeIn 0.5s ease-in-out;
        }
    </style>
</body>

</html>