<?php include 'app/views/shares/header.php'; ?>
<h1>Danh sách danh mục</h1>

<a href="/PROJECTBANHANG/Category/add" class="btn btn-success mb-3">Thêm danh mục mới</a>

<table class="table table-bordered table-striped">
    <thead>
        <tr>
            <th>ID</th>
            <th>Tên danh mục</th>
            <th>Mô tả</th>
            <th>Hành động</th>
        </tr>
    </thead>
    <tbody>
        <?php if (!empty($categories)): ?>
            <?php foreach ($categories as $category): ?>
                <tr>
                    <td><?php echo $category->id; ?></td>
                    <td><?php echo htmlspecialchars($category->name, ENT_QUOTES, 'UTF-8'); ?></td>
                    <td><?php echo htmlspecialchars($category->description, ENT_QUOTES, 'UTF-8'); ?></td>
                    <td>
                        <a href="/PROJECTBANHANG/Category/edit/<?php echo $category->id; ?>" class="btn btn-sm btn-warning">Sửa</a>
                        <a href="/PROJECTBANHANG/Category/delete/<?php echo $category->id; ?>"
                           onclick="return confirm('Bạn có chắc chắn muốn xóa?');"
                           class="btn btn-sm btn-danger">Xóa</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr><td colspan="4">Không có danh mục nào.</td></tr>
        <?php endif; ?>
    </tbody>
</table>

<?php include 'app/views/shares/footer.php'; ?>
