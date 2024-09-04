<?php
function getAllMembers($pdo) {
    $stmt = $pdo->prepare("SELECT * FROM member");
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getAllClasses($pdo) {
    $stmt = $pdo->prepare("SELECT * FROM classes");
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getAllBookings($pdo) {
    $stmt = $pdo->prepare("SELECT * FROM booking");
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Function to insert a new member
function insertMember($pdo, $first_name, $last_name) {
    try {
        $random_id = uniqid();
        $stmt = $pdo->prepare("INSERT INTO member (random_id, first_name, last_name) VALUES (?, ?, ?)");
        $stmt->execute([$random_id, $first_name, $last_name]);
    } catch (PDOException $e) {
        echo "Error inserting member: " . $e->getMessage();
    }
}




function insertClass($pdo, $name) {
    // Generate a random_id for the class
    $random_id = bin2hex(random_bytes(8)); // Generate a random 16-character hex string

    $stmt = $pdo->prepare("INSERT INTO classes (random_id, name) VALUES (?, ?)");
    return $stmt->execute([$random_id, $name]);
}
function generateRandomIntId($length = 16) {
    // Generate a random number with the desired length
    return str_pad(mt_rand(0, str_repeat('9', $length)), $length, '0', STR_PAD_LEFT);
}

function insertBooking($pdo, $member_first_name, $member_last_name, $class_name) {
    do {
        $random_id = generateRandomIntId(); // Generate a 16-digit random ID
        // Check if the generated ID already exists
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM booking WHERE random_id = ?");
        $stmt->execute([$random_id]);
        $exists = $stmt->fetchColumn();
    } while ($exists > 0);

    $stmt = $pdo->prepare("INSERT INTO booking (random_id, member_first_name, member_last_name, class_name) VALUES (?, ?, ?, ?)");
    return $stmt->execute([$random_id, $member_first_name, $member_last_name, $class_name]);
}




function updateMember($pdo, $id, $firstName, $lastName) {
    $stmt = $pdo->prepare("UPDATE member SET first_name = ?, last_name = ? WHERE random_id = ?");
    return $stmt->execute([$firstName, $lastName, $id]);
}


?>
