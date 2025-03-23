<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Chỉnh sửa danh mục</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
        <h2>Chỉnh sửa danh mục</h2>
        <form method="POST" action="/webbanhang/category/edit/<?= $category->id ?>">
            <div class="form-group">
                <label for="name">Tên danh mục</label>
                <input type="text" class="form-control" id="name" name="name" value="<?= $category->name ?>" required>
            </div>
            <div class="form-group">
                <label for="description">Mô tả</label>
                <textarea class="form-control" id="description" name="description"><?= $category->description ?></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Cập nhật</button>
            <a href="index.php" class="btn btn-secondary">Hủy</a>
        </form>
    </div>
</body>
</html>
