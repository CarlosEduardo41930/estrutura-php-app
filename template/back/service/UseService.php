<?php
// Aqui você pode colocar serviços de fora do seu projeto, como APIs externas, serviços de pagamento, etc.


/*
 * ==========================================================
 * SERVIÇOS EXTERNOS
 * Coloque aqui integrações com APIs, serviços de pagamento,
 * envio de e-mails, etc.
 * ==========================================================
 */

function enviar_email($para, $assunto, $corpo)
{
    $cabecalhos = [
        'MIME-Version: 1.0',
        'Content-type: text/html; charset=UTF-8',
        'From: Projeto <nao-responda@seudominio.com>',
    ];

    return mail($para, $assunto, $corpo, implode("\r\n", $cabecalhos));
}

?>
