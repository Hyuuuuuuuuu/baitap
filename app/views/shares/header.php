<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Quản lý sản phẩm</title>
<link
href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css"
rel="stylesheet">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-light bg-light">
<a class="navbar-brand" href="#">Quản lý sản phẩm</a>
<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle 
navigation">
<span class="navbar-toggler-icon"></span>
</button>
<div class="collapse navbar-collapse" id="navbarNav">
<ul class="navbar-nav">
<li class="nav-item">
<ul class="navbar-nav ml-auto"> <!-- thêm ml-auto để đẩy giỏ hàng sang phải -->
    <li class="nav-item">
        <a class="nav-link" href="/PROJECTBANHANG/Product/">Danh sách sản phẩm</a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="/PROJECTBANHANG/Product/add">Thêm sản phẩm</a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="/PROJECTBANHANG/category/">Danh sách loại</a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="/PROJECTBANHANG/category/add">Thêm loại sản phẩm</a>
    </li>
    <!-- Nút giỏ hàng -->
    <li class="nav-item">
        <a class="nav-link btn btn-outline-success ml-lg-3" href="/PROJECTBANHANG/product/cart">
            🛒 Giỏ hàng
        </a>
    </li>
</u>
</div>
</nav>
<div class="container mt-4">
</div>
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script
src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></scrip
t>
<script
src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>