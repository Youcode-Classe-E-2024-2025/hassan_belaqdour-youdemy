<?php
require_once __DIR__ . '/../database.php';

class User
{
    protected $id;
    protected $username;
    protected $email;
    protected $password;
    protected $role;
    protected $isApproved;
    protected $status;

    protected $conn;

    public function __construct($id = null)
    {
        $db = new Database();
        $this->conn = $db->getConnection();
        if ($id) {
            $this->loadById($id);
        }
    }

    public function loadById($id)
    {
        $stmt = $this->conn->prepare("SELECT * FROM users WHERE id = :id");
        $stmt->execute(['id' => $id]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($data) {
            $this->id = $data['id'];
            $this->username = $data['username'];
            $this->email = $data['email'];
            $this->password = $data['password'];
            $this->role = $data['role'];
            $this->isApproved = $data['is_approved'];
            $this->status = $data['status'];
        }
    }

    public function getId()
    {
        return $this->id;
    }
    public function getUsername()
    {
        return $this->username;
    }
    public function getEmail()
    {
        return $this->email;
    }
    public function getRole()
    {
        return $this->role;
    }
    public function getIsApproved()
    {
        return $this->isApproved;
    }
    public function getStatus()
    {
        return $this->status;
    }

    public function create($username, $email, $password, $role = 'student')
    {
        $hashedPassword = md5($password);

        $stmt = $this->conn->prepare("INSERT INTO users (username, email, password, role) 
            VALUES (:username, :email, :password, :role)");
        $stmt->execute([
            'username' => $username,
            'email' => $email,
            'password' => $hashedPassword,
            'role' => $role
        ]);
        return $this->conn->lastInsertId();
    }

    public function login($email, $password)
    {
        $stmt = $this->conn->prepare("SELECT * FROM users WHERE email = :email AND password = :password");
        $stmt->execute([
            'email' => $email,
            'password' => md5($password)
        ]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($user) {
            if ($user['status'] === 'suspended') {
                return false;
            }
            $this->id = $user['id'];
            $this->username = $user['username'];
            $this->email = $user['email'];
            $this->password = $user['password'];
            $this->role = $user['role'];
            $this->isApproved = $user['is_approved'];
            $this->status = $user['status'];
            return true;
        }
        return false;
    }

    public function approveTeacher($teacherId)
    {
        $stmt = $this->conn->prepare("UPDATE users SET is_approved = 1 WHERE id = :tid");
        $stmt->execute(['tid' => $teacherId]);
    }

    public function updateStatus($userId, $status)
    {
        $stmt = $this->conn->prepare("UPDATE users SET status = :status WHERE id = :uid");
        $stmt->execute(['status' => $status, 'uid' => $userId]);
    }
}
