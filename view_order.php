<?php include('./header.php') ?>
<div class="card mt-5">
    <div class="card-header">Books Data</div>
    <div class="card-body">
    
    <!-- Form for Filtering by Date -->
    <form method="POST" class="mb-4">
        <div class="form-row">
            <div class="form-group col-md-6">
                <label for="start_date">Start Date:</label>
                <input type="date" class="form-control" id="start_date" name="start_date" value="<?php echo isset($_POST['start_date']) ? $_POST['start_date'] : ''; ?>">
            </div>
            <div class="form-group col-md-6">
                <label for="end_date">End Date:</label>
                <input type="date" class="form-control" id="end_date" name="end_date" value="<?php echo isset($_POST['end_date']) ? $_POST['end_date'] : ''; ?>">
            </div>
        </div>
        <button type="submit" class="btn btn-primary">Filter</button>
    </form>

    <table class="table table-striped">
        <tr>
            <th>Order Id</th>
            <th>Customer Name</th>
            <th>Customer Id</th>
            <th>ISBN</th>
            <th>Title</th>
            <th>Author</th>
            <th>Category Name</th>
            <th>Price</th>
            <th>Quantity</th>
            <th>Total Price</th>
            <th>Order Date</th>
        </tr>
        <?php
        
        // TODO 1: Buat koneksi dengan database
        require_once('./lib/db_login.php');

        // Initialize variables for filter dates
        $start_date = "";
        $end_date = "";

        // Check if the form is submitted
        if (isset($_POST['start_date']) && isset($_POST['end_date'])) {
            $start_date = $_POST['start_date'];
            $end_date = $_POST['end_date'];

            // Query to display filtered orders by date
            $query = "SELECT o.orderid AS OrderId, c.name AS CustomerName, o.customerid AS CustomerId, b.isbn AS ISBN, b.title AS Title, b.author AS Author, ct.name AS CategoryName, b.price AS Price, i.quantity AS Quantity, (b.price*i.quantity) AS TotalPrice, o.date AS OrderDate
                    FROM orders AS o JOIN order_items AS i JOIN
                    customers AS c JOIN categories AS ct JOIN
                    books AS b
                    ON o.orderid = i.orderid AND
                    o.customerid = c.customerid AND
                    i.isbn = b.isbn AND
                    b.categoryid = ct.categoryid
                    WHERE o.date BETWEEN '$start_date' AND '$end_date'";
        } else {
            // Query to display all orders if date filter is not used
            $query = "SELECT o.orderid AS OrderId, c.name AS CustomerName, o.customerid AS CustomerId, b.isbn AS ISBN, b.title AS Title, b.author AS Author, ct.name AS CategoryName, b.price AS Price, i.quantity AS Quantity, (b.price*i.quantity) AS TotalPrice, o.date AS OrderDate
                    FROM orders AS o JOIN order_items AS i JOIN
                    customers AS c JOIN categories AS ct JOIN
                    books AS b
                    ON o.orderid = i.orderid AND
                    o.customerid = c.customerid AND
                    i.isbn = b.isbn AND
                    b.categoryid = ct.categoryid";
        }

        $result = $db->query($query);
        if(!$result){
            die("Could not connect to database: <br />". $db->error."<br>Query: ".$query);
        }

        // Initialize variables for grouping by Order ID
        $currentOrderId = null;
        $currentCustomerId = null;
        $i = 1;
        while ($row = $result->fetch_object()){
            // TODO 6: Kelompokkan data berdasarkan Order ID dan Customer ID
            if ($currentOrderId != $row->OrderId || $currentCustomerId != $row->CustomerId) {
                if ($currentOrderId !== null) {
                    echo '</tr>';
                }
                echo '<tr>';
                echo "<td rowspan='1'>" . $row->OrderId . '</td>';
                echo "<td rowspan='1'>" . $row->CustomerName . '</td>';
                echo "<td rowspan='1'>" . $row->CustomerId . '</td>';
                $currentOrderId = $row->OrderId;
                $currentCustomerId = $row->CustomerId;
            } else {
                echo "<td></td>";
                echo "<td></td>";
                echo "<td></td>";
            }

            // TODO 7: Tampilkan detail item dalam kelompok Order ID dan Customer ID
            echo '<td>' . $row->ISBN . '</td>';
            echo '<td>' . $row->Title . '</td>';
            echo '<td>' . $row->Author . '</td>';
            echo '<td>' . $row->CategoryName . '</td>';
            echo '<td>' . $row->Price . '</td>';
            echo '<td>' . $row->Quantity . '</td>';
            echo '<td>' . $row->TotalPrice . '</td>';
            echo '<td>' . $row->OrderDate . '</td>';
            echo '</tr>';
            $i++;
        }

        ?>
        </table>
        <?php
            echo '<br />';
            echo 'Total Rows = '.$result->num_rows;
            // TODO 4: Lakukan dealokasi variabel $result
            $result->free();

            // TODO 5: Tutup koneksi dengan database
            $db->close();
            ?>

            <!-- Div untuk menempatkan tombol View Graph di tengah layar -->
            <div class="text-center">
                <a href="view_graph.php" class="btn btn-primary">View Graph</a>
            </div>
        </div>
</div>

<?php include('./footer.php') ?>
