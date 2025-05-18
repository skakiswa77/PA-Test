<?php
$plan = isset($_GET['plan']) ? $_GET['plan'] : '';
$employees = isset($_GET['employees']) ? (int)$_GET['employees'] : 0;

$pageTitle = "Finaliser votre abonnement";
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $pageTitle ?> | Business Care</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow">
                    <div class="card-header bg-primary text-white">
                        <h1 class="h3 mb-0">Finaliser votre abonnement</h1>
                    </div>
                    <div class="card-body text-center">
                        <h2 class="h4 mb-4">MERCI DE BIEN VOULOIR FINALISER VOTRE ABONNEMENT DANS BUSINESS CARE</h2>
                        <p class="lead mb-4">Vous avez choisi le forfait <strong><?= htmlspecialchars(ucfirst($plan)) ?></strong> pour <strong><?= $employees ?></strong> employés.</p>
                        
                        <div id="paypal-button-container" class="my-4"></div>
                        
                        <p><small class="text-muted">En procédant au paiement, vous acceptez nos conditions d'utilisation et notre politique de confidentialité.</small></p>
                    </div>
                </div>
            </div>
        </div>
    </div>

  
    <script src="https://www.paypal.com/sdk/js?client-id=Aew7SKsxkDyhGNdyWGhJSizFsfqPYVmqHX2feSx-nIMah3CwX60uzYQ3uJF4P9O7uMp_IjbILnISPSiS&currency=EUR"></script>
    <script>
        paypal.Buttons({ 
            createOrder: function(data, actions) {  
                return actions.order.create({
                    purchase_units: [{
                        amount: {
                            value: '0.10'
                        }
                    }]
                });
            },
            onApprove: function(data, actions) {
                return actions.order.capture().then(function(details) {
                    alert("Transaction OK : " + details.payer.name.given_name);
                   
                    window.location.href = "index.php?page=payment_success";
                });
            },
            onError: function(err) {
                console.error('Payment Error:', err);
                alert("Paiement échoué ! " + err.message);
            } 
        }).render('#paypal-button-container');
    </script>
</body>
</html>