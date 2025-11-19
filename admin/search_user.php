<?php
include("config.php");

$search = $_POST['search'] ?? '';
$search = "%$search%";

$sql = "
    SELECT customer_id AS id, full_name, email, phone, status, 'Customer' AS role
    FROM customer
    WHERE full_name LIKE ? OR phone LIKE ? OR email LIKE ? OR customer_id LIKE ?

    UNION ALL

    SELECT mechanic_id AS id, full_name, email, phone, status, 'Mechanic' AS role
    FROM mechanic
    WHERE full_name LIKE ? OR phone LIKE ? OR email LIKE ? OR mechanic_id LIKE ?

    ORDER BY full_name ASC
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("ssssssss", $search, $search, $search, $search, $search, $search, $search, $search);
$stmt->execute();
$res = $stmt->get_result();

if ($res->num_rows > 0) {

    while ($row = $res->fetch_assoc()) {
        $status = $row['status'];
        $color  = ($status === 'Verified') ? 'green' : (($status === 'Blocked') ? 'red' : 'orange');

        echo "<tr>
                <td>{$row['id']}</td>
                <td>{$row['full_name']}</td>
                <td>{$row['role']}</td>
                <td>{$row['email']}</td>
                <td>{$row['phone']}</td>

                <td style='font-weight:600;color:$color;'>$status</td>

                <td>";

        // APPROVE button
        if ($status === 'Not Verified') {
            echo "<button class='action-btn approve-btn' data-id='{$row['id']}' data-role='{$row['role']}'>Approve</button>";
        }

        // BLOCK / UNBLOCK Logic
        if ($status === 'Blocked') {
            echo "<button class='action-btn unblock-btn' data-id='{$row['id']}' data-role='{$row['role']}'>Unblock</button>";
        } else {
            echo "<button class='action-btn block-btn' data-id='{$row['id']}' data-role='{$row['role']}'>Block</button>";
        }

        echo "
                <button class='action-btn view-btn' data-id='{$row['id']}' data-role='{$row['role']}'>View</button>
                <button class='action-btn delete-btn' data-id='{$row['id']}' data-role='{$row['role']}'>Delete</button>
                </td>
              </tr>";
    }
} else {
    echo "<tr><td colspan='7' style='text-align:center;color:#999;'>No users found</td></tr>";
}
?>
