<?php include 'app/views/shares/header.php'; ?>

<div class="card">
    <div class="card-header">
        <h2 class="mb-0">Thêm sản phẩm mới</h2>
    </div>
    <div class="card-body">
        <form action="/PROJECTBANHANG/Product/save" method="post" enctype="multipart/form-data" class="needs-validation" novalidate>
            <div class="mb-3">
                <label for="name" class="form-label">Tên sản phẩm:</label>
                <input type="text" class="form-control" id="name" name="name" required>
            </div>
            
            <div class="mb-3">
                <label for="description" class="form-label">Mô tả:</label>
                <textarea class="form-control" id="description" name="description" rows="3"></textarea>
            </div>
            
            <div class="mb-3">
                <label for="price" class="form-label">Giá:</label>
                <input type="number" class="form-control" id="price" name="price" required>
            </div>
            
            <div class="mb-3">
                <label for="category" class="form-label">Danh mục:</label>
                <select class="form-select" id="category" name="category_id" required>
                    <option value="">Chọn danh mục</option>
                    <?php foreach ($categories as $category): ?>
                        <option value="<?php echo $category->id; ?>">
                            <?php echo htmlspecialchars($category->name, ENT_QUOTES, 'UTF-8'); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <div class="mb-3">
                <label for="image" class="form-label">Hình ảnh:</label>
                <input type="file" class="form-control" id="image" name="image">
            </div>
            
            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                <a href="/PROJECTBANHANG/Product" class="btn btn-secondary me-md-2">Hủy</a>
                <button type="submit" class="btn btn-primary">Thêm sản phẩm</button>
            </div>
        </form>
    </div>
</div>

<?php include 'app/views/shares/footer.php'; ?>