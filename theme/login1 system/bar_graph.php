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
$sql = "SELECT Franchise_Name, COUNT(*) AS total FROM user_client GROUP BY Franchise_Name";
$result = $connection->query($sql);

if (!$result) {
    die("Invalid query: " . $connection->error);
}

$data = array();
while($row = $result->fetch_assoc()) {
    $data[$row['Franchise_Name']] = $row['total'];
}

$connection->close();

// Create image instance
$image = imagecreatetruecolor(400, 300);

// Set background color
$bg = imagecolorallocate($image, 255, 255, 255);
imagefill($image, 0, 0, $bg);

// Set color for the bars
$bar_color = imagecolorallocate($image, 0, 0, 255);

// Set font color
$font_color = imagecolorallocate($image, 0, 0, 0);

// Set the padding and margin for the graph
$padding = 20;
$margin = 40;

// Set the width and height for each bar
$bar_width = 30;
$bar_height = 200;

// Set the x and y coordinates for the first bar
$x = $margin;
$y = 280;

// Draw each bar on the graph
foreach ($data as $franchise_name => $num_users) {
    // Calculate the height of the bar
    $height = ($num_users / max($data)) * $bar_height;

    // Draw the bar
    imagefilledrectangle($image, $x, $y - $height, $x + $bar_width, $y, $bar_color);

    // Draw the label for the bar
    $label = "$franchise_name\n($num_users)";
    $label_width = imagefontwidth(5) * strlen($label);
    imagestring($image, 5, $x + ($bar_width / 2) - ($label_width / 2), $y + $padding, $label, $font_color);

    // Move to the next bar
    $x += $bar_width + $padding;
}

// Display the graph on the web page
header("Content-type: image/png");
imagepng($image);
imagedestroy($image);
?>
