<?php include('./header.php') ?>
    <div class="card mt-5">
        <div class="card-header">Books Category</div>
        <div class="card-body">
            <table class="table table-striped">
                <tr>
                    <th>Category</th>    
                    <th>ISBN</th>
                    <th>Title</th>
                    <th>Author</th>
                    <th>Price</th>
                </tr>

                <?php
                // TODO 1: Buat koneksi dengan database
                require_once('lib/db_login.php');

                // TODO 2: Tulis dan eksekusi query ke database
                $query = "SELECT c.name AS Category, b.isbn AS ISBN, b.author AS Author, b.title AS Title, b.price AS Price  
                FROM books b, categories c 
                WHERE b.categoryid = c.categoryid 
                ORDER BY c.name";
                $result = $db->query($query);
                if (!$result) {
                    die("Could not connect to database: <br />" . $db->error . "<br>Query: " . $query);
                }

                // Inisialisasi variabel untuk kategori saat ini
                $currentCategory = null;

                // TODO 3: Parsing data yang diterima dari database ke halaman web
                $i = 1;
                while ($row = $result->fetch_object()) { 
                    //Cek jika kategori sama maka dilakukan spanning kolom
                    if ($currentCategory != $row->Category) { 
                        if ($currentCategory !== null) {
                            echo "</tr>";
                        }
                        echo "<tr>";
                        echo "<td rowspan='1'>" . $row->Category . "</td>";
                        $currentCategory = $row->Category;
                    } else {
                        echo "<td></td>";
                    }

                    echo '<td>' . $row->ISBN . '</td>';
                    echo '<td>' . $row->Title . '</td>';
                    echo '<td>' . $row->Author . '</td>';
                    echo '<td>' . $row->Price . '</td>';
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
        </div>
    </div>
<?php include('./footer.php') ?>
