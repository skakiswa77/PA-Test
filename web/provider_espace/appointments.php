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
$pageTitle = "Gestion des Rendez-vous";

try {
    $appointments = $db->query(
        "SELECT a.*, u.first_name, u.last_name, u.email, c.name as company_name 
         FROM medical_appointments a
         JOIN users u ON a.user_id = u.id
         LEFT JOIN companies c ON u.company_id = c.id
         WHERE a.provider_id = ?
         ORDER BY a.appointment_datetime DESC",
        [$_SESSION['user_id']]
    );
} catch (Exception $e) {
    $appointments = [];
    setAlert('Erreur lors du chargement des rendez-vous: ' . $e->getMessage(), 'danger');
}


try {
    $availability = $db->query(
        "SELECT * FROM provider_availability 
         WHERE provider_id = ? 
         LIMIT 1",
        [$_SESSION['user_id']],
        true
    );
    
    $isAvailable = $availability ? $availability['is_available'] : true;
} catch (Exception $e) {
    $isAvailable = true;
}


$action = $_GET['action'] ?? '';

if ($action === 'toggle_availability' && isset($_POST['is_available'])) {
    $newAvailabilityStatus = $_POST['is_available'] ? 1 : 0;
    
    try {
        if ($availability) {

            $db->update(
                'provider_availability',
                ['is_available' => $newAvailabilityStatus, 'updated_at' => date('Y-m-d H:i:s')],
                'provider_id = ?',
                [$_SESSION['user_id']]
            );
        } else {

            $db->insert('provider_availability', [
                'provider_id' => $_SESSION['user_id'],
                'is_available' => $newAvailabilityStatus,
                'created_at' => date('Y-m-d H:i:s')
            ]);
        }
        
        $isAvailable = $newAvailabilityStatus;
        setAlert('Votre statut de disponibilité a été mis à jour.', 'success');
    } catch (Exception $e) {
        setAlert('Erreur lors de la mise à jour de la disponibilité: ' . $e->getMessage(), 'danger');
    }
}

include_once __DIR__ . '/../includes/header.php';
?>

