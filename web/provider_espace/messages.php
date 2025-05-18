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
$pageTitle = "Notifications";


if (isset($_GET['mark_all_read'])) {
    try {
        $db->update(
            'notifications',
            ['is_read' => 1, 'read_at' => date('Y-m-d H:i:s')],
            'user_id = ? AND is_read = 0',
            [$_SESSION['user_id']]
        );
        setAlert('Toutes les notifications ont été marquées comme lues.', 'success');
        redirect('notifications.php');
        exit;
    } catch (Exception $e) {
        setAlert('Erreur lors de la mise à jour des notifications: ' . $e->getMessage(), 'danger');
    }
}


try {
    $notifications = $db->query(
        "SELECT * FROM notifications
         WHERE user_id = ?
         ORDER BY created_at DESC
         LIMIT 50", 
        [$_SESSION['user_id']]
    );
} catch (Exception $e) {
    $notifications = [];
    setAlert('Erreur lors du chargement des notifications: ' . $e->getMessage(), 'danger');
}

$unreadCount = 0;
foreach ($notifications as $notification) {
    if (!$notification['is_read']) {
        $unreadCount++;
    }
}

try {
    $preferences = $db->query(
        "SELECT * FROM notification_preferences
         WHERE user_id = ?
         LIMIT 1",
        [$_SESSION['user_id']],
        true
    );
    
    if (!$preferences) {

        $defaultPreferences = [
            'email_events' => 1,
            'email_appointments' => 1,
            'email_community' => 1,
            'email_marketing' => 0,
            'push_events' => 1,
            'push_appointments' => 1,
            'push_community' => 1,
            'push_marketing' => 0
        ];
        
        $db->insert('notification_preferences', array_merge(
            ['user_id' => $_SESSION['user_id'], 'created_at' => date('Y-m-d H:i:s')],
            $defaultPreferences
        ));
        
        $preferences = $defaultPreferences;
    }
} catch (Exception $e) {
    $preferences = [];
    setAlert('Erreur lors du chargement des préférences de notification: ' . $e->getMessage(), 'danger');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_preferences'])) {
    try {
        $updateData = [
            'email_events' => isset($_POST['email_events']) ? 1 : 0,
            'email_appointments' => isset($_POST['email_appointments']) ? 1 : 0,
            'email_community' => isset($_POST['email_community']) ? 1 : 0,
            'email_marketing' => isset($_POST['email_marketing']) ? 1 : 0,
            'push_events' => isset($_POST['push_events']) ? 1 : 0,
            'push_appointments' => isset($_POST['push_appointments']) ? 1 : 0,
            'push_community' => isset($_POST['push_community']) ? 1 : 0,
            'push_marketing' => isset($_POST['push_marketing']) ? 1 : 0,
            'updated_at' => date('Y-m-d H:i:s')
        ];
        
        $db->update(
            'notification_preferences',
            $updateData,
            'user_id = ?',
            [$_SESSION['user_id']]
        );
        
        setAlert('Vos préférences de notification ont été mises à jour.', 'success');
        $preferences = array_merge($preferences, $updateData);
    } catch (Exception $e) {
        setAlert('Erreur lors de la mise à jour des préférences: ' . $e->getMessage(), 'danger');
    }
}


include_once __DIR__ . '/../includes/header.php';
?>

