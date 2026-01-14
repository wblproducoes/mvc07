<?php

declare(strict_types=1);

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

/**
 * Classe para envio de emails - PHPMailer 7.0.3
 * PHP 8.4+
 */
final class Mailer
{
    private PHPMailer $mail;
    
    public function __construct()
    {
        $this->mail = new PHPMailer(true);
        $this->configure();
    }
    
    private function configure(): void
    {
        try {
            // Configurações do servidor
            $this->mail->isSMTP();
            $this->mail->Host = MAIL_HOST;
            $this->mail->SMTPAuth = true;
            $this->mail->Username = MAIL_USERNAME;
            $this->mail->Password = MAIL_PASSWORD;
            $this->mail->SMTPSecure = MAIL_ENCRYPTION;
            $this->mail->Port = MAIL_PORT;
            
            // Configurações gerais
            $this->mail->CharSet = MAIL_CHARSET;
            $this->mail->SMTPDebug = MAIL_DEBUG;
            
            // Remetente padrão
            $this->mail->setFrom(MAIL_FROM_ADDRESS, MAIL_FROM_NAME);
            
            // HTML por padrão
            $this->mail->isHTML(true);
            
        } catch (Exception $e) {
            error_log("Erro ao configurar PHPMailer: " . $e->getMessage());
            throw new RuntimeException("Erro ao configurar email");
        }
    }
    
    public function to(string $email, string $name = ''): self
    {
        try {
            $this->mail->addAddress($email, $name);
        } catch (Exception $e) {
            error_log("Erro ao adicionar destinatário: " . $e->getMessage());
        }
        return $this;
    }
    
    public function cc(string $email, string $name = ''): self
    {
        try {
            $this->mail->addCC($email, $name);
        } catch (Exception $e) {
            error_log("Erro ao adicionar CC: " . $e->getMessage());
        }
        return $this;
    }
    
    public function bcc(string $email, string $name = ''): self
    {
        try {
            $this->mail->addBCC($email, $name);
        } catch (Exception $e) {
            error_log("Erro ao adicionar BCC: " . $e->getMessage());
        }
        return $this;
    }
    
    public function replyTo(string $email, string $name = ''): self
    {
        try {
            $this->mail->addReplyTo($email, $name);
        } catch (Exception $e) {
            error_log("Erro ao adicionar Reply-To: " . $e->getMessage());
        }
        return $this;
    }
    
    public function subject(string $subject): self
    {
        $this->mail->Subject = $subject;
        return $this;
    }
    
    public function body(string $body): self
    {
        $this->mail->Body = $body;
        return $this;
    }
    
    public function altBody(string $altBody): self
    {
        $this->mail->AltBody = $altBody;
        return $this;
    }
    
    public function attach(string $path, string $name = ''): self
    {
        try {
            $this->mail->addAttachment($path, $name);
        } catch (Exception $e) {
            error_log("Erro ao adicionar anexo: " . $e->getMessage());
        }
        return $this;
    }
    
    public function template(string $template, array $data = []): self
    {
        $templatePath = "app/views/emails/{$template}.twig";
        
        if (!file_exists($templatePath)) {
            throw new RuntimeException("Template de email não encontrado: {$template}");
        }
        
        // Usar Twig para renderizar template
        $loader = new \Twig\Loader\FilesystemLoader('app/views/emails');
        $twig = new \Twig\Environment($loader);
        
        $html = $twig->render("{$template}.twig", $data);
        $this->body($html);
        
        return $this;
    }
    
    public function send(): bool
    {
        try {
            return $this->mail->send();
        } catch (Exception $e) {
            error_log("Erro ao enviar email: " . $this->mail->ErrorInfo);
            return false;
        }
    }
    
    public function getError(): string
    {
        return $this->mail->ErrorInfo;
    }
    
    /**
     * Método estático para envio rápido
     */
    public static function sendQuick(
        string $to,
        string $subject,
        string $body,
        string $toName = ''
    ): bool {
        $mailer = new self();
        return $mailer
            ->to($to, $toName)
            ->subject($subject)
            ->body($body)
            ->send();
    }
    
    /**
     * Enviar email de boas-vindas
     */
    public static function sendWelcome(string $email, string $name): bool
    {
        $mailer = new self();
        return $mailer
            ->to($email, $name)
            ->subject('Bem-vindo ao ' . APP_NAME)
            ->template('welcome', [
                'name' => $name,
                'app_name' => APP_NAME,
                'base_url' => BASE_URL
            ])
            ->send();
    }
    
    /**
     * Enviar email de recuperação de senha
     */
    public static function sendPasswordReset(string $email, string $name, string $token): bool
    {
        $resetUrl = BASE_URL . '/auth/reset-password?token=' . $token;
        
        $mailer = new self();
        return $mailer
            ->to($email, $name)
            ->subject('Recuperação de Senha - ' . APP_NAME)
            ->template('password-reset', [
                'name' => $name,
                'reset_url' => $resetUrl,
                'app_name' => APP_NAME
            ])
            ->send();
    }
    
    /**
     * Enviar notificação ao administrador
     */
    public static function sendAdminNotification(string $subject, string $message): bool
    {
        // Buscar email do admin (você pode implementar isso)
        $adminEmail = 'admin@example.com';
        
        $mailer = new self();
        return $mailer
            ->to($adminEmail, 'Administrador')
            ->subject($subject)
            ->body($message)
            ->send();
    }
}