<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">Gestion des Rendez-vous</h1>
        <form action="appointments.php?action=toggle_availability" method="post" class="d-flex align-items-center">
            <div class="form-check form-switch me-2">
                <input class="form-check-input" type="checkbox" id="availabilitySwitch" name="is_available" value="1" <?= $isAvailable ? 'checked' : '' ?> onchange="this.form.submit()">
                <label class="form-check-label" for="availabilitySwitch">Disponible pour les rendez-vous</label>
            </div>
            <noscript><button type="submit" class="btn btn-sm btn-primary">Mettre à jour</button></noscript>
        </form>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">Mes Rendez-vous</h6>
            <a href="availability.php" class="btn btn-sm btn-primary">
                <i class="fas fa-calendar-alt me-1"></i> Gérer mes disponibilités
            </a>
        </div>
        <div class="card-body">
            <ul class="nav nav-tabs mb-4" id="appointmentTabs" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="upcoming-tab" data-bs-toggle="tab" href="#upcoming" role="tab">À venir</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="past-tab" data-bs-toggle="tab" href="#past" role="tab">Passés</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="canceled-tab" data-bs-toggle="tab" href="#canceled" role="tab">Annulés</a>
                </li>
            </ul>
            
            <div class="tab-content" id="appointmentTabsContent">
                <div class="tab-pane fade show active" id="upcoming" role="tabpanel">
                    <?php
                    $hasUpcoming = false;
                    foreach ($appointments as $appointment) {
                        if (strtotime($appointment['appointment_datetime']) > time() && $appointment['status'] != 'cancelled') {
                            $hasUpcoming = true;
                            $date = date('d/m/Y', strtotime($appointment['appointment_datetime']));
                            $time = date('H:i', strtotime($appointment['appointment_datetime']));
                            $statusClass = $appointment['status'] == 'confirmed' ? 'success' : 'warning';
                            $statusText = $appointment['status'] == 'confirmed' ? 'Confirmé' : 'En attente';
                            ?>
                            <div class="card mb-3 border-<?= $statusClass ?>">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-9">
                                            <h5 class="card-title"><?= htmlspecialchars($appointment['first_name'] . ' ' . $appointment['last_name']) ?></h5>
                                            <p class="card-text">
                                                <i class="fas fa-building me-1"></i> <?= htmlspecialchars($appointment['company_name'] ?? 'N/A') ?><br>
                                                <i class="fas fa-calendar-day me-1"></i> <?= $date ?> à <?= $time ?><br>
                                                <i class="fas fa-comment-dots me-1"></i> <?= htmlspecialchars($appointment['notes'] ?? 'Aucune note') ?>
                                            </p>
                                        </div>
                                        <div class="col-md-3 text-md-end">
                                            <span class="badge bg-<?= $statusClass ?> mb-2"><?= $statusText ?></span>
                                            <div class="btn-group-vertical w-100">
                                                <a href="appointment_details.php?id=<?= $appointment['id'] ?>" class="btn btn-sm btn-outline-primary">Détails</a>
                                                <?php if ($appointment['status'] != 'confirmed'): ?>
                                                <a href="appointment_action.php?id=<?= $appointment['id'] ?>&action=confirm" class="btn btn-sm btn-success">Confirmer</a>
                                                <?php endif; ?>
                                                <a href="appointment_action.php?id=<?= $appointment['id'] ?>&action=cancel" class="btn btn-sm btn-outline-danger" onclick="return confirm('Êtes-vous sûr de vouloir annuler ce rendez-vous?')">Annuler</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php
                        }
                    }
                    
                    if (!$hasUpcoming) {
                        echo '<div class="text-center py-5"><p class="text-muted">Aucun rendez-vous à venir</p></div>';
                    }
                    ?>
                </div>
                <div class="tab-pane fade" id="past" role="tabpanel">
                    <?php
                    $hasPast = false;
                    foreach ($appointments as $appointment) {
                        if (strtotime($appointment['appointment_datetime']) <= time() && $appointment['status'] != 'cancelled') {
                            $hasPast = true;
                            $date = date('d/m/Y', strtotime($appointment['appointment_datetime']));
                            $time = date('H:i', strtotime($appointment['appointment_datetime']));
                            ?>
                            <div class="card mb-3">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-9">
                                            <h5 class="card-title"><?= htmlspecialchars($appointment['first_name'] . ' ' . $appointment['last_name']) ?></h5>
                                            <p class="card-text">
                                                <i class="fas fa-building me-1"></i> <?= htmlspecialchars($appointment['company_name'] ?? 'N/A') ?><br>
                                                <i class="fas fa-calendar-day me-1"></i> <?= $date ?> à <?= $time ?><br>
                                                <i class="fas fa-comment-dots me-1"></i> <?= htmlspecialchars($appointment['notes'] ?? 'Aucune note') ?>
                                            </p>
                                        </div>
                                        <div class="col-md-3 text-md-end">
                                            <div class="btn-group-vertical w-100">
                                                <a href="appointment_details.php?id=<?= $appointment['id'] ?>" class="btn btn-sm btn-outline-primary">Détails</a>
                                                <a href="appointment_report.php?id=<?= $appointment['id'] ?>" class="btn btn-sm btn-info">Compte rendu</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php
                        }
                    }
                    
                    if (!$hasPast) {
                        echo '<div class="text-center py-5"><p class="text-muted">Aucun rendez-vous passé</p></div>';
                    }
                    ?>
                </div>
                
        
                <div class="tab-pane fade" id="canceled" role="tabpanel">
                    <?php
                    $hasCanceled = false;
                    foreach ($appointments as $appointment) {
                        if ($appointment['status'] == 'cancelled') {
                            $hasCanceled = true;
                            $date = date('d/m/Y', strtotime($appointment['appointment_datetime']));
                            $time = date('H:i', strtotime($appointment['appointment_datetime']));
                            ?>
                            <div class="card mb-3 border-danger">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-9">
                                            <h5 class="card-title"><?= htmlspecialchars($appointment['first_name'] . ' ' . $appointment['last_name']) ?></h5>
                                            <p class="card-text">
                                                <i class="fas fa-building me-1"></i> <?= htmlspecialchars($appointment['company_name'] ?? 'N/A') ?><br>
                                                <i class="fas fa-calendar-day me-1"></i> <?= $date ?> à <?= $time ?><br>
                                                <i class="fas fa-comment-dots me-1"></i> <?= htmlspecialchars($appointment['notes'] ?? 'Aucune note') ?>
                                            </p>
                                        </div>
                                        <div class="col-md-3 text-md-end">
                                            <span class="badge bg-danger mb-2">Annulé</span>
                                            <div class="btn-group-vertical w-100">
                                                <a href="appointment_details.php?id=<?= $appointment['id'] ?>" class="btn btn-sm btn-outline-primary">Détails</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php
                        }
                    }
                    
                    if (!$hasCanceled) {
                        echo '<div class="text-center py-5"><p class="text-muted">Aucun rendez-vous annulé</p></div>';
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
include_once __DIR__ . '/../includes/footer.php';
?>