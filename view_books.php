<?php include('./header.php') ?>
<div class="card mt-5">
    <div class="card-header">Books Data</div>
    <div class="card-body">
    <a href="add_books.php" class="btn btn-primary mb-4">+ Add Books Data</a>
        <br>
        <table class="table table-striped">
            <tr>
                <th>ISBN</th>
                <th>Title</th>
                <th>Category</th>
                <th>Author</th>
                <th>Price</th>
                <th>Action</th>
            </tr>
            <?php
            
            // TODO 1: Buat koneksi dengan database
            require_once('./lib/db_login.php');

            // TODO 2: Tulis dan eksekusi query ke database
            $query = "SELECT b.isbn AS ISBN, b.author AS Author, b.title AS Title, b.price AS Price, c.name AS Category FROM books b, categories c WHERE b.categoryid = c.categoryid ORDER BY b.isbn";
            $result = $db->query($query);
            if(!$result){
                die("Could not connect to database: <br />". $db->error."<br>Query: ".$query);
            }

            // TODO 3: Parsing data yang diterima dari database ke halaman HTML/PHP
            $i = 1;
            while ($row = $result->fetch_object()){
                echo '<tr>';
                echo '<td>'.$row->ISBN.'</td>';
                echo '<td>'.$row->Title.'</td>';
                echo '<td>'.$row->Category.'</td>';
                echo '<td>'.$row->Author.'</td>';
                echo '<td>'.$row->Price.'</td>';
                echo '<td><a class="btn btn-warning btn-sm" href="edit_books.php?id='.$row->ISBN.'">Edit</a>&nbsp;&nbsp;
                        <a class="btn btn-danger btn-sm" href="confirm_delete_books.php?id='.$row->ISBN.'">Delete</a>
                        </td>';
                echo '</tr>';
                $i++;
            }
            echo '</table>';
            echo '<br />';
            echo 'Total Rows = '.$result->num_rows;
            // TODO 4: Lakukan dealokasi variabel $result
            $result->free();

            // TODO 5: Tutup koneksi dengan database
            $db->close();
            ?>
        </table>
    </div>
</div>

<?php include('./footer.php') ?>
