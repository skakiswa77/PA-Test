<?php
session_start();


require_once __DIR__ . '/../config/config.php';


$orderId = isset($_GET['order_id']) ? $_GET['order_id'] : '';


$pageTitle = "Paiement confirmé";


include_once __DIR__ . '/../includes/header.php';
?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-body text-center p-5">
                    <div class="mb-4">
                        <i class="fas fa-check-circle text-success fa-5x"></i>
                    </div>
                    <h1 class="h3 mb-3">Paiement confirmé</h1>
                    <p class="lead mb-4">Nous vous remercions pour votre abonnement à Business Care.</p>
                    
                    <?php if (!empty($orderId)): ?>
                        <p>Référence de votre commande : <strong><?= htmlspecialchars($orderId) ?></strong></p>
                    <?php endif; ?>
                    
                    <hr class="my-4">
                    
                    <p>Un email de confirmation a été envoyé à votre adresse email.</p>
                    <p>Vous pouvez maintenant accéder à tous les services inclus dans votre abonnement.</p>
                    
                    <div class="mt-4">
                        <a href="<?= APP_URL ?>/index.php?page=dashboard" class="btn btn-primary btn-lg">Accéder à mon espace</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php

include_once __DIR__ . '/../includes/footer.php';
?>