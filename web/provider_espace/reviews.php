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
$pageTitle = "Mes Avis";

try {
    $reviews = $db->query(
        "SELECT r.*, u.first_name, u.last_name, c.name as company_name 
         FROM provider_reviews r
         JOIN users u ON r.user_id = u.id
         LEFT JOIN companies c ON u.company_id = c.id
         WHERE r.provider_id = ?
         ORDER BY r.created_at DESC",
        [$_SESSION['user_id']]
    );
} catch (Exception $e) {
    $reviews = [];
    setAlert('Erreur lors du chargement des avis: ' . $e->getMessage(), 'danger');
}

$totalRating = 0;
$reviewCount = count($reviews);

foreach ($reviews as $review) {
    $totalRating += $review['rating'];
}

$averageRating = $reviewCount > 0 ? $totalRating / $reviewCount : 0;

$ratingStats = [1 => 0, 2 => 0, 3 => 0, 4 => 0, 5 => 0];

foreach ($reviews as $review) {
    $rating = $review['rating'];
    if (isset($ratingStats[$rating])) {
        $ratingStats[$rating]++;
    }
}

$ratingPercentages = [];
foreach ($ratingStats as $rating => $count) {
    $ratingPercentages[$rating] = $reviewCount > 0 ? ($count / $reviewCount) * 100 : 0;
}

include_once __DIR__ . '/../includes/header.php';
?>

<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">Mes Avis</h1>
    </div>


    <div class="row mb-4">
        <div class="col-lg-4">
            <div class="card shadow h-100">
                <div class="card-body text-center">
                    <h5 class="card-title">Note moyenne</h5>
                    <div class="display-4 fw-bold my-3"><?= number_format($averageRating, 1) ?><span class="fs-4">/5</span></div>
                    <div class="mb-3">
                        <?php for ($i = 1; $i <= 5; $i++): ?>
                            <?php if ($i <= floor($averageRating)): ?>
                                <i class="fas fa-star text-warning"></i>
                            <?php elseif ($i - 0.5 <= $averageRating): ?>
                                <i class="fas fa-star-half-alt text-warning"></i>
                            <?php else: ?>
                                <i class="far fa-star text-warning"></i>
                            <?php endif; ?>
                        <?php endfor; ?>
                    </div>
                    <p class="text-muted">Basé sur <?= $reviewCount ?> avis</p>
                </div>
            </div>
        </div>
        <div class="col-lg-8">
            <div class="card shadow h-100">
                <div class="card-body">
                    <h5 class="card-title">Répartition des notes</h5>
                    <div class="py-3">
                        <?php for ($rating = 5; $rating >= 1; $rating--): ?>
                            <div class="d-flex align-items-center mb-2">
                                <div class="me-2" style="width: 40px;">
                                    <?= $rating ?> <i class="fas fa-star text-warning"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <div class="progress" style="height: 10px;">
                                        <div class="progress-bar bg-warning" role="progressbar" style="width: <?= $ratingPercentages[$rating] ?>%;" aria-valuenow="<?= $ratingPercentages[$rating] ?>" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                </div>
                                <div class="ms-2" style="width: 45px;">
                                    <?= $ratingStats[$rating] ?> avis
                                </div>
                            </div>
                        <?php endfor; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">Tous les avis</h6>
        </div>
        <div class="card-body">
            <?php if (empty($reviews)): ?>
                <div class="text-center py-5">
                    <i class="fas fa-comment-alt fa-4x text-muted mb-3"></i>
                    <p class="lead">Vous n'avez encore reçu aucun avis</p>
                    <p class="text-muted">Les avis apparaîtront ici une fois que vos clients auront évalué vos services.</p>
                </div>
            <?php else: ?>
                <?php foreach ($reviews as $review): ?>
                    <div class="card mb-3">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <div>
                                    <h5 class="card-title mb-0"><?= htmlspecialchars($review['first_name'] . ' ' . $review['last_name']) ?></h5>
                                    <p class="text-muted small mb-0"><?= htmlspecialchars($review['company_name'] ?? 'Entreprise non spécifiée') ?></p>
                                </div>
                                <div class="text-end">
                                    <div class="mb-1">
                                        <?php for ($i = 1; $i <= 5; $i++): ?>
                                            <?php if ($i <= $review['rating']): ?>
                                                <i class="fas fa-star text-warning"></i>
                                            <?php else: ?>
                                                <i class="far fa-star text-warning"></i>
                                            <?php endif; ?>
                                        <?php endfor; ?>
                                    </div>
                                    <p class="text-muted small mb-0"><?= date('d/m/Y', strtotime($review['created_at'])) ?></p>
                                </div>
                            </div>
                            <p class="card-text"><?= nl2br(htmlspecialchars($review['comment'])) ?></p>
                            <?php if (!empty($review['provider_response'])): ?>
                                <div class="border-top pt-3 mt-3">
                                    <p class="fw-bold mb-1">Votre réponse :</p>
                                    <p class="mb-0"><?= nl2br(htmlspecialchars($review['provider_response'])) ?></p>
                                </div>
                            <?php else: ?>
                                <div class="text-end">
                                    <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#responseModal<?= $review['id'] ?>">
                                        Répondre
                                    </button>
                                </div>
                                
                            
                                <div class="modal fade" id="responseModal<?= $review['id'] ?>" tabindex="-1" aria-labelledby="responseModalLabel<?= $review['id'] ?>" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <form action="review_respond.php" method="post">
                                                <input type="hidden" name="review_id" value="<?= $review['id'] ?>">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="responseModalLabel<?= $review['id'] ?>">Répondre à l'avis</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="mb-3">
                                                        <label for="provider_response<?= $review['id'] ?>" class="form-label">Votre réponse</label>
                                                        <textarea class="form-control" id="provider_response<?= $review['id'] ?>" name="provider_response" rows="4" required></textarea>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                                                    <button type="submit" class="btn btn-primary">Envoyer</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php
include_once __DIR__ . '/../includes/footer.php';
?>