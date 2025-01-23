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
    <header class="bg-gray-800 text-white p-4 flex justify-between">
        <div>
            <a href="index.php">Youdemy</a>
        </div>
        <nav>
            <?php echo (isset($_SESSION['user_role']) ? '<a href="../' . $_SESSION['user_role'] . '/dashboard.php">Back</a>' : ''); ?>
            <?php if (!isset($_SESSION['user_id'])): ?>
                <a href="../auth/login.php" class="ml-6 mr-4">Login</a>
                <a href="../auth/signup.php">Sign Up</a>
            <?php else: ?>
                <a class="ml-6" href="../auth/logout.php">Logout</a>
            <?php endif; ?>
        </nav>
    </header>

    <div class="container mx-auto mt-8">
        <h1 class="text-2xl font-bold mb-4">Course Catalog</h1>
        <form method="GET" class="mb-4">
            <input type="text" name="search" placeholder="Search courses..." class="border p-2"
                value="<?php echo htmlspecialchars($search); ?>">
            <button type="submit" class="bg-blue-500 text-white px-4 py-2">Search</button>
        </form>

        <?php foreach ($courses as $course): ?>
            <div class="bg-white p-4 mb-4 rounded shadow">
                <h2 class="text-xl font-semibold"><?php echo htmlspecialchars($course['title']); ?></h2>
                <p><?php echo htmlspecialchars(substr($course['description'], 0, 150)); ?>...</p>

                <?php if (isset($_SESSION['user_id']) && $_SESSION['user_role'] === 'student'): ?>

                    <?php if ($studentObj && $studentObj->isEnrolled($course['id'])): ?>
                        <span class="text-green-600 font-semibold">Already Enrolled</span>
                        <a href="course_details.php?id=<?php echo $course['id']; ?>" class="ml-3 text-blue-600">
                            View Details
                        </a>
                    <?php else: ?>
                        <form action="enroll.php" method="POST" style="display:inline;">
                            <input type="hidden" name="course_id" value="<?php echo $course['id']; ?>">
                            <button type="submit" class="bg-green-500 text-white px-3 py-1">Enroll</button>
                        </form>
                        <a href="course_details.php?id=<?php echo $course['id']; ?>" class="ml-3 text-blue-600">
                            View Details
                        </a>
                    <?php endif; ?>

                <?php else: ?>
                    <a href="../auth/login.php" class="bg-gray-500 text-white px-3 py-1">Login to Enroll</a>
                    <span class="ml-2 text-gray-600">Login to see details</span>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>

        <div class="flex space-x-2 mt-4">
            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                <a class="px-3 py-1 border <?php if ($i == $page)
                    echo 'bg-blue-300'; ?>"
                    href="?page=<?php echo $i; ?>&search=<?php echo urlencode($search); ?>">
                    <?php echo $i; ?>
                </a>
            <?php endfor; ?>
        </div>
    </div>
</body>

</html>