<?php
include("config.php");

// Fetch customer → mechanic ratings
$sql1 = "SELECT cr.rating_id, cr.rating, cr.review, 
                c.full_name AS customer, 
                m.full_name AS mechanic
         FROM customer_rating cr
         JOIN customer c ON cr.customer_id = c.customer_id
         JOIN mechanic m ON cr.mechanic_id = m.mechanic_id
         ORDER BY cr.rating_id DESC";

$res1 = $conn->query($sql1);

// Fetch mechanic → customer ratings
$sql2 = "SELECT mr.rating_id, mr.rating, mr.review, 
                m.full_name AS mechanic, 
                c.full_name AS customer
         FROM mechanic_rating mr
         JOIN mechanic m ON mr.mechanic_id = m.mechanic_id
         JOIN customer c ON mr.customer_id = c.customer_id
         ORDER BY mr.rating_id DESC";

$res2 = $conn->query($sql2);

// Function: Generate stars
function stars($rating) {
    $output = "";
    for ($i = 1; $i <= 5; $i++) {
        $output .= ($i <= $rating) ? "★" : "☆";
    }
    return "<div class='stars' style='color:#ff9800;font-size:20px;'>$output</div>";
}

// Output Start
if (($res1->num_rows + $res2->num_rows) == 0) {
    echo "<p style='text-align:center;color:#777;'>No ratings found.</p>";
    exit;
}

// --- Show customer → mechanic reviews ---
while ($row = $res1->fetch_assoc()) {
    echo "
    <div class='review-box'>
        <h4>{$row['customer']} → {$row['mechanic']}</h4>
        " . stars($row['rating']) . "
        <p>".htmlspecialchars($row['review'])."</p>
        <div class='action-buttons'>
            <button class='flag-btn'>Flag</button>
            <button class='delete-btn' data-id='{$row['rating_id']}' data-type='customer'>Delete</button>
        </div>
    </div>
    ";
}

// --- Show mechanic → customer reviews ---
while ($row = $res2->fetch_assoc()) {
    echo "
    <div class='review-box'>
        <h4>{$row['mechanic']} → {$row['customer']}</h4>
        " . stars($row['rating']) . "
        <p>".htmlspecialchars($row['review'])."</p>
        <div class='action-buttons'>
            <button class='flag-btn'>Flag</button>
            <button class='delete-btn' data-id='{$row['rating_id']}' data-type='mechanic'>Delete</button>
        </div>
    </div>
    ";
}
?>
