<?php
// TODO 1: Lakukan koneksi dengan database
require_once('./lib/db_login.php');

// Variable untuk menyimpan data input pengguna
$isbn = $title = $author = $category = $price = "";
$error_isbn = $error_title = $error_author = $error_category = $error_price = "";
$valid = true;

// TODO 2: Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validasi ISBN
    $isbn = test_input($_POST['isbn']);
    if ($isbn == '') {
        $error_isbn = "ISBN is required";
        $valid = false;
    } elseif (!preg_match("/^[0-9\-]*$/", $isbn)) {
        $error_isbn = "ISBN should contain only digits and hyphens";
        $valid = false;
    }

    // Validasi Title
    $title = test_input($_POST['title']);
    if ($title == '') {
        $error_title = "Title is required";
        $valid = false;
    } elseif (!preg_match("/^[a-zA-Z0-9 ]*$/", $title)) {
        $error_title = "Only letters, numbers, and white space allowed";
        $valid = false;
    }

    // Validasi Author
    $author = test_input($_POST['author']);
    if ($author == '') {
        $error_author = "Author is required";
        $valid = false;
    } elseif (!preg_match("/^[a-zA-Z,.\s]*$/", $author)) {
        $error_author = "Only letters, commas, and periods are allowed";
        $valid = false;
    }

    // Validasi Price
    $price = $_POST['price'];
    if ($price == '') {
        $error_price = "Price is required";
        $valid = false;
    } elseif (!preg_match("/^[0-9,.]*$/", $price)) {
        $error_price = "Only numbers, commas, and periods are allowed";
        $valid = false;
    }

    // Validasi Category
    $category = $_POST['category'];
    if ($category == '' || $category == 'none') {
        $error_category = "Category is required";
        $valid = false;
    }

    // Jika data valid, tambahkan buku ke database
    if ($valid) {
        // TODO 3: Insert data buku ke dalam database
        $insert_query = "INSERT INTO books (isbn, title, categoryid, author, price) VALUES ('$isbn', '$title', '$category', '$author', '$price')";
        $insert_result = $db->query($insert_query);

        if (!$insert_result) {
            die("Could not insert book into the database: <br />" . $db->error);
        } else {
            // Redirect ke halaman CRUD setelah penambahan berhasil
            header('Location: crud.php');
        }
    }
}
?>

<?php include('./header.php'); ?>
<div class="card mt-5">
    <div class="card-header">Add Book Data</div>
    <div class="card-body">
        <form action="<?= htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST" autocomplete="on">
            <div class="form-group">
                <label for="isbn">ISBN:</label>
                <input type="text" class="form-control" id="isbn" name="isbn" value="<?= $isbn; ?>">
                <div class="error"><?php if (isset($error_isbn)) echo $error_isbn; ?></div>
            </div>
            <div class="form-group">
                <label for="title">Title:</label>
                <input type="text" class="form-control" id="title" name="title" value="<?= $title; ?>">
                <div class="error"><?php if (isset($error_title)) echo $error_title; ?></div>
            </div>
            <div class="form-group">
                <label for="category">Category:</label>
                <select name="category" id="category" class="form-control" required>
                    <option value="none">--Select a category--</option>
                    <option value="1" <?php if ($category == "1") echo 'selected'; ?>>Fiksi</option>
                    <option value="2" <?php if ($category == "2") echo 'selected'; ?>>Romance</option>
                    <option value="3" <?php if ($category == "3") echo 'selected'; ?>>Thriller</option>
                    <option value="4" <?php if ($category == "4") echo 'selected'; ?>>Horror</option>
                    <option value="5" <?php if ($category == "5") echo 'selected'; ?>>Slice of Life</option>
                    <option value="6" <?php if ($category == "6") echo 'selected'; ?>>Biography</option>
                    <option value="7" <?php if ($category == "7") echo 'selected'; ?>>Novel</option>
                    <option value="8" <?php if ($category == "8") echo 'selected'; ?>>Buku Ilmiah</option>
                </select>
                <div class="error"><?php if (isset($error_category)) echo $error_category; ?></div>
            </div>
            <div class="form-group">
                <label for="author">Author:</label>
                <input type="text" class="form-control" id="author" name="author" value="<?= $author; ?>">
                <div class="error"><?php if (isset($error_author)) echo $error_author; ?></div>
            </div>
            <div class="form-group">
                <label for="price">Price:</label>
                <input type="text" class="form-control" id="price" name="price" value="<?= $price; ?>">
                <div class="error"><?php if (isset($error_price)) echo $error_price; ?></div>
            </div>
            <br>
            <button type="submit" class="btn btn-primary" name="submit">Add Book</button>
        </form>
    </div>
</div>
<?php include('./footer.php'); ?>
