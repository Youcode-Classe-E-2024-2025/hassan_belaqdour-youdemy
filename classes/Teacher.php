<?php
require_once 'User.php';

class Teacher extends User
{
    public function addCourse($title, $description, $content, $categoryId, $format, $tagIds = [])
    {
        $stmt = $this->conn->prepare("
            INSERT INTO courses (teacher_id, title, description, content, category_id, format)
            VALUES (:tid, :t, :d, :c, :cat, :f)
        ");
        $stmt->execute([
            'tid' => $this->id,
            't' => $title,
            'd' => $description,
            'c' => $content,
            'cat' => $categoryId ?: null,
            'f' => $format
        ]);
        $courseId = $this->conn->lastInsertId();

        if (!empty($tagIds)) {
            $tagStmt = $this->conn->prepare("
                INSERT INTO course_tags (course_id, tag_id) 
                VALUES (:cid, :tid)
            ");
            foreach ($tagIds as $tagId) {
                $tagStmt->execute(['cid' => $courseId, 'tid' => $tagId]);
            }
        }

        return $courseId;
    }

    public function getMyCourses()
    {
        $stmt = $this->conn->prepare("SELECT * FROM courses WHERE teacher_id = :tid");
        $stmt->execute(['tid' => $this->id]);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function updateCourse($courseId, $title, $description, $content, $category)
    {
        $stmt = $this->conn->prepare("UPDATE courses 
                                      SET title = :t, description = :d, content = :c, category_id = :cat
                                      WHERE id = :cid AND teacher_id = :tid");
        $stmt->execute([
            't' => $title,
            'd' => $description,
            'c' => $content,
            'cat' => $category,
            'cid' => $courseId,
            'tid' => $this->id
        ]);
    }

    public function deleteCourse($courseId)
    {
        $stmt = $this->conn->prepare("DELETE FROM courses WHERE id = :cid AND teacher_id = :tid");
        $stmt->execute(['cid' => $courseId, 'tid' => $this->id]);
    }

    public function getCourseStats()
    {
        $stmt = $this->conn->prepare("
            SELECT c.title, COUNT(e.id) as total_students
            FROM courses c
            LEFT JOIN enrollments e ON c.id = e.course_id
            WHERE c.teacher_id = :tid
            GROUP BY c.id
        ");
        $stmt->execute(['tid' => $this->id]);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
}
