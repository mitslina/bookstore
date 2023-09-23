<?php
// TODO 1: Lakukan koneksi dengan database
require_once('./lib/db_login.php');

if (isset($_GET['id'])) {
    $isbn = test_input($_GET['id']); // Mendapatkan ISBN dari query string

    // TODO 2: Tulis dan eksekusi query untuk mengambil informasi buku berdasarkan ISBN
    $query = "SELECT * FROM books WHERE isbn='" . $isbn . "'";
    $result = $db->query($query);

    if (!$result) {
        die("Could not query the database: <br />" . $db->error);
    }

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        $title = $row['title'];
        $author = $row['author'];
        $category = $row['categoryid'];
        $price = $row['price'];
    } else {
        // ISBN tidak valid, tampilkan pesan kesalahan
        echo "Invalid ISBN.";
        exit();
    }

    // TODO 3: Handle konfirmasi penghapusan
    if (isset($_POST['confirm'])) {
        // Hapus buku dari database
        $delete_query = "DELETE FROM books WHERE isbn='" . $isbn . "'";
        $delete_result = $db->query($delete_query);

        if (!$delete_result) {
            die("Could not delete the book: <br />" . $db->error);
        } else {
            // Redirect kembali ke halaman CRUD setelah penghapusan berhasil
            header('Location: crud.php');
        }
    } elseif (isset($_POST['cancel'])) {
        // Redirect kembali ke halaman CRUD jika pengguna membatalkan penghapusan
        header('Location: crud.php');
    }
}
?>

<?php include('./header.php'); ?>
<div class="card mt-5">
    <div class="card-header">Confirm Delete Book</div>
    <div class="card-body">
        <p>Are you sure you want to delete the following book?</p>
        <table class="table">
            <tr>
                <th>ISBN</th>
                <td><?= $isbn; ?></td>
            </tr>
            <tr>
                <th>Title</th>
                <td><?= $title; ?></td>
            </tr>
            <tr>
                <th>Author</th>
                <td><?= $author; ?></td>
            </tr>
            <tr>
                <th>Category</th>
                <td><?= $category; ?></td>
            </tr>
            <tr>
                <th>Price</th>
                <td><?= $price; ?></td>
            </tr>
        </table>
        <form action="<?= htmlspecialchars($_SERVER['PHP_SELF']) . '?id=' . $isbn; ?>" method="POST">
            <button type="submit" class="btn btn-danger" name="confirm">Confirm Delete</button>
            <button type="submit" class="btn btn-secondary" name="cancel">Cancel</button>
        </form>
    </div>
</div>
<?php include('./footer.php'); ?>
