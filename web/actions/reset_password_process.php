<?php
session_start();


require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../utils/database.php';
require_once __DIR__ . '/../utils/helpers.php';


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    $token = trim($_POST['token'] ?? $_GET['token'] ?? '');
    $email = trim($_POST['email'] ?? $_GET['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirmPassword = $_POST['confirm_password'] ?? '';
    
    
    $errors = [];
    
    if (empty($token) || empty($email)) {
        $errors[] = 'Les informations de réinitialisation sont incomplètes. Veuillez utiliser le lien envoyé par email.';
    }
    
    if (empty($password)) {
        $errors[] = 'Le mot de passe est requis.';
    } elseif (strlen($password) < 8) {
        $errors[] = 'Le mot de passe doit contenir au moins 8 caractères.';
    }
    
    if ($password !== $confirmPassword) {
        $errors[] = 'Les mots de passe ne correspondent pas.';
    }
    
    
    if (empty($errors)) {
        $db = Database::getInstance();
        
        try {
          
            $resetRequest = $db->query(
                "SELECT * FROM password_resets 
                 WHERE token = ? AND email = ? AND expiration > NOW() 
                 LIMIT 1",
                [$token, $email],
                true
            );
            
            if ($resetRequest) {
                
                
                
                $passwordHash = password_hash($password, PASSWORD_DEFAULT);
                
                
                $updated = $db->update(
                    'users', 
                    [
                        'password' => $passwordHash,
                        'updated_at' => date('Y-m-d H:i:s')
                    ], 
                    'email = ?', 
                    [$email]
                );
                
                if ($updated) {
                    
                    $db->delete('password_resets', 'email = ?', [$email]);
                    
                    
                    if (function_exists('logAction')) {
                        logAction('password_reset', 'Réinitialisation du mot de passe pour ' . $email);
                    }
                    
                    
                    setAlert('Votre mot de passe a été réinitialisé avec succès. Vous pouvez maintenant vous connecter avec votre nouveau mot de passe.', 'success');
                    
                   
                    header("Location: " . APP_URL . "/index.php?page=login");
                    exit;
                } else {
                    
                    $errors[] = 'Aucun utilisateur trouvé avec cette adresse email.';
                }
            } else {
                
                $errors[] = 'Ce lien de réinitialisation est invalide ou a expiré. Veuillez faire une nouvelle demande.';
            }
        } catch (Exception $e) {
            
            $errors[] = 'Une erreur est survenue lors de la réinitialisation du mot de passe. Veuillez réessayer.';
            
           
            error_log('Reset password error: ' . $e->getMessage());
        }
    }
    
    
    if (!empty($errors)) {
        setAlert(implode('<br>', $errors), 'danger');
        
        
        header("Location: " . APP_URL . "/index.php?page=reset_password&token=" . urlencode($token) . "&email=" . urlencode($email));
        exit;
    }
} else {
   
    setAlert('Accès non autorisé. Veuillez utiliser le formulaire de réinitialisation.', 'danger');
    header("Location: " . APP_URL . "/index.php?page=login");
    exit;
}