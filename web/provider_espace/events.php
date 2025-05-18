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
$pageTitle = "Gestion des Événements";

try {
    $events = $db->query(
        "SELECT e.*, et.name as event_type_name, COUNT(er.id) as participant_count 
         FROM events e
         LEFT JOIN event_types et ON e.event_type_id = et.id
         LEFT JOIN event_registrations er ON e.id = er.event_id
         WHERE e.provider_id = ?
         GROUP BY e.id
         ORDER BY e.start_datetime DESC",
        [$_SESSION['user_id']]
    );
} catch (Exception $e) {
    $events = [];
    setAlert('Erreur lors du chargement des événements: ' . $e->getMessage(), 'danger');
}

try {
    $eventTypes = $db->query("SELECT * FROM event_types ORDER BY name");
} catch (Exception $e) {
    $eventTypes = [];
}

include_once __DIR__ . '/../includes/header.php';
?>

<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">Gestion des Événements</h1>
        <a href="event_create.php" class="btn btn-primary">
            <i class="fas fa-plus me-1"></i> Créer un événement
        </a>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">Mes Événements</h6>
        </div>
        <div class="card-body">
            <ul class="nav nav-tabs mb-4" id="eventTabs" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="upcoming-events-tab" data-bs-toggle="tab" href="#upcoming-events" role="tab">À venir</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="past-events-tab" data-bs-toggle="tab" href="#past-events" role="tab">Passés</a>
                </li>
            </ul>
            
            <div class="tab-content" id="eventTabsContent">
                <div class="tab-pane fade show active" id="upcoming-events" role="tabpanel">
                    <?php
                    $hasUpcoming = false;
                    foreach ($events as $event) {
                        if (strtotime($event['start_datetime']) > time()) {
                            $hasUpcoming = true;
                            $startDate = date('d/m/Y', strtotime($event['start_datetime']));
                            $startTime = date('H:i', strtotime($event['start_datetime']));
                            $endTime = date('H:i', strtotime($event['end_datetime']));
                            ?>
                            <div class="card mb-3 border-primary">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-9">
                                            <h5 class="card-title"><?= htmlspecialchars($event['title']) ?></h5>
                                            <span class="badge bg-info mb-2"><?= htmlspecialchars($event['event_type_name']) ?></span>
                                            <p class="card-text">
                                                <i class="fas fa-calendar-day me-1"></i> <?= $startDate ?> de <?= $startTime ?> à <?= $endTime ?><br>
                                                <i class="fas fa-map-marker-alt me-1"></i> <?= htmlspecialchars($event['location']) ?><br>
                                                <i class="fas fa-users me-1"></i> <?= $event['participant_count'] ?> participant(s) <?= $event['max_participants'] ? '(max: ' . $event['max_participants'] . ')' : '' ?>
                                            </p>
                                            <p class="card-text"><?= nl2br(htmlspecialchars(substr($event['description'], 0, 150))) ?><?= strlen($event['description']) > 150 ? '...' : '' ?></p>
                                        </div>
                                        <div class="col-md-3 text-md-end">
                                            <div class="btn-group-vertical w-100">
                                                <a href="event_details.php?id=<?= $event['id'] ?>" class="btn btn-sm btn-outline-primary">Détails</a>
                                                <a href="event_participants.php?id=<?= $event['id'] ?>" class="btn btn-sm btn-info">Participants</a>
                                                <a href="event_edit.php?id=<?= $event['id'] ?>" class="btn btn-sm btn-warning">Modifier</a>
                                                <a href="event_action.php?id=<?= $event['id'] ?>&action=cancel" class="btn btn-sm btn-outline-danger" onclick="return confirm('Êtes-vous sûr de vouloir annuler cet événement?')">Annuler</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php
                        }
                    }
                    
                    if (!$hasUpcoming) {
                        echo '<div class="text-center py-5">
                                <p class="text-muted">Aucun événement à venir</p>
                                <a href="event_create.php" class="btn btn-primary">Créer mon premier événement</a>
                              </div>';
                    }
                    ?>
                </div>
                <div class="tab-pane fade" id="past-events" role="tabpanel">
                    <?php
                    $hasPast = false;
                    foreach ($events as $event) {
                        if (strtotime($event['start_datetime']) <= time()) {
                            $hasPast = true;
                            $startDate = date('d/m/Y', strtotime($event['start_datetime']));
                            $startTime = date('H:i', strtotime($event['start_datetime']));
                            $endTime = date('H:i', strtotime($event['end_datetime']));
                            ?>
                            <div class="card mb-3">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-9">
                                            <h5 class="card-title"><?= htmlspecialchars($event['title']) ?></h5>
                                            <span class="badge bg-info mb-2"><?= htmlspecialchars($event['event_type_name']) ?></span>
                                            <p class="card-text">
                                                <i class="fas fa-calendar-day me-1"></i> <?= $startDate ?> de <?= $startTime ?> à <?= $endTime ?><br>
                                                <i class="fas fa-map-marker-alt me-1"></i> <?= htmlspecialchars($event['location']) ?><br>
                                                <i class="fas fa-users me-1"></i> <?= $event['participant_count'] ?> participant(s)
                                            </p>
                                        </div>
                                        <div class="col-md-3 text-md-end">
                                            <div class="btn-group-vertical w-100">
                                                <a href="event_details.php?id=<?= $event['id'] ?>" class="btn btn-sm btn-outline-primary">Détails</a>
                                                <a href="event_participants.php?id=<?= $event['id'] ?>" class="btn btn-sm btn-info">Participants</a>
                                                <a href="event_report.php?id=<?= $event['id'] ?>" class="btn btn-sm btn-success">Rapport</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php
                        }
                    }
                    
                    if (!$hasPast) {
                        echo '<div class="text-center py-5"><p class="text-muted">Aucun événement passé</p></div>';
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
include_once __DIR__ . '/../includes/header.php';
?>