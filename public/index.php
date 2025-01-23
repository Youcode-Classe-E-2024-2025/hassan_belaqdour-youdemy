<?php
session_start();
require_once '../classes/Course.php';
require_once '../classes/Student.php';

$page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
$search = isset($_GET['search']) ? trim($_GET['search']) : '';

$perPage = 5;
$totalCourses = Course::countAll($search);
$courses = Course::getAll($page, $perPage, $search);
$totalPages = ceil($totalCourses / $perPage);

$studentObj = null;
if (isset($_SESSION['user_id']) && $_SESSION['user_role'] === 'student') {
    require_once '../classes/Student.php';
    $studentObj = new Student($_SESSION['user_id']);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Youdemy - Catalog</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50">
    <header class="bg-gradient-to-r from-purple-600 to-indigo-700 text-white p-6 shadow-lg">
        <div class="container mx-auto flex justify-between items-center">
            <a href="index.php" class="text-2xl font-bold hover:text-gray-200 transition duration-300">Youdemy</a>
            <nav class="flex items-center space-x-6">
                <?php echo (isset($_SESSION['user_role']) ? '<a href="../' . $_SESSION['user_role'] . '/dashboard.php" class="hover:text-gray-200 transition duration-300">Back</a>' : ''); ?>
                <?php if (!isset($_SESSION['user_id'])): ?>
                    <a href="../auth/login.php" class="hover:text-gray-200 transition duration-300">Login</a>
                    <a href="../auth/signup.php"
                        class="bg-white text-purple-600 px-4 py-2 rounded-lg hover:bg-gray-100 transition duration-300">Sign
                        Up</a>
                <?php else: ?>
                    <a href="../auth/logout.php" class="hover:text-gray-200 transition duration-300">Logout</a>
                <?php endif; ?>
            </nav>
        </div>
    </header>

    <div class="container mx-auto mt-8 px-4">
        <h1 class="text-3xl font-bold text-gray-800 mb-6">Course Catalog</h1>

        <form method="GET" class="mb-8">
            <div class="flex items-center">
                <input type="text" name="search" placeholder="Search courses..."
                    class="w-full px-4 py-3 border rounded-l-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent transition duration-300"
                    value="<?php echo htmlspecialchars($search); ?>">
                <button type="submit"
                    class="bg-purple-600 text-white px-6 py-3 rounded-r-lg hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-purple-500 transition duration-300">
                    Search
                </button>
            </div>
        </form>

        <div class="space-y-6">
            <?php foreach ($courses as $course): ?>
                <div class="bg-white p-6 rounded-xl shadow-lg hover:shadow-xl transition-shadow duration-300">
                    <h2 class="text-2xl font-bold text-gray-800 mb-2"><?php echo htmlspecialchars($course['title']); ?></h2>
                    <p class="text-gray-600 mb-4"><?php echo htmlspecialchars(substr($course['description'], 0, 150)); ?>...</p>

                    <div class="flex items-center space-x-4">
                        <?php if (isset($_SESSION['user_id']) && $_SESSION['user_role'] === 'student'): ?>
                            <?php if ($studentObj && $studentObj->isEnrolled($course['id'])): ?>
                                <span class="text-green-600 font-semibold">Already Enrolled</span>
                                <a href="course_details.php?id=<?php echo $course['id']; ?>"
                                    class="text-purple-600 hover:text-purple-800 transition duration-300">
                                    View Details
                                </a>
                            <?php else: ?>
                                <form action="enroll.php" method="POST" class="inline">
                                    <input type="hidden" name="course_id" value="<?php echo $course['id']; ?>">
                                    <button type="submit"
                                        class="bg-green-500 text-white px-4 py-2 rounded-lg hover:bg-green-600 transition duration-300">
                                        Enroll
                                    </button>
                                </form>
                                <a href="course_details.php?id=<?php echo $course['id']; ?>"
                                    class="text-purple-600 hover:text-purple-800 transition duration-300">
                                    View Details
                                </a>
                            <?php endif; ?>
                        <?php else: ?>
                            <a href="../auth/login.php"
                                class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600 transition duration-300">
                                Login to Enroll
                            </a>
                            <span class="text-gray-600">Login to see details</span>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <div class="flex justify-center space-x-2 mt-8">
            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                <a class="px-4 py-2 border rounded-lg <?php if ($i == $page)
                    echo 'bg-purple-600 text-white';
                else
                    echo 'text-purple-600 hover:bg-purple-100'; ?> transition duration-300"
                    href="?page=<?php echo $i; ?>&search=<?php echo urlencode($search); ?>">
                    <?php echo $i; ?>
                </a>
            <?php endfor; ?>
        </div>
    </div>
</body>
</html>
