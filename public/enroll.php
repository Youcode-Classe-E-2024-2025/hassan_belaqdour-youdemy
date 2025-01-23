<?php
session_start();
require_once '../classes/Student.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_SESSION['user_id']) && $_SESSION['user_role'] === 'student') {
        $courseId = (int) $_POST['course_id'];
        $student = new Student($_SESSION['user_id']);
        $student->enrollCourse($courseId);
        $_SESSION['message'] = "Enrolled successfully!";
        header("Location: course_details.php?id=$courseId");
        exit;
    } else {
        $_SESSION['message'] = "You must be a student to enroll.";
        header("Location: index.php");
        exit;
    }
}
header("Location: index.php");
