<?php

function mostrarTabela()
{
    $usuarios = [
        [
            'id' => 1,
            'nome' => 'João',
            'email' => 'joao@email.com'
        ],
        [
            'id' => 2,
            'nome' => 'Maria',
            'email' => 'maria@email.com'
        ],
        [
            'id' => 3,
            'nome' => 'Carlos',
            'email' => 'carlos@email.com'
        ]
    ];

    echo '<table>';

    echo '<thead>';
    echo '<tr>';
    echo '<th>ID</th>';
    echo '<th>Nome</th>';
    echo '<th>E-mail</th>';
    echo '</tr>';
    echo '</thead>';

    echo '<tbody>';

    foreach ($usuarios as $usuario) {
        echo '<tr>';
        echo '<td>' . $usuario['id'] . '</td>';
        echo '<td>' . $usuario['nome'] . '</td>';
        echo '<td>' . $usuario['email'] . '</td>';
        echo '</tr>';
    }

    echo '</tbody>';

    echo '</table>';
}
