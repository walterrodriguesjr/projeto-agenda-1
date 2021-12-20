<?php

session_start();

include_once "connection.php";
include_once "url.php";

$data = $_POST;

/* MODIFICAÇÕES NO BANCO */
if (!empty($data)) {

/* CRIAR CONTATO */
    if ($data["type"] === "create") {

        $name = $data["name"];
        $phone = $data["phone"];
        $observations = $data["observations"];

        $query = "INSERT INTO contacts (name, phone, observations) VALUES (:name, :phone, :observations)";

        $stmt = $conn->prepare($query);

        $stmt->bindParam(":name", $name);
        $stmt->bindParam(":phone", $phone);
        $stmt->bindParam(":observations", $observations);

        try {
            $stmt->execute();
            $_SESSION["msg"] = "Contato Criando com Sucesso!";

        } catch (PDOException $e) {
            //erro na conexão
            $error = $e->getMessage();
            echo "Erro: $error";
        }
    } else if ($data['type'] === "edit") {
        $name = $data['name'];
        $phone = $data['phone'];
        $observations = $data['observations'];
        $id = $data['id'];

        
        $query = "UPDATE contacts
                  SET name = :name, phone = :phone, observations = :observations
                  WHERE id = :id";

$stmt = $conn->prepare($query);
$stmt->bindParam(":name", $name);
$stmt->bindParam(":phone", $phone);
$stmt->bindParam(":observations", $observations);
$stmt->bindParam(":id", $id);

try {

    $stmt->execute();
    $_SESSION["msg"] = "Contato Atualizado com Sucesso!";

} catch (PDOException $e) {
    //erro na conexão
    $error = $e->getMessage();
    echo "Erro: $error";
}

}else if($data["type"] ==="delete"){
    $id = $data["id"];

    $query = "DELETE FROM contacts WHERE id = :id";

    $stmt = $conn->prepare($query);

    $stmt->bindParam(":id", $id);


    try {

        $stmt->execute();
        $_SESSION["msg"] = "Contato Removido com Sucesso!";
    
    } catch (PDOException $e) {
        //erro na conexão
        $error = $e->getMessage();
        echo "Erro: $error";
    }
}

    /* REDIRECT HOME */
    header("Location:" . $BASE_URL . "../index.php");

    /* SELEÇÃO DE DADOS */
} else {
    $id;

    if (!empty($_GET)) {
        $id = $_GET['id'];
    }
    //retorna o dado de um contato
    if (!empty($id)) {
        $query = "SELECT * FROM contacts WHERE id = :id";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(":id", $id);
        $stmt->execute();
        $contact = $stmt->fetch();
    } else {
        //retorna todos os contatos
        $contacts = [];

        $query = "SELECT * FROM contacts";

        $stmt = $conn->prepare($query);

        $stmt->execute();

        $contacts = $stmt->fetchAll();
    }

}

/* FECHAR CONEXÃO */
$conn = null;
