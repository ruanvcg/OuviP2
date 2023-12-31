<?php
    include_once("db_connect.php"); // Include the database connection script

    $postdata = file_get_contents("php://input"); // Retrieve data from the request body

    if(isset($postdata) && !empty($postdata))
    {
        $request = json_decode($postdata); // Decode the JSON data from the request
        
        $nome = trim($request->nome); // Sanitize and extract the 'nome' field
        $cpf = trim($request->cpf); // Sanitize and extract the 'cpf' field
        $email = trim($request->email); // Sanitize and extract the 'email' field
        $telefone = trim($request->telefone); // Sanitize and extract the 'telefone' field
        $senha = sha1($request->senha); // Hash the 'senha' field


        // Check if a user with the same hashed CPF already exists
        $checkSql = "SELECT COUNT(*) AS count FROM usuarios WHERE cpf = ?";
        $checkStmt = $mysqli->prepare($checkSql);
        $checkStmt->bind_param("s", $cpf);
        $checkStmt->execute();
        $checkResult = $checkStmt->get_result();
        $checkRow = $checkResult->fetch_assoc();

        if ($checkRow['count'] > 0) {
            $data = array('message' => 'failed', 'error' => 'CPF already exists');
            echo json_encode($data);
            exit();
        }



        // Check if a user with the same email already exists
        $checkEmailSql = "SELECT COUNT(*) AS count FROM usuarios WHERE email = ?";
        $checkEmailStmt = $mysqli->prepare($checkEmailSql);
        $checkEmailStmt->bind_param("s", $email);
        $checkEmailStmt->execute();
        $checkEmailResult = $checkEmailStmt->get_result();
        $checkEmailRow = $checkEmailResult->fetch_assoc();

        if ($checkEmailRow['count'] > 0) {
            $data = array('message' => 'failed', 'error' => 'Email already exists');
            echo json_encode($data);
            exit();
        }

        // Insert user data into the 'usuarios' table
        $insertSql = "INSERT INTO usuarios(
            nome, 
            cpf, 
            email, 
            telefone, 
            senha
        ) VALUES (?, ?, ?, ?, ?)";

        $stmt = $mysqli->prepare($insertSql);
        $stmt->bind_param("sssss", $nome, $cpf, $email, $telefone, $senha);

        if ($stmt->execute()){
            $data = array('message' => 'success');
            echo json_encode($data);
        } else{
            $data = array('message' => 'failed');
            echo json_encode($data);
        }

        $stmt->close(); // Close the prepared statement
    }    
?>
