<?php
require_once 'User.php';

class Admin extends User
{
    public function getAllPendingTeachers()
    {
        $stmt = $this->conn->prepare("SELECT * FROM users WHERE role = 'teacher' AND is_approved = 0");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function approveTeacher($teacherId)
    {
        parent::approveTeacher($teacherId);
    }

    public function getAllUsers()
    {
        $stmt = $this->conn->prepare("SELECT * FROM users");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getGlobalStats()
    {
        $stmtCourses = $this->conn->prepare("SELECT COUNT(*) as total_courses FROM courses");
        $stmtCourses->execute();
        $totalCourses = $stmtCourses->fetch(PDO::FETCH_ASSOC)['total_courses'];

        $stmtPopular = $this->conn->prepare("
            SELECT c.title, COUNT(e.id) as total_students
            FROM courses c
            LEFT JOIN enrollments e ON c.id = e.course_id
            GROUP BY c.id
            ORDER BY total_students DESC
            LIMIT 1
        ");
        $stmtPopular->execute();
        $mostStudentsCourse = $stmtPopular->fetch(PDO::FETCH_ASSOC);

        $stmtTopTeachers = $this->conn->prepare("
            SELECT u.username, COUNT(e.id) as total_enrollments
            FROM users u
            JOIN courses c ON u.id = c.teacher_id
            LEFT JOIN enrollments e ON c.id = e.course_id
            WHERE u.role = 'teacher'
            GROUP BY u.id
            ORDER BY total_enrollments DESC
            LIMIT 3
        ");
        $stmtTopTeachers->execute();
        $topTeachers = $stmtTopTeachers->fetchAll(PDO::FETCH_ASSOC);

        return [
            'total_courses' => $totalCourses,
            'most_popular_course' => $mostStudentsCourse,
            'top_teachers' => $topTeachers
        ];
    }
}
