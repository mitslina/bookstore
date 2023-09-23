<?php
require_once('./lib/db_login.php');

if (isset($_GET['id'])) {
    $isbn = test_input($_GET['id']); // Mendapatkan ISBN dari query string

    // TODO: Tulis dan eksekusi query untuk mengambil informasi buku berdasarkan ISBN
    $query = "SELECT b.isbn AS ISBN, b.author AS Author, b.title AS Title, b.price AS Price, c.review AS Review, d.name AS Category FROM books b
              LEFT JOIN book_reviews c ON b.isbn = c.isbn
              LEFT JOIN categories d ON b.categoryid = d.categoryid
              WHERE b.isbn = '$isbn'";
    
    $result = $db->query($query);

    if (!$result) {
        die("Could not query the database: <br />" . $db->error);
    }else{
            $row = $result->fetch_object();
            if($row) {
                $isbn = $row->ISBN;
                $title = $row->Title;
                $category = $row->Category;
                $author = $row->Author;
                $price = $row->Price;
                $review = $row->Review;
             }else{
            echo "Book Not Found.";
        }
    }

    // Handle konfirmasi penambahan review
    if (isset($_POST['add_review'])) {
        $review = test_input($_POST['review']);
        
        if ($review == '') {
            $error_review = "Review is required";
        } else {
            // TODO: Simpan ulasan ke dalam basis data menggunakan query INSERT
            $review = $db->real_escape_string($review);
           // Cek apakah review sudah ada di basis data
           $check_query = "SELECT * FROM book_reviews WHERE isbn = '$isbn'";
           $check_result = $db->query($check_query);

           if ($check_result->num_rows > 0) {
               // Jika review sudah ada, lakukan UPDATE
               $update_query = "UPDATE book_reviews SET review='$review' WHERE isbn='$isbn'";
               $update_result = $db->query($update_query);
               
               if ($update_result) {
                   // Redirect kembali ke halaman detail buku setelah berhasil mengupdate ulasan
                   header("Location: book_detail.php?id=$isbn");
                   $db->close();
                   exit; // Hentikan eksekusi kode setelah melakukan redirect
               } else {
                   echo "Failed to update review.";
               }
           } else {
               // Jika review belum ada, lakukan INSERT
               $insert_query = "INSERT INTO book_reviews (isbn, review) VALUES ('$isbn', '$review')";
               $insert_result = $db->query($insert_query);
               
               if ($insert_result) {
                   // Redirect kembali ke halaman detail buku setelah berhasil menambahkan ulasan
                   header("Location: book_detail.php?id=$isbn");
                   $db->close();
                   exit; // Hentikan eksekusi kode setelah melakukan redirect
               } else {
                   echo "Failed to add review.";
               }
           }
       }
    } elseif (isset($_POST['back'])) {
        // Redirect kembali ke halaman CRUD jika pengguna membatalkan penambahan review
        header('Location: search_book.php');
        $db->close();
        exit;
    }
}
?>

<?php include('./header.php'); ?>
<div class="card mt-5">
    <div class="card-header">Book Details</div>
    <div class="card-body">
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
            <tr>
                <th>Review</th>
                <td><?= $review; ?></td>
            </tr>
            <tr>
                <th>Add Review</th>
                <td>
                <form action="<?= htmlspecialchars($_SERVER['PHP_SELF']) . '?id=' . $isbn; ?>" method="POST">
                        <textarea name="review" id="review" rows="3" style="width: 100%;"><?php if(isset($review)) {echo $review;} ?></textarea>
                        <div class="error"><?php if (isset($error_review)) echo $error_review ?></div>
                        <button type="submit" class="btn btn-danger" name="add_review">Add Review</button>
                        <button type="submit" class="btn btn-secondary" name="back">Back</button>
                    </form>
                </td>
            </tr>
        </table>
    </div>
</div>
<?php include('./footer.php'); ?>

