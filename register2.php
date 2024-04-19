<?php
    include "../config/config.php";
    include "../classes/Database.php";

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $request_body = file_get_contents('php://input');
        $data = json_decode($request_body, true);

        try {

            $data['username'] = htmlspecialchars($data['username']);
            $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);

            $database = new Database("INSERT INTO users (username, password) VALUES (:username, :password)");

            $database->bind_all_Param($data);

            $database->execute();

            unset($database);

            echo json_encode(array('success' => true, 'message' => "Registration success"));
        } catch(Exception $e){
            $errorMessage = $e->getMessage();

            if (str_contains($errorMessage, 'SQLSTATE[23000]')) {
                http_response_code(409);
            } else {
                http_response_code(500);
            }
        } finally {
            unset($database);
        }
    }
?>