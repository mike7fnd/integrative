<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");

require 'db.php'; // Include database connection

// Get raw JSON input
$json = file_get_contents("php://input");
$data = json_decode($json, true);

// Debugging: Check if JSON is received correctly
if (!$data) {
    echo json_encode(["error" => "No JSON data received"]);
    exit();
}

// Validate input fields
if (
    isset($data["name"], $data["email"], $data["age"], $data["gender"], $data["phoneNumber"]) &&
    !empty($data["name"]) && !empty($data["email"]) && !empty($data["age"]) &&
    !empty($data["gender"]) && !empty($data["phoneNumber"])
) {
    try {
        $query = "INSERT INTO clients (name, email, age, gender, phoneNumber) 
                  VALUES (:name, :email, :age, :gender, :phoneNumber)";
        $stmt = $conn->prepare($query);

        $stmt->bindParam(":name", $data["name"]);
        $stmt->bindParam(":email", $data["email"]);
        $stmt->bindParam(":age", $data["age"], PDO::PARAM_INT);
        $stmt->bindParam(":gender", $data["gender"]);
        $stmt->bindParam(":phoneNumber", $data["phoneNumber"]);

        if ($stmt->execute()) {
            echo json_encode(["message" => "Data inserted successfully"]);
        } else {
            echo json_encode(["message" => "Error inserting data"]);
        }
    } catch (PDOException $e) {
        echo json_encode(["error" => "Database error: " . $e->getMessage()]);
    }
} else {
    echo json_encode(["message" => "Invalid input data"]);
}
?>
