<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/chart.js@3.7.0/dist/chart.min.js"></script>
    <style>
        body {
            margin: 50px;
        }

        h1 {
            text-align: center;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            text-align: left;
            padding: 8px;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #f2f2f2;
        }

        tr:hover {
            background-color: #f5f5f5;
        }

        .btn {
            margin-right: 5px;
        }

        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
        }

        .btn-primary:hover {
            background-color: #0069d9;
            border-color: #0062cc;
        }

        .btn-danger {
            background-color: #dc3545;
            border-color: #dc3545;
        }

        .btn-danger:hover {
            background-color: #c82333;
            border-color: #bd2130;
        }
    </style>
</head>
<body>

    <h1><b>List of Clients</b></h1>
    <br>
    <table class="table">
        <thead>
			<tr>
				<th>ID</th>
				<th>Name</th>
				<th>Email</th>
                <th>Payment Status</th>
                <th>Franchise_Name</th>
                <th>Action</th>
			</tr>
		</thead>

        <tbody>
            <?php
            $servername = "localhost";
			$username = "root";
			$password = "";
			$database = "user_db";

			// Create connection
			$connection = new mysqli($servername, $username, $password, $database);

            // Check connection
			if ($connection->connect_error) {
				die("Connection failed: " . $connection->connect_error);
			}

            // read all row from database table
			$sql = "SELECT * FROM user_client";
			$result = $connection->query($sql);

            if (!$result) {
				die("Invalid query: " . $connection->error);
			}

            // read data of each row
			while($row = $result->fetch_assoc()) {
                echo "<tr>
                    <td>" . $row["id"] . "</td>
                    <td>" . $row["name"] . "</td>
                    <td>" . $row["email"] . "</td>
                    <td>" . $row["payment"] . "</td>
                    <td>" . $row["Franchise_Name"] . "</td>

                    <td>
                        <a class='btn btn-primary btn-sm' href='update'>Update</a>
                        <a class='btn btn-danger btn-sm' href='delete'>Delete</a>
                    </td>
                </tr>";
            }
            $connection->close();
            ?>
            <?php
$servername = "localhost";
$username = "root";
$password = "";
$database = "user_db";

// Create connection
$connection = new mysqli($servername, $username, $password, $database);

// Check connection
if ($connection->connect_error) {
    die("Connection failed: " . $connection->connect_error);
}

// read all row from database table
$sql = "SELECT * FROM user_client";
$result = $connection->query($sql);

if (!$result) {
    die("Invalid query: " . $connection->error);
}

// read data of each row and calculate royalty fees for each franchise
$total_clients = 0;
$franchise_data = array();
while($row = $result->fetch_assoc()) {
    $franchise_name = $row['Franchise_Name'];
    if (!isset($franchise_data[$franchise_name])) {
        $franchise_data[$franchise_name] = array(
            'count' => 0,
            'fees' => 0
        );
    }
    $franchise_data[$franchise_name]['count']++;
    $total_clients++;

}

// calculate royalty fees for each franchise and display the output
echo "<table class='table'>";
echo "<center><h1><br><br><b>Royalty Fees</b></h1><br></center>";
echo "<thead><tr><th>Franchise</th><th>Number of Clients</th><th>Royalty Fees [₹]</th></tr></thead>";
echo "<tbody>";
foreach ($franchise_data as $franchise_name => $data) {
    $count = $data['count'];
    $fees = $count * 50; // 10% of ₹5000
    $franchise_data[$franchise_name]['fees'] = $fees;
    echo "<tr><td>$franchise_name</td><td>$count</td><td>$fees</td></tr>";
}
echo "</tbody></table>";

$connection->close();
?>

        </tbody>
    </table>
    <p><br><br><br></p>
    <p style="text-align:center;"><h1><b>Bar Graph</b><h1></p>
    <canvas id="myChart" height="5" width="15"></canvas>
</body>
<script>
    var franchiseData = <?php
        $servername = "localhost";
        $username = "root";
        $password = "";
        $database = "user_db";

        $connection = new mysqli($servername, $username, $password, $database);

        if ($connection->connect_error) {
            die("Connection failed: " . $connection->connect_error);
        }

        $sql = "SELECT Franchise_Name, COUNT(*) as count FROM user_client GROUP BY Franchise_Name";
        $result = $connection->query($sql);

        if (!$result) {
            die("Invalid query: " . $connection->error);
        }

        $data = array();
        while($row = $result->fetch_assoc()) {
            $data[$row['Franchise_Name']] = $row['count'];
        }

        echo json_encode($data);
    ?>;

    var ctx = document.getElementById('myChart').getContext('2d');
    var myChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: Object.keys(franchiseData),
            datasets: [{
                label: 'Number of Clients',
                data: Object.values(franchiseData),
                backgroundColor: 'rgba(54, 162, 235, 0.2)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 3
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
</script>
</html>