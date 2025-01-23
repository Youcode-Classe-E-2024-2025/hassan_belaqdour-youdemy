<?php
require_once 'User.php';

class Student extends User
{
    public function enrollCourse($courseId)
    {
        $stmt = $this->conn->prepare("INSERT INTO enrollments (student_id, course_id) 
                                      VALUES (:sid, :cid)");
        try {
            $stmt->execute(['sid' => $this->id, 'cid' => $courseId]);
        } catch (PDOException $e) {
        }
    }

    public function getMyCourses()
    {
        $stmt = $this->conn->prepare("SELECT c.* FROM courses c 
                                      JOIN enrollments e ON c.id = e.course_id
                                      WHERE e.student_id = :sid");
        $stmt->execute(['sid' => $this->id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function isEnrolled($courseId)
    {
        $stmt = $this->conn->prepare("
            SELECT COUNT(*) AS cnt 
            FROM enrollments 
            WHERE student_id = :sid AND course_id = :cid
        ");
        $stmt->execute(['sid' => $this->id, 'cid' => $courseId]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return ($row['cnt'] > 0);
    }
}
