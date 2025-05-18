<?php
session_start();
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../utils/database.php';
require_once __DIR__ . '/../utils/helpers.php';
require_once __DIR__ . '/../utils/auth.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'provider') {
    setAlert('Accès non autorisé. Veuillez vous connecter en tant que prestataire.', 'danger');
    redirect(APP_URL . '/index.php?page=login');
    exit;
}

$db = Database::getInstance();
$pageTitle = "Gestion des Factures";

try {
    $invoices = $db->query(
        "SELECT pi.*, c.name as company_name, 
        (SELECT SUM(ps.total_amount) FROM provider_services ps WHERE ps.provider_invoice_id = pi.id) as total_services_amount
         FROM provider_invoices pi
         LEFT JOIN companies c ON pi.company_id = c.id
         WHERE pi.provider_id = ?
         ORDER BY pi.issue_date DESC",
        [$_SESSION['user_id']]
    );
} catch (Exception $e) {
    $invoices = [];
    setAlert('Erreur lors du chargement des factures: ' . $e->getMessage(), 'danger');
}

$totalEarnings = 0;
$pendingAmount = 0;
$paidAmount = 0;

foreach ($invoices as $invoice) {
    $totalEarnings += $invoice['total_amount'];
    
    if ($invoice['status'] == 'paid') {
        $paidAmount += $invoice['total_amount'];
    } else {
        $pendingAmount += $invoice['total_amount'];
    }
}

include_once __DIR__ . '/../includes/header.php';
?>

<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">Gestion des Factures</h1>
        <a href="invoice_create.php" class="btn btn-primary">
            <i class="fas fa-plus me-1"></i> Créer une facture
        </a>
    </div>

    <div class="row mb-4">
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Revenus totaux</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?= number_format($totalEarnings, 2, ',', ' ') ?> €</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-euro-sign fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Montant payé</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?= number_format($paidAmount, 2, ',', ' ') ?> €</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Montant en attente</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?= number_format($pendingAmount, 2, ',', ' ') ?> €</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clock fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">Mes Factures</h6>
            <div>
                <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="fas fa-filter me-1"></i> Filtrer
                </button>
                <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                    <li><a class="dropdown-item" href="?status=all">Toutes</a></li>
                    <li><a class="dropdown-item" href="?status=pending">En attente</a></li>
                    <li><a class="dropdown-item" href="?status=paid">Payées</a></li>
                    <li><a class="dropdown-item" href="?status=overdue">En retard</a></li>
                </ul>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Numéro</th>
                            <th>Entreprise</th>
                            <th>Date d'émission</th>
                            <th>Date d'échéance</th>
                            <th>Montant</th>
                            <th>Statut</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($invoices)): ?>
                        <tr>
                            <td colspan="7" class="text-center">Aucune facture trouvée</td>
                        </tr>
                        <?php else: ?>
                        
                        <?php foreach ($invoices as $invoice): 
                                            $statusClass = '';
                                            $statusText = '';
                                            
                                            switch ($invoice['status']) {
                                                case 'pending':
                                                    $statusClass = 'warning';
                                                    $statusText = 'En attente';
                                                    break;
                                                case 'paid':
                                                    $statusClass = 'success';
                                                    $statusText = 'Payée';
                                                    break;
                                                case 'overdue':
                                                    $statusClass = 'danger';
                                                    $statusText = 'En retard';
                                                    break;
                                                default:
                                                    $statusClass = 'secondary';
                                                    $statusText = 'Inconnue';
                                            }
                                        ?>
                                        <tr>
                                            <td><?= htmlspecialchars($invoice['invoice_number']) ?></td>
                                            <td><?= htmlspecialchars($invoice['company_name']) ?></td>
                                            <td><?= date('d/m/Y', strtotime($invoice['issue_date'])) ?></td>
                                            <td><?= date('d/m/Y', strtotime($invoice['due_date'])) ?></td>
                                            <td><?= number_format($invoice['total_amount'], 2, ',', ' ') ?> €</td>
                                            <td><span class="badge bg-<?= $statusClass ?>"><?= $statusText ?></span></td>
                                            <td>
                                                <div class="btn-group">
                                                    <a href="invoice_view.php?id=<?= $invoice['id'] ?>" class="btn btn-sm btn-primary">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <a href="invoice_download.php?id=<?= $invoice['id'] ?>" class="btn btn-sm btn-info">
                                                        <i class="fas fa-download"></i>
                                                    </a>
                                                    <?php if ($invoice['status'] != 'paid'): ?>
                                                    <a href="invoice_edit.php?id=<?= $invoice['id'] ?>" class="btn btn-sm btn-warning">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <?php endif; ?>
                                                </div>
                                            </td>
                                        </tr>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            
               
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Prochaine facturation automatique</h6>
                    </div>
                    <div class="card-body">
                        <p class="mb-3">À la fin de chaque mois, Business Care génère automatiquement vos factures basées sur les prestations que vous avez réalisées.</p>
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1">
                                <h5>Prochaine génération de facture : <?= date('d/m/Y', strtotime('last day of this month')) ?></h5>
                                <div class="progress" style="height: 10px;">
                                    <?php 
                                        $currentDay = date('j');
                                        $daysInMonth = date('t');
                                        $percentage = ($currentDay / $daysInMonth) * 100;
                                    ?>
                                    <div class="progress-bar bg-primary" role="progressbar" style="width: <?= $percentage ?>%;" aria-valuenow="<?= $percentage ?>" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                                <small class="text-muted"><?= $currentDay ?>/<?= $daysInMonth ?> jours</small>
                            </div>
                            <div class="ms-3">
                                <a href="invoice_preview.php" class="btn btn-outline-primary">Prévisualiser</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <?php
            include_once __DIR__ . '/../includes/footer.php';
            ?>