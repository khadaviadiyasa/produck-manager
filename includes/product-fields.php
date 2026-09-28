<label for="name">Nama Produk</label>
<input id="name" name="name" value="<?= e($product["name"] ?? ($_POST["name"] ?? "")) ?>" required minlength="3">

<label for="category">Kategori</label>
<input id="category" name="category" value="<?= e($product["category"] ?? ($_POST["category"] ?? "")) ?>">

<label for="price">Harga</label>
<input id="price" type="number" name="price" step="0.01" min="0.01" value="<?= e($product["price"] ?? ($_POST["price"] ?? "")) ?>" required>

<label for="stock">Stok</label>
<input id="stock" type="number" name="stock" min="0" value="<?= e($product["stock"] ?? ($_POST["stock"] ?? "")) ?>" required>
