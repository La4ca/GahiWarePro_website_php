<?php
class crudDAO {
    private $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    // User methods
    public function create($lastname, $firstname, $username, $password_hash, $email) {
        $sql = "INSERT INTO tbsignup (lastname, firstname, username, password_hash, email) 
                VALUES (:lastname, :firstname, :username, :password_hash, :email)";
        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([
                ':lastname'      => $lastname,
                ':firstname'     => $firstname,
                ':username'      => $username,
                ':password_hash' => $password_hash,
                ':email'         => $email
            ]);
            return true;
        } catch (PDOException $e) {
            return $e->getMessage();
        }
    }

    public function login($identifier, $password) {
        $sql = "SELECT * FROM tbsignup 
                WHERE username = :identifier OR email = :identifier 
                LIMIT 1";

        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([':identifier' => $identifier]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user && password_verify($password, $user['password_hash'])) {
                unset($user['password_hash']);
                return $user;
            }
            return false;
        } catch (PDOException $e) {
            return false;
        }
    }

    // Product methods
    public function getAllProducts() {
        $sql = "SELECT * FROM products WHERE status = 'active' ORDER BY created_at DESC";
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getProductsByCategory($category) {
        $sql = "SELECT * FROM products WHERE category = :category AND status = 'active'";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':category' => $category]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function searchProducts($searchTerm) {
        $sql = "SELECT * FROM products WHERE (name LIKE :search OR description LIKE :search) AND status = 'active'";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':search' => "%$searchTerm%"]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getProductById($id) {
        $sql = "SELECT * FROM products WHERE id = :id AND status = 'active'";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}