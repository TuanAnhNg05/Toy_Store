<?php
require_once 'config/db.php';

class ProductModel
{
    private $db;

    public function __construct()
    {
        $this->db = new Database();
    }

    // 1. Lấy sản phẩm nổi bật
    public function getFeaturedProducts()
    {
        $sql = "SELECT * FROM products ORDER BY id DESC LIMIT 8";
        return $this->db->getAll($sql);
    }

    // 2. Lấy chi tiết 1 sản phẩm
    public function getProductById($id)
    {
        $id = intval($id);
        $sql = "SELECT * FROM products WHERE id = $id";
        return $this->db->getOne($sql);
    }

    // 3. Tìm kiếm sản phẩm
    public function searchProducts($keyword)
    {
        $keyword = $this->db->conn->real_escape_string($keyword);
        $sql = "SELECT * FROM products WHERE name LIKE '%$keyword%'";
        return $this->db->getAll($sql);
    }

    // 4. Đếm tổng số sản phẩm
    public function countAll()
    {
        $sql = "SELECT COUNT(*) as total FROM products";
        $result = $this->db->getOne($sql);
        return $result ? $result['total'] : 0;
    }

    // 5. Lấy sản phẩm có phân trang
    public function getProductsPagination($offset, $limit)
    {
        $sql = "SELECT * FROM products ORDER BY id DESC LIMIT $offset, $limit";
        return $this->db->getAll($sql);
    }

    // 6. Lấy sản phẩm theo danh mục
    public function getProductsByCategory($cat_id)
    {
        $cat_id = intval($cat_id);
        $sql = "SELECT * FROM products WHERE category_id = $cat_id";
        return $this->db->getAll($sql);
    }
}
?>