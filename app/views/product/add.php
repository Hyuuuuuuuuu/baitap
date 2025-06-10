<?php include 'app/views/shares/header.php'; ?>
<h1>Thêm sản phẩm mới</h1>
<form id="add-product-form">
    <div class="form-group">
        <label for="name">Tên sản phẩm:</label>
        <input type="text" id="name" name="name" class="form-control" required>
    </div>
    <div class="form-group">
        <label for="description">Mô tả:</label>
        <textarea id="description" name="description" class="form-control" required></textarea>
    </div>
    <div class="form-group">
        <label for="price">Giá:</label>
        <input type="number" id="price" name="price" class="form-control" step="0.01" required>
    </div>
    <div class="form-group">
        <label for="category_id">Danh mục:</label>
        <select id="category_id" name="category_id" class="form-control" required>
            <!-- Các danh mục sẽ được tải từ API và hiển thị tại đây -->
        </select>
    </div>
    <button type="submit" class="btn btn-primary">Thêm sản phẩm</button>
</form>
<a href="/PROJECTBANHANG/Product/list" class="btn btn-secondary mt-2">Quay lại danh sách
    sản phẩm</a>
<?php include 'app/views/shares/footer.php'; ?>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        fetch('/PROJECTBANHANG/api/category')
            .then(response => response.json())
            .then(data => {
                const categorySelect = document.getElementById('category_id');
                data.forEach(category => {
                    const option = document.createElement('option');
                    option.value = category.id;
                    option.textContent = category.name;
                    categorySelect.appendChild(option);
                });
            });
        document.getElementById('add-product-form').addEventListener('submit',
            function (event) {
                event.preventDefault();
                const formData = new FormData(this);
                const jsonData = {};
                formData.forEach((value, key) => {
                    jsonData[key] = value;
                });
                fetch('/PROJECTBANHANG/api/product', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(jsonData)
                })
                    .then(response => response.json())
                    .then(data => {
                        console.log('Response:', data); // Log the response
                        if (data.success) {
                            alert('Thêm sản phẩm thành công!');
                            location.href = '/PROJECTBANHANG/Product';
                        } else {
                            if (data.errors) {
                                const errorMessages = Object.values(data.errors).join('\n');
                                alert('Lỗi:\n' + errorMessages);
                            } else {
                                alert(data.message || 'Thêm sản phẩm thất bại');
                            }
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Có lỗi xảy ra khi gửi yêu cầu');
                    });
            });
    });
</script>