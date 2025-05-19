<?php include 'app/views/shares/header.php'; ?>
<h1>Thêm danh mục mới</h1>

<?php if (!empty($error)): ?>
<div class="alert alert-danger">
    <ul>
        <li><?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?></li>
    </ul>
</div>
<?php endif; ?>

<form method="POST" action="/PROJECTBANHANG/Category/save" onsubmit="return validateCategoryForm();">
    <div class="form-group">
        <label for="name">Tên danh mục:</label>
        <input type="text" id="name" name="name" class="form-control" required>
    </div>

    <div class="form-group">
        <label for="description">Mô tả:</label>
        <textarea id="description" name="description" class="form-control"></textarea>
    </div>

    <button type="submit" class="btn btn-primary">Thêm danh mục</button>
</form>

<a href="/PROJECTBANHANG/Category/index" class="btn btn-secondary mt-2">Quay lại danh sách danh mục</a>

<script>
function validateCategoryForm() {
    const name = document.getElementById("name").value.trim();
    if (name === "") {
        alert("Tên danh mục không được để trống.");
        return false;
    }
    return true;
}
</script>

<?php include 'app/views/shares/footer.php'; ?>
