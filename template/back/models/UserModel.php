<?php

// exemplo de função de banco de dados
/*

function validar($pdo, $senha, $cpf)
{

    $sql = "SELECT senha, nivel, id FROM usuarios WHERE cpf = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$cpf]);

    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($usuario && password_verify($senha, $usuario['senha'])) {

        $_SESSION['nivel'] = $usuario['nivel'];
        $_SESSION['id_usuario'] = $usuario['id'];

        if ($usuario['nivel'] == 'medico') {

            header("Location: ../views/pgMedico.php");
            exit();
        } elseif ($usuario['nivel'] == 'paciente') {

            header("Location: ../views/pgPaciente.php");
            exit();
        }
    } else {

        $_SESSION['erro'][] = "Usuário ou senha incorretos!";
    }
}

function getMedicamentoPaciente($pdo, $idPaciente)
{
    $sql = "SELECT medicamento_em_uso.id_medicamento as id, medicamento_em_uso.nome, medicamento_em_uso.dosagem, medicamento_em_uso.frequencia FROM medicamento_em_uso LEFT JOIN paciente on paciente.id = medicamento_em_uso.fk_paciente_id LEFT JOIN usuarios on paciente.fk_usuario_id=usuarios.id WHERE usuarios.id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$idPaciente]);
    return $stmt->fetchAll();
}

*/

/*
 * ==========================================================
 * FUNÇÕES DE BANCO DE DADOS
 * Cada função recebe o $pdo e faz sua query
 * ==========================================================
 */

function cadastrarUsuario($pdo, $nome, $email, $senha)
{
    $sql = "INSERT INTO usuarios (nome, email, senha) VALUES (?, ?, ?)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$nome, $email, $senha]);
}

function validarLogin($pdo, $senha, $email)
{
    $sql = "SELECT senha, nivel, id FROM usuarios WHERE email = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$email]);

    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($usuario && password_verify($senha, $usuario['senha'])) {

        $_SESSION['usuario_id'] = $usuario['id'];
        $_SESSION['usuario_nivel'] = $usuario['nivel'];
        $_SESSION['logado'] = true;

    } else {

        $_SESSION['erro'][] = "Usuário ou senha incorretos!";
    }
}

function listarUsuarios($pdo)
{
    $sql = "SELECT id, nome, email FROM usuarios ORDER BY id ASC";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    return $stmt->fetchAll();
}

function buscarUsuarioPorId($pdo, $id)
{
    $sql = "SELECT id, nome, email, nivel FROM usuarios WHERE id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function deletarUsuario($pdo, $id)
{
    $sql = "DELETE FROM usuarios WHERE id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$id]);
}

?>