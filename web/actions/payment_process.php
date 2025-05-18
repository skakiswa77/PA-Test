<?php
session_start();


require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../utils/database.php';
require_once __DIR__ . '/../utils/helpers.php';


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
 
    $jsonData = file_get_contents('php://input');
    $paymentData = json_decode($jsonData, true);
    
   
    if ($paymentData && json_last_error() === JSON_ERROR_NONE) {
        
        $orderId = $paymentData['orderId'] ?? '';
        $plan = $paymentData['plan'] ?? '';
        $employees = $paymentData['employees'] ?? 0;
        $amount = $paymentData['amount'] ?? 0;
        $status = $paymentData['status'] ?? '';
        
        if (!empty($orderId) && !empty($plan) && $status === 'COMPLETED') {
            try {
                $db = Database::getInstance();
                
               
                $paymentId = $db->insert('payments', [
                    'order_id' => $orderId,
                    'user_id' => $_SESSION['user_id'] ?? null,
                    'plan' => $plan,
                    'employees' => $employees,
                    'amount' => $amount,
                    'status' => $status,
                    'payment_method' => 'paypal',
                    'payment_date' => date('Y-m-d H:i:s'),
                    'created_at' => date('Y-m-d H:i:s')
                ]);
                
                
                if (isset($_SESSION['user_id'])) {
                    
                    $startDate = date('Y-m-d');
                    $endDate = date('Y-m-d', strtotime('+1 year'));
                    
                   
                    $existingSubscription = $db->query(
                        "SELECT * FROM subscriptions WHERE user_id = ? LIMIT 1",
                        [$_SESSION['user_id']],
                        true
                    );
                    
                    if ($existingSubscription) {
                       
                        $db->update('subscriptions', 
                            [
                                'plan' => $plan,
                                'employees' => $employees,
                                'start_date' => $startDate,
                                'end_date' => $endDate,
                                'is_active' => 1,
                                'updated_at' => date('Y-m-d H:i:s')
                            ], 
                            'user_id = ?', 
                            [$_SESSION['user_id']]
                        );
                    } else {
                        
                        $db->insert('subscriptions', [
                            'user_id' => $_SESSION['user_id'],
                            'plan' => $plan,
                            'employees' => $employees,
                            'start_date' => $startDate,
                            'end_date' => $endDate,
                            'is_active' => 1,
                            'created_at' => date('Y-m-d H:i:s')
                        ]);
                    }
                }
                
                
                if (function_exists('logAction')) {
                    logAction('payment', 'Paiement réussi pour le forfait ' . $plan, $_SESSION['user_id'] ?? null);
                }
                
                
                header('Content-Type: application/json');
                echo json_encode(['success' => true, 'message' => 'Paiement enregistré avec succès']);
                exit;
                
            } catch (Exception $e) {
                
                header('Content-Type: application/json');
                echo json_encode(['success' => false, 'message' => 'Erreur lors de l\'enregistrement du paiement: ' . $e->getMessage()]);
                exit;
            }
        } else {
           
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'Données de paiement incomplètes ou paiement non complété']);
            exit;
        }
    } else {
        
        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'message' => 'Données JSON invalides']);
        exit;
    }
} else {
   
    header('HTTP/1.1 405 Method Not Allowed');
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'Méthode non autorisée']);
    exit;
}