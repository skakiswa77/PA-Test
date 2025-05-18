<?php
session_start();


require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../utils/database.php';
require_once __DIR__ . '/../utils/helpers.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $companyName = trim($_POST['company_name'] ?? '');
    $companyEmail = trim($_POST['company_email'] ?? '');
    $companyPhone = trim($_POST['company_phone'] ?? '');
    $contactName = trim($_POST['contact_name'] ?? '');
    $contactPosition = trim($_POST['contact_position'] ?? '');
    $employeeCount = (int)($_POST['employee_count'] ?? 0);
    $plan = trim($_POST['plan'] ?? '');
    $message = trim($_POST['message'] ?? '');
    $services = $_POST['services'] ?? [];


    $errors = [];

    if (empty($companyName)) {
        $errors[] = 'Le nom de l\'entreprise est requis.';
    }

    if (empty($companyEmail)) {
        $errors[] = 'L\'email de l\'entreprise est requis.';
    } elseif (!filter_var($companyEmail, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'L\'email de l\'entreprise est invalide.';
    }

    if (empty($contactName)) {
        $errors[] = 'Le nom du contact est requis.';
    }

    if ($employeeCount <= 0) {
        $errors[] = 'Le nombre d\'employés doit être un nombre positif.';
    }

    if (empty($plan)) {
        $errors[] = 'Veuillez sélectionner un forfait.';
    }

    if (empty($services)) {
        $errors[] = 'Veuillez sélectionner au moins un service.';
    }


    if (empty($errors)) {
        $db = Database::getInstance();
        
        try {

            $quoteId = $db->insert('quotes', [
                'company_name' => $companyName,
                'company_email' => $companyEmail,
                'company_phone' => $companyPhone,
                'contact_name' => $contactName,
                'contact_position' => $contactPosition,
                'employee_count' => $employeeCount,
                'plan' => $plan,
                'message' => $message,
                'status' => 'pending',
                'created_at' => date('Y-m-d H:i:s')
            ]);
            
     
            foreach ($services as $service) {
                $db->insert('quote_services', [
                    'quote_id' => $quoteId,
                    'service_name' => $service,
                    'created_at' => date('Y-m-d H:i:s')
                ]);
            }
            
  
            if (function_exists('logAction')) {
                logAction('quote_request', 'Nouvelle demande de devis de ' . $companyName);
            }
            
            // Envoyer un email de confirmation au client
            // Note: Cette partie devrait être configurée selon votre système d'envoi d'emails
            /*
            $to = $companyEmail;
            $subject = "Votre demande de devis chez Business Care";
            $message = "Bonjour {$contactName},\n\n";
            $message .= "Nous avons bien reçu votre demande de devis. Notre équipe commerciale vous contactera dans les plus brefs délais.\n\n";
            $message .= "Cordialement,\n";
            $message .= "L'équipe Business Care";
            $headers = "From: contact@businesscare.fr";
            
            mail($to, $subject, $message, $headers);
            */
            
            // Afficher un message de succès
            setAlert('Votre demande de devis a été envoyée avec succès. Notre équipe commerciale vous contactera sous 24h.', 'success');
            

            header("Location: " . APP_URL . "../index.php?page=quote_confirmation");
            exit;
            
        } catch (Exception $e) {

            setAlert('Une erreur est survenue lors de l\'envoi de votre demande. Veuillez réessayer ou nous contacter directement.', 'danger');
            

            $_SESSION['quote_form_data'] = $_POST;
            

            header("Location: " . APP_URL . "../index.php?page=quote");
            exit;
        }
    } else {

        setAlert(implode('<br>', $errors), 'danger');
        

        $_SESSION['quote_form_data'] = $_POST;
        
        header("Location: " . APP_URL . "../index.php?page=quote");
        exit;
    }
} else {
    header("Location: " . APP_URL . "../index.php?page=quote");
    exit;
}