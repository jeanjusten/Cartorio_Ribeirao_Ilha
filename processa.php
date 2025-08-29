<?php
// Import PHPMailer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Attach all required Files
require 'PHPMailer/Exception.php';
require 'PHPMailer/PHPMailer.php';
require 'PHPMailer/SMTP.php';

// Form Settings
$destinatario = "jean.cjm@hotmail.com"; //"cartorioribeirao@cartorioribeirao.com.br"
$assunto = "Nova Inscrição - Casamento Coletivo";

// SMTP Gmail
$smtpHost = "smtp.gmail.com";
$smtpPorta = 587;
$smtpUsuario = "sistema.cartorioribeirao@gmail.com";   
$smtpSenha = "sbts rwyh yqrl zrwq"; // senha de app gerada no Gmail

// Form Data
$nome       = $_POST['nome'] ?? '';
$endereco   = $_POST['endereco'] ?? '';
$identidade = $_POST['identidade'] ?? '';
$cpf        = $_POST['cpf'] ?? '';
$telefone   = $_POST['telefone'] ?? '';
$whatsapp   = $_POST['whatsapp'] ?? '';
$regime     = $_POST['regime'] ?? '';
$agendamento= $_POST['agendamento'] ?? '';

// Função que salva arquivos com validação de tipo
function salvarArquivo($campo) {
    $tiposPermitidos = ['image/jpeg','image/png','application/pdf'];
    if (isset($_FILES[$campo]) && $_FILES[$campo]['error'] == 0) {
        if (!in_array($_FILES[$campo]['type'], $tiposPermitidos)) {
            die("Erro: tipo de arquivo não permitido para $campo.");
        }
        if (!is_dir("uploads")) mkdir("uploads", 0777, true);
        $nomeArquivo = basename($_FILES[$campo]['name']);
        $caminho = "uploads/" . time() . "_" . $nomeArquivo;
        if (move_uploaded_file($_FILES[$campo]['tmp_name'], $caminho)) {
            return $caminho;
        }
    }
    return null;
}

// Salva arquivos individuais
$docIdentidadeCpf = salvarArquivo("doc_identidade_cpf");
$docResidencia    = salvarArquivo("doc_residencia");
$docCertidao      = salvarArquivo("doc_certidao");

// Salva arquivos das testemunhas
$docTestemunhas = [];
if (isset($_FILES['doc_testemunhas'])) {
    foreach ($_FILES['doc_testemunhas']['tmp_name'] as $key => $tmp_name) {
        if ($_FILES['doc_testemunhas']['error'][$key] == 0) {
            $nomeArquivo = basename($_FILES['doc_testemunhas']['name'][$key]);
            $caminho = "uploads/" . time() . "_" . $nomeArquivo;
            move_uploaded_file($tmp_name, $caminho);
            $docTestemunhas[] = $caminho;
        }
    }
}

// Mensagem de e-mail
$mensagem = "
Olá Daniel! 
Você tem uma nova inscrição no Casamento Coletivo através do site:

Nome: $nome
Endereço: $endereco
Identidade: $identidade
CPF: $cpf
Telefone: $telefone
WhatsApp: $whatsapp
Regime de Bens: $regime
Horário Agendado: $agendamento

Todos os documentos estão anexados a este e-mail.
";

// Envia com PHPMailer
$mail = new PHPMailer(true);
$mail->CharSet = "UTF-8";
$mail->Encoding = "base64";

try {
    $mail->isSMTP();
    $mail->Host = $smtpHost;
    $mail->SMTPAuth = true;
    $mail->Username = $smtpUsuario;
    $mail->Password = $smtpSenha;
    $mail->SMTPSecure = 'tls';
    $mail->Port = $smtpPorta;

    $mail->setFrom($smtpUsuario, 'Casamento Coletivo');
    $mail->addAddress($destinatario);
    $mail->Subject = $assunto;
    $mail->Body    = $mensagem;

    // Anexa arquivos
    if ($docIdentidadeCpf) $mail->addAttachment($docIdentidadeCpf);
    if ($docResidencia) $mail->addAttachment($docResidencia);
    if ($docCertidao) $mail->addAttachment($docCertidao);
    foreach ($docTestemunhas as $arquivo) {
        $mail->addAttachment($arquivo);
    }

    $mail->send();
    header("Location: obrigado.html");
    exit();
    //echo "<h2>Inscrição enviada com sucesso!</h2>";
    //echo "<p>Obrigado, $nome! Seu formulário foi recebido!</p>";
    //echo "<p>Não esqueça de comparecer ao Cartório no horário agendado para concluir a habilitação.</p>";

} catch (Exception $e) {
    echo "<h2>Erro ao enviar inscrição</h2>";
    echo "<p>Detalhes do erro: {$mail->ErrorInfo}</p>";
}
?>
