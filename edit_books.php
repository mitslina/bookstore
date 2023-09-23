<?php
// TODO 1: Lakukan koneksi dengan database
require_once('./lib/db_login.php');

// TODO 2: Buat variabel $id yang diambil dari query string parameter
$id = test_input($_GET['id']); //mendapatkan customerid yang dilewatkan ke url

// Memeriksa apakah user belum menekan tombol submit
// TODO 3: Tulislah dan eksekusi query untuk mengambil informasi customer berdasarkan id
if (!isset($_POST["submit"])) {
    $query = "SELECT * FROM books WHERE isbn='" . $id . "'";
    // TODO 4: Jika valid, update data pada database dengan mengeksekusi query yang sesuai
    $result = $db->query($query);
    if (!$result) {
        die("Could not query the database: <br />" . $db->error);
    } else {
        while ($row = $result->fetch_object()) {
            $isbn = $row->isbn;
            $title = $row->title;
            $category = $row->categoryid;
            $author = $row->author;
            $price = $row->price;
        }
    }
} else {
    $valid = TRUE; //flag validasi
    $isbn = test_input($_POST['isbn']);
    if ($isbn == '') {
        $error_isbn = "ISBN is required";
        $valid = FALSE;
    } elseif (!preg_match("/^[0-9\-]*$/", $isbn)) {
        $error_isbn = "ISBN should contain only digits and hyphens";
        $valid = FALSE;
    }

    $title = test_input($_POST['title']);
    if ($title == '') {
        $error_title = "Title is required";
        $valid = FALSE;
    } elseif (!preg_match("/^[a-zA-Z0-9 ]*$/", $title)) {
        $error_title = "Only letters, numbers, and white space allowed";
        $valid = FALSE;
    }

    $author = test_input($_POST['author']);
    if ($author == '') {
        $error_author = "Author is required";
        $valid = FALSE;
    } elseif (!preg_match("/^[a-zA-Z,.\s]*$/", $author)) {
        $error_author = "Only letters, commas, and periods are allowed";
        $valid = FALSE;
    }

    $price = $_POST['price'];
    if ($price == '') {
        $error_price = "Price is required";
        $valid = FALSE;
    } elseif (!preg_match("/^[0-9,.]*$/", $price)) {
        $error_price = "Only numbers, commas, and periods are allowed";
        $valid = FALSE;
    }

    $category = $_POST['category'];
    if ($category == '' || $category == 'none') {
        $error_category = "Category is required";
        $valid = FALSE;
    }

    //update data into database
    if ($valid) {
        //Assign a query
        $query = "UPDATE books SET title='" . $title . "', categoryid='" . $category . "', author='" . $author . "', price='" . $price . "' WHERE isbn='" . $isbn . "'";
        //Execute the query
        $result = $db->query($query);
        if (!$result) {
            die("Could not query the database: <br />" . $db->error . '<br>Query:' . $query);
        } else {
            $db->close();
            header('Location: crud.php');
        }
    }
}

?>

<?php include('./header.php') ?>
<br>
<div class="card mt-4">
    <div class="card-header">Edit Books Data</div>
    <div class="card-body">
        <form action="<?= htmlspecialchars($_SERVER['PHP_SELF']) . '?id=' . $id ?>" method="POST" autocomplete="on">
            <div class="form-group">
                <label for="isbn">ISBN:</label>
                <input type="text" class="form-control" id="isbn" name="isbn" value="<?= $isbn; ?>">
                <div class="error"><?php if (isset($error_isbn)) echo $error_isbn ?></div>
            </div>
            <div class="form-group">
                <label for="title">Title:</label>
                <input type="text" class="form-control" id="title" name="title" value="<?= $title; ?>">
                <div class="error"><?php if (isset($error_title)) echo $error_title ?></div>
            </div>
            <div class="form-group">
                <label for="category">Category:</label>
                <select name="category" id="category" class="form-control" required>
                    <option value="none" <?php if (!isset($category)) echo 'selected' ?>>--Select a category--</option>
                    <option value="1" <?php if (isset($category) && $category == "1") echo 'selected' ?>>Fiksi</option>
                    <option value="2" <?php if (isset($category) && $category == "2") echo 'selected' ?>>Romance</option>
                    <option value="3" <?php if (isset($category) && $category == "3") echo 'selected' ?>>Thriller</option>
                    <option value="4" <?php if (isset($category) && $category == "4") echo 'selected' ?>>Horror</option>
                    <option value="5" <?php if (isset($category) && $category == "5") echo 'selected' ?>>Slice of Life</option>
                    <option value="6" <?php if (isset($category) && $category == "6") echo 'selected' ?>>Biography</option>
                    <option value="7" <?php if (isset($category) && $category == "7") echo 'selected' ?>>Novel</option>
                    <option value="8" <?php if (isset($category) && $category == "8") echo 'selected' ?>>Buku Ilmiah</option>
                </select>
                <div class="error"><?php if (isset($error_category)) echo $error_category ?></div>
            </div>
            <div class="form-group">
                <label for="author">Author:</label>
                <input type="text" class="form-control" id="author" name="author" value="<?= $author; ?>">
                <div class="error"><?php if (isset($error_author)) echo $error_author ?></div>
            </div>
            <div class="form-group">
                <label for="name">Price:</label>
                <input type="text" class="form-control" id="price" name="price" value="<?= $price; ?>">
                <div class="error"><?php if (isset($error_price)) echo $error_price ?></div>
            </div>
            <br>
            <button type="submit" class="btn btn-primary" name="submit" value="submit">Submit</button>
            <a href="crud.php" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</div>
<?php include('./footer.php') ?>
<?php
$db->close();
?>