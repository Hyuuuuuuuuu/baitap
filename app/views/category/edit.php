<?php include 'app/views/shares/header.php'; ?>
<h1>Chỉnh sửa danh mục</h1>

<?php if (!empty($error)): ?>
<div class="alert alert-danger">
    <ul>
        <li><?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?></li>
    </ul>
</div>
<?php endif; ?>

<form method="POST" action="/PROJECTBANHANG/Category/update" onsubmit="return validateCategoryForm();">
    <input type="hidden" name="id" value="<?php echo $category->id; ?>">

    <div class="form-group">
        <label for="name">Tên danh mục:</label>
        <input type="text" id="name" name="name" class="form-control"
               value="<?php echo htmlspecialchars($category->name, ENT_QUOTES, 'UTF-8'); ?>" required>
    </div>

    <div class="form-group">
        <label for="description">Mô tả:</label>
        <textarea id="description" name="description" class="form-control"><?php echo htmlspecialchars($category->description, ENT_QUOTES, 'UTF-8'); ?></textarea>
    </div>

    <button type="submit" class="btn btn-primary">Cập nhật danh mục</button>
</form>

<a href="/PROJECTBANHANG/Category/index" class="b
