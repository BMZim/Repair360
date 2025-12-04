<?php
include("db.php");

$query = $_POST["query"] ?? "";

$sql = "SELECT 
            s.service_id,
            m.mechanic_id,
            m.full_name,
            m.avatar,
            s.shop_name, 
            s.skills,
            s.expert_area,
            s.shop_location,
            s.fee,
            COALESCE(AVG(r.rating), 0) AS avg_rating
        FROM service s
        JOIN mechanic m ON s.mechanic_id = m.mechanic_id
        LEFT JOIN mechanic_rating r ON m.mechanic_id = r.mechanic_id
        WHERE s.shop_name LIKE ? OR s.skills LIKE ?
        GROUP BY m.mechanic_id, s.service_id";

$stmt = $conn->prepare($sql);

$like = "%$query%";
$stmt->bind_param("ss", $like, $like);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    echo "<p style='padding:20px;font-weight:bold'>No services found!</p>";
    exit;
}

while ($row = $result->fetch_assoc()) {
    
    $skillsArray = explode(",", $row['skills']); 
    $expertArray = explode(",", $row['expert_area']); 

    $latitude = $longitude = null;
    if (!empty($row['shop_location']) && strpos($row['shop_location'], ',') !== false) {
        list($latitude, $longitude) = explode(',', $row['shop_location']);
        $latitude  = trim($latitude);
        $longitude = trim($longitude);
    }
?>
<div class="service-card">
    <div class="card-header">
        <div class="avatar">
            <img src="../uploads/<?= htmlspecialchars($row['avatar']) ?>" alt="">
        </div>
        <div>
            <div class="name">
                <?= htmlspecialchars($row['shop_name']) ?>
                <span class="stars">
                    <?= str_repeat("★", round($row['avg_rating'])) ?>
                </span>
            </div>
            <div class="category"><?= htmlspecialchars($row['skills']) ?></div>
        </div>
    </div>

    <div class="expertise">
        <strong>Expert At:</strong>
        <ul>
            <?php foreach ($expertArray as $expert): ?>
            <li><?= htmlspecialchars(trim($expert)); ?></li>
            <?php endforeach; ?>
        </ul>
    </div>

    <div class="service-cost">
        <h3 style="color:red;">Service Cost: <?= $row['fee'] ?> ৳</h3>
    </div>

    <div class="hire">
        <button class="hire-btn" 
            onclick="openBookingModal('<?= $row['mechanic_id'] ?>','<?= $row['service_id'] ?>','<?= $row['fee'] ?>')">
            Hire Now
        </button>

        <?php if ($latitude && $longitude): ?>
        <button class="hire-btn" onclick="showLocation(<?= $latitude ?>, <?= $longitude ?>)">
            Location
        </button>
        <?php endif; ?>
    </div>

    <div class="footer">
        <img src="img/repairlogo.png" />
        Expert Mechanics by Repair360
    </div>
</div>

<?php 
}
?>