<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">Notifications</h1>
        <?php if ($unreadCount > 0): ?>
            <a href="notifications.php?mark_all_read=1" class="btn btn-sm btn-outline-primary">
                <i class="fas fa-check-double me-1"></i> Marquer tout comme lu
            </a>
        <?php endif; ?>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">Vos notifications</h6>
                    <span class="badge bg-primary rounded-pill"><?= $unreadCount ?> non lu<?= $unreadCount > 1 ? 's' : '' ?></span>
                </div>
                <div class="card-body p-0">
                    <?php if (empty($notifications)): ?>
                        <div class="text-center py-5">
                            <i class="fas fa-bell-slash fa-4x text-muted mb-3"></i>
                            <p class="lead">Vous n'avez aucune notification</p>
                        </div>
                    <?php else: ?>
                        <div class="list-group list-group-flush">
                            <?php foreach ($notifications as $notification): 
                                $isRead = $notification['is_read'];
                                $createdAt = strtotime($notification['created_at']);
                                $timeAgo = getTimeAgo($createdAt);
                                
                                $icon = 'bell';
                                $iconClass = 'primary';
                                
                                switch ($notification['type']) {
                                    case 'appointment':
                                        $icon = 'calendar-check';
                                        $iconClass = 'success';
                                        break;
                                    case 'review':
                                        $icon = 'star';
                                        $iconClass = 'warning';
                                        break;
                                    case 'invoice':
                                        $icon = 'file-invoice-dollar';
                                        $iconClass = 'info';
                                        break;
                                    case 'event':
                                        $icon = 'users';
                                        $iconClass = 'primary';
                                        break;
                                    case 'verification':
                                        $icon = 'check-circle';
                                        $iconClass = 'success';
                                        break;
                                    case 'message':
                                        $icon = 'envelope';
                                        $iconClass = 'info';
                                        break;
                                }
                            ?>
                                <a href="notification_read.php?id=<?= $notification['id'] ?>&redirect=<?= urlencode($notification['action_url'] ?? 'notifications.php') ?>" 
                                   class="list-group-item list-group-item-action <?= $isRead ? '' : 'bg-light' ?>">
                                    <div class="d-flex align-items-center">
                                        <div class="flex-shrink-0 me-3">
                                            <div class="avatar bg-<?= $iconClass ?>-soft text-<?= $iconClass ?> rounded-circle p-2">
                                                <i class="fas fa-<?= $icon ?>"></i>
                                            </div>
                                        </div>
                                        <div class="flex-grow-1">
                                            <h6 class="mb-1 <?= $isRead ? '' : 'fw-bold' ?>"><?= htmlspecialchars($notification['title']) ?></h6>
                                            <p class="mb-1 small"><?= htmlspecialchars($notification['message']) ?></p>
                                            <small class="text-muted"><?= $timeAgo ?></small>
                                        </div>
                                        <?php if (!$isRead): ?>
                                            <div class="ms-2">
                                                <span class="badge bg-primary rounded-pill">Nouveau</span>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </a>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Préférences de notification</h6>
                </div>
                <div class="card-body">
                    <form action="notifications.php" method="post">
                        <input type="hidden" name="update_preferences" value="1">
                        
                        <h6 class="mb-3">Notifications par email</h6>
                        
                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" id="email_appointments" name="email_appointments" value="1" <?= ($preferences['email_appointments'] ?? 1) ? 'checked' : '' ?>>
                            <label class="form-check-label" for="email_appointments">Rendez-vous (confirmations, rappels, annulations)</label>
                        </div>
                        
                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" id="email_events" name="email_events" value="1" <?= ($preferences['email_events'] ?? 1) ? 'checked' : '' ?>>
                            <label class="form-check-label" for="email_events">Événements (inscriptions, rappels, annulations)</label>
                        </div>
                        
                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" id="email_community" name="email_community" value="1" <?= ($preferences['email_community'] ?? 1) ? 'checked' : '' ?>>
                            <label class="form-check-label" for="email_community">Communauté (avis, commentaires, mentions)</label>
                        </div>
                        
                        <div class="mb-4 form-check">
                            <input type="checkbox" class="form-check-input" id="email_marketing" name="email_marketing" value="1" <?= ($preferences['email_marketing'] ?? 0) ? 'checked' : '' ?>>
                            <label class="form-check-label" for="email_marketing">Marketing et promotions</label>
                        </div>
                        
                        <h6 class="mb-3">Notifications push</h6>
                        
                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" id="push_appointments" name="push_appointments" value="1" <?= ($preferences['push_appointments'] ?? 1) ? 'checked' : '' ?>>
                            <label class="form-check-label" for="push_appointments">Rendez-vous (confirmations, rappels, annulations)</label>
                        </div>
                        
                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" id="push_events" name="push_events" value="1" <?= ($preferences['push_events'] ?? 1) ? 'checked' : '' ?>>
                            <label class="form-check-label" for="push_events">Événements (inscriptions, rappels, annulations)</label>
                        </div>
                        
                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" id="push_community" name="push_community" value="1" <?= ($preferences['push_community'] ?? 1) ? 'checked' : '' ?>>
                            <label class="form-check-label" for="push_community">Communauté (avis, commentaires, mentions)</label>
                        </div>
                        
                        <div class="mb-4 form-check">
                            <input type="checkbox" class="form-check-input" id="push_marketing" name="push_marketing" value="1" <?= ($preferences['push_marketing'] ?? 0) ? 'checked' : '' ?>>
                            <label class="form-check-label" for="push_marketing">Marketing et promotions</label>
                        </div>
                        
                        <div class="text-end">
                            <button type="submit" class="btn btn-primary">Enregistrer les préférences</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
function getTimeAgo($timestamp) {
    $currentTime = time();
    $timeDiff = $currentTime - $timestamp;
    
    if ($timeDiff < 60) {
        return "À l'instant";
    } elseif ($timeDiff < 3600) {
        $minutes = floor($timeDiff / 60);
        return "Il y a " . $minutes . " minute" . ($minutes > 1 ? 's' : '');
    } elseif ($timeDiff < 86400) {
        $hours = floor($timeDiff / 3600);
        return "Il y a " . $hours . " heure" . ($hours > 1 ? 's' : '');
    } elseif ($timeDiff < 604800) {
        $days = floor($timeDiff / 86400);
        return "Il y a " . $days . " jour" . ($days > 1 ? 's' : '');
    } else {
        return date('d/m/Y à H:i', $timestamp);
    }
}

include_once __DIR__ . '/../includes/footer.php';
?>