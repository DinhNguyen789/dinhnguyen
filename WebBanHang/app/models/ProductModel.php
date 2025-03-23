<?php
class ProductModel
{
    private $conn;
    private $table_name = "product";

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function getProductsByCategory($category_name)
{
    // Chuẩn hóa category_name
    $category_name = trim($category_name);
    $category_name = strtolower($category_name);
    
    $query = "SELECT p.id, p.name, p.description, p.price, p.image, c.name AS category_name 
              FROM " . $this->table_name . " p 
              LEFT JOIN category c ON p.category_id = c.id 
              WHERE LOWER(c.name) = :category_name";
    $stmt = $this->conn->prepare($query);
    $stmt->bindValue(":category_name", $category_name);
    $stmt->execute();
    
    $products = $stmt->fetchAll(PDO::FETCH_OBJ);
    if (empty($products)) {
        echo "<p class='text-danger'>No products found for category: $category_name</p>";
    }
    
    return $products;
}

    public function getAllProducts()
    {
        $query = "SELECT p.id, p.name, p.description, p.price, p.image, c.name AS category_name 
                  FROM " . $this->table_name . " p 
                  LEFT JOIN category c ON p.category_id = c.id";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public function getProductById($id)
    {
        $query = "SELECT * FROM " . $this->table_name . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_OBJ);
    }

    public function addProduct($name, $description, $price, $category_id, $image)
    {
        $errors = [];
        if (empty($name)) {
            $errors['name'] = 'Tên sản phẩm không được để trống';
        }
        if (empty($description)) {
            $errors['description'] = 'Mô tả không được để trống';
        }
        if (!is_numeric($price) || $price < 0) {
            $errors['price'] = 'Giá sản phẩm không hợp lệ';
        }
        if (count($errors) > 0) {
            return $errors;
        }

        $query = "INSERT INTO " . $this->table_name . " (name, description, price, category_id, image) 
                  VALUES (:name, :description, :price, :category_id, :image)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':name', htmlspecialchars(strip_tags($name)));
        $stmt->bindParam(':description', htmlspecialchars(strip_tags($description)));
        $stmt->bindParam(':price', htmlspecialchars(strip_tags($price)));
        $stmt->bindParam(':category_id', htmlspecialchars(strip_tags($category_id)));
        $stmt->bindParam(':image', htmlspecialchars(strip_tags($image)));

        return $stmt->execute();
    }

    public function updateProduct($id, $name, $description, $price, $category_id, $image)
    {
        $query = "UPDATE " . $this->table_name . " SET name=:name, description=:description, price=:price, 
                  category_id=:category_id, image=:image WHERE id=:id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':name', htmlspecialchars(strip_tags($name)));
        $stmt->bindParam(':description', htmlspecialchars(strip_tags($description)));
        $stmt->bindParam(':price', htmlspecialchars(strip_tags($price)));
        $stmt->bindParam(':category_id', htmlspecialchars(strip_tags($category_id)));
        $stmt->bindParam(':image', htmlspecialchars(strip_tags($image)));

        return $stmt->execute();
    }

    public function deleteProduct($id)
    {
        $query = "DELETE FROM " . $this->table_name . " WHERE id=:id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }
}
?>