<?php
require_once __DIR__ . '/../database.php';

class Course
{
    protected $id;
    protected $teacherId;
    protected $title;
    protected $description;
    protected $content;
    protected $category;
    protected $conn;

    public function __construct($id = null)
    {
        $db = new Database();
        $this->conn = $db->getConnection();
        if ($id) {
            $this->loadById($id);
        }
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function getCategory()
    {
        return $this->category;
    }

    public function getContent()
    {
        return $this->content;
    }

    public function loadById($id)
    {
        $stmt = $this->conn->prepare("
            SELECT c.*,
                   cat.name AS category_name
            FROM courses c
            LEFT JOIN categories cat ON c.category_id = cat.id
            WHERE c.id = :id
        ");
        $stmt->execute(['id' => $id]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($data) {
            $this->id = $data['id'];
            $this->teacherId = $data['teacher_id'];
            $this->title = $data['title'];
            $this->description = $data['description'];
            $this->content = $data['content'];
            $this->category = $data['category_name'];
        }
    }

    public static function getAll($page = 1, $perPage = 5, $search = '')
    {
        $db = new Database();
        $conn = $db->getConnection();
        $offset = ($page - 1) * $perPage;
        $searchQuery = "%$search%";

        $sql = "SELECT * FROM courses 
                WHERE title LIKE :search 
                   OR description LIKE :search 
                LIMIT :offset, :perpage";
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(':search', $searchQuery, PDO::PARAM_STR);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->bindValue(':perpage', $perPage, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function countAll($search = '')
    {
        $db = new Database();
        $conn = $db->getConnection();
        $searchQuery = "%$search%";

        $sql = "SELECT COUNT(*) as total FROM courses
                WHERE title LIKE :search 
                   OR description LIKE :search";
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(':search', $searchQuery);
        $stmt->execute();
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        return $data['total'];
    }
}
