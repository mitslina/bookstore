<?php include('./header.php'); ?>
<!-- Display the Chart -->
<div class="container mt-4">
    <h3>Total Data Order Buku Tiap Kategori</h3>
    <div id="categoryChart" style="width: 800px; height: 400px;"></div>
</div>

<!-- Include Google Charts JavaScript -->
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script type="text/javascript">
    google.charts.load('current', {'packages':['corechart']});
    google.charts.setOnLoadCallback(drawChart);

    function drawChart() {
        var data = new google.visualization.DataTable();
        data.addColumn('string', 'Category');
        data.addColumn('number', 'Total Quantity');
        data.addColumn('number', 'Total Ordered');

        data.addRows([
            <?php
            require_once('./lib/db_login.php');

            $category_query = "SELECT c.name AS Category, 
                                      SUM(b.quantity) AS TotalQuantity, 
                                      IFNULL(SUM(oi.quantity), 0) AS TotalOrdered
                               FROM categories c
                               LEFT JOIN books b ON c.categoryid = b.categoryid
                               LEFT JOIN order_items oi ON b.isbn = oi.isbn
                               GROUP BY c.categoryid, c.name";
            $category_result = $db->query($category_query);

            if ($category_result->num_rows > 0) {
                while ($category_row = $category_result->fetch_assoc()) {
                    echo "['" . $category_row['Category'] . "', " . $category_row['TotalQuantity'] . ", " . $category_row['TotalOrdered'] . "],";
                }
            }

            $db->close();
            ?>
        ]);

        var options = {
            title: 'Jumlah Data Buku per Kategori dan Total Data Buku yang Di-Order',
            hAxis: {title: 'Category'},
            vAxis: {title: 'Quantity'},
            bars: 'vertical'
        };

        var chart = new google.visualization.ColumnChart(document.getElementById('categoryChart'));
        chart.draw(data, options);
    }
</script>

<!-- Bootstrap Footer -->
<footer class="footer bg-light text-center py-3">
    <div class="container">
        <?php include('./footer.php'); ?>
    </div>
</footer>
</body>
</html>
