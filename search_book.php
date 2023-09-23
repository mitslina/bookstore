<?php include('./header.php') ?>

<div class="card mt-5">
    <div class="card-header">Book List</div>
    <div class="card-body">
                <!-- Filter Form -->
                <div class="card mt-3">
            <div class="card-header">Filter Books</div>
            <div class="card-body">
                <form method="POST" autocomplete="on" action="">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="category">Category:</label>
                                <select name="category" id="category" class="form-control">
                                    <option value="" selected disable >--Kategori--</option>
                                    <option value="Fiksi" <?php if(isset($_POST['category']) && $_POST['category']=="Fiksi") echo 'selected=true'; ?>>Fiksi</option>
                                    <option value="Romance" <?php if(isset($_POST['category']) && $_POST['category']=="Romance") echo 'selected=true'; ?>>Romance</option>
                                    <option value="Thriller" <?php if(isset($_POST['category']) && $_POST['category']=="Thriller") echo 'selected=true'; ?>>Thriller</option>
                                    <option value="Horror" <?php if(isset($_POST['category']) && $_POST['category']=="Horror") echo 'selected=true'; ?>>Horror</option>
                                    <option value="Slice of Life" <?php if(isset($_POST['category']) && $_POST['category']=="Slice of Life") echo 'selected=true'; ?>>Slice of Life</option>
                                    <option value="Biography" <?php if(isset($_POST['category']) && $_POST['category']=="Biography") echo 'selected=true'; ?>>Biography</option>
                                    <option value="Novel" <?php if(isset($_POST['category']) && $_POST['category']=="Novel") echo 'selected=true'; ?>>Novel</option>
                                    <option value="Buku Ilmiah" <?php if(isset($_POST['category']) && $_POST['category']=="Buku Ilmiah") echo 'selected=true'; ?>>Buku Ilmiah</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="isbn">ISBN:</label>
                                <input type="text" name="isbn" id="isbn" class="form-control" 
                                value="<?php if(isset($_POST['isbn'])) {echo ($_POST['isbn']);} ?>">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="author">Author:</label>
                                <input type="text" name="author" id="author" class="form-control" 
                                value="<?php if(isset($_POST['author'])) {echo ($_POST['author']);}?>">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="title">Title:</label>
                                <input type="text" name="title" id="title" class="form-control" 
                                value="<?php if(isset($_POST['title'])) {echo ($_POST['title']);}?>">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="min_price">Min Price:</label>
                                <input type="number" name="min_price" id="min_price" class="form-control" 
                                value="<?php if(isset($_POST['min_price'])) {echo ($_POST['min_price']);} ?>">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="max_price">Max Price:</label>
                                <input type="number" name="max_price" id="max_price" class="form-control" 
                                value="<?php if(isset($_POST['max_price'])) {echo ($_POST['max_price']);} ?>">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>&nbsp;</label><br>
                                <!-- <button type="submit" class="btn btn-primary" action="">Filter</button> -->
                                <button type="submit" class="btn btn-primary" action="">Filter</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <br />
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

            // TODO 2: Tulis dan eksekusi query ke database dengan filter
            $query = "SELECT b.isbn AS ISBN, b.author AS Author, b.title AS Title, b.price AS Price, c.name AS Category 
            FROM books b, categories c 
            WHERE b.categoryid = c.categoryid";

            // Filter berdasarkan kategori
            if (!empty($_POST['category'])) {
                $category = $db->real_escape_string($_POST['category']);
                $query .= " AND c.name = '$category'";
            }

            // Filter berdasarkan ISBN
            if (!empty($_POST['isbn'])) {
                $isbn = $db->real_escape_string($_POST['isbn']);
                $query .= " AND b.isbn LIKE '%$isbn%'";
            }

            // Filter berdasarkan author
            if (!empty($_POST['author'])) {
                $author = $db->real_escape_string($_POST['author']);
                $query .= " AND b.author LIKE '%$author%'";
            }

            // Filter berdasarkan judul buku
            if (!empty($_POST['title'])) {
                $title = $db->real_escape_string($_POST['title']);
                $query .= " AND b.title LIKE '%$title%'";
            }

            // Filter berdasarkan rentang harga
            if (!empty($_POST['min_price'])) {
                $minPrice = (float)$_POST['min_price'];
                $query .= " AND b.price >= $minPrice";
            }

            if (!empty($_POST['max_price'])) {
                $maxPrice = (float)$_POST['max_price'];
                $query .= " AND b.price <= $maxPrice";
            }

            $query .= " ORDER BY b.isbn";

            $result = $db->query($query);
            if (!$result) {
                die("Could not connect to database: <br />" . $db->error . "<br>Query: " . $query);
            }

            // TODO 3: Parsing data yang diterima dari database ke halaman HTML/PHP
            $i = 1;
            while ($row = $result->fetch_object()) {
                echo '<tr>';
                echo '<td>' . $row->ISBN . '</td>';
                echo '<td>' . $row->Title . '</td>';
                echo '<td>' . $row->Category . '</td>';
                echo '<td>' . $row->Author . '</td>';
                echo '<td>' . $row->Price . '</td>';
                echo '<td>
                        <a class="btn btn-warning btn-sm" href="book_detail.php?id=' . $row->ISBN . '">View Detail</a>&nbsp;&nbsp;
                      </td>';
                echo '</tr>';
                $i++;
            }
            echo '</table>';
            echo '<br />';
            echo 'Total Rows = ' . $result->num_rows;

            // TODO 4: Lakukan dealokasi variabel $result
            $result->free();

            // TODO 5: Tutup koneksi dengan database
            $db->close();
            ?>
        </table>
    </div>
</div>

<?php include('./footer.php') ?>
