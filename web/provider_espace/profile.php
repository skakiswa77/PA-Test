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
$pageTitle = "Mon Profil";


try {
    $user = $db->query(
        "SELECT u.*, pp.bio, pp.hourly_rate, pp.is_verified, pp.rating, ps.name as specialization, ps.id as specialization_id
         FROM users u
         LEFT JOIN provider_profiles pp ON u.id = pp.user_id
         LEFT JOIN provider_specializations ps ON pp.specialization_id = ps.id
         WHERE u.id = ? LIMIT 1",
        [$_SESSION['user_id']],
        true
    );
} catch (Exception $e) {
    $user = [
        'id' => $_SESSION['user_id'],
        'first_name' => $_SESSION['user_name'] ?? 'Prestataire',
        'last_name' => '',
        'email' => '',
        'profile_picture' => '',
        'rating' => 0
    ];
    setAlert('Erreur lors du chargement du profil: ' . $e->getMessage(), 'danger');
}


try {
    $specializations = $db->query("SELECT * FROM provider_specializations ORDER BY name");
} catch (Exception $e) {
    $specializations = [];
}


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_profile'])) {
    $firstName = trim($_POST['first_name'] ?? '');
    $lastName = trim($_POST['last_name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $bio = trim($_POST['bio'] ?? '');
    $hourlyRate = (float)($_POST['hourly_rate'] ?? 0);
    $specializationId = (int)($_POST['specialization_id'] ?? 0);
    
    $errors = [];
    
 
    if (empty($firstName)) {
        $errors[] = 'Le prénom est requis.';
    }
    
    if (empty($lastName)) {
        $errors[] = 'Le nom est requis.';
    }
    
    if (empty($email)) {
        $errors[] = 'L\'email est requis.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'L\'email est invalide.';
    }
    
    if ($hourlyRate <= 0) {
        $errors[] = 'Le taux horaire doit être supérieur à 0.';
    }
    
    if ($specializationId <= 0) {
        $errors[] = 'Veuillez sélectionner une spécialisation.';
    }
    

    $profilePicture = $user['profile_picture'];
    
    if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] === UPLOAD_ERR_OK) {
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
        $maxSize = 5 * 1024 * 1024; 
        
        if (!in_array($_FILES['profile_picture']['type'], $allowedTypes)) {
            $errors[] = 'Format d\'image non pris en charge. Utilisez JPG, PNG ou GIF.';
        } elseif ($_FILES['profile_picture']['size'] > $maxSize) {
            $errors[] = 'L\'image est trop volumineuse. Taille maximale : 5 Mo.';
        } else {
  
            $extension = pathinfo($_FILES['profile_picture']['name'], PATHINFO_EXTENSION);
            $fileName = 'provider_' . $_SESSION['user_id'] . '_' . time() . '.' . $extension;
            $uploadPath = __DIR__ . '/../uploads/profile_pictures/' . $fileName;
            
            if (!is_dir(dirname($uploadPath))) {
                mkdir(dirname($uploadPath), 0777, true);
            }
            
            if (move_uploaded_file($_FILES['profile_picture']['tmp_name'], $uploadPath)) {
                $profilePicture = 'uploads/profile_pictures/' . $fileName;
            } else {
                $errors[] = 'Erreur lors de l\'upload de l\'image. Veuillez réessayer.';
            }
        }
    }
    
    if (empty($errors)) {
        try {
            $db->beginTransaction();
            
    
            $db->update(
                'users',
                [
                    'first_name' => $firstName,
                    'last_name' => $lastName,
                    'email' => $email,
                    'phone' => $phone,
                    'profile_picture' => $profilePicture,
                    'updated_at' => date('Y-m-d H:i:s')
                ],
                'id = ?',
                [$_SESSION['user_id']]
            );
            

            $providerProfile = $db->query(
                "SELECT * FROM provider_profiles WHERE user_id = ? LIMIT 1",
                [$_SESSION['user_id']],
                true
            );
            
            if ($providerProfile) {

                $db->update(
                    'provider_profiles',
                    [
                        'specialization_id' => $specializationId,
                        'bio' => $bio,
                        'hourly_rate' => $hourlyRate,
                        'updated_at' => date('Y-m-d H:i:s')
                    ],
                    'user_id = ?',
                    [$_SESSION['user_id']]
                );
            } else {

                $db->insert('provider_profiles', [
                    'user_id' => $_SESSION['user_id'],
                    'specialization_id' => $specializationId,
                    'bio' => $bio,
                    'hourly_rate' => $hourlyRate,
                    'is_verified' => 0,
                    'rating' => 0,
                    'created_at' => date('Y-m-d H:i:s')
                ]);
            }
            
            $db->commit();
            

            $_SESSION['user_name'] = $firstName . ' ' . $lastName;
            
            setAlert('Votre profil a été mis à jour avec succès.', 'success');
            redirect('profile.php');
            exit;
            
        } catch (Exception $e) {
            $db->rollback();
            setAlert('Erreur lors de la mise à jour du profil: ' . $e->getMessage(), 'danger');
        }
    } else {
 
        setAlert(implode('<br>', $errors), 'danger');
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['change_password'])) {
    $currentPassword = $_POST['current_password'] ?? '';
    $newPassword = $_POST['new_password'] ?? '';
    $confirmPassword = $_POST['confirm_password'] ?? '';
    
    $errors = [];
    
    if (empty($currentPassword)) {
        $errors[] = 'Le mot de passe actuel est requis.';
    }
    
    if (empty($newPassword)) {
        $errors[] = 'Le nouveau mot de passe est requis.';
    } elseif (strlen($newPassword) < 8) {
        $errors[] = 'Le nouveau mot de passe doit contenir au moins 8 caractères.';
    }
    
    if ($newPassword !== $confirmPassword) {
        $errors[] = 'Les mots de passe ne correspondent pas.';
    }
    
    if (empty($errors)) {
        try {
            $userPassword = $db->query(
                "SELECT password FROM users WHERE id = ? LIMIT 1",
                [$_SESSION['user_id']],
                true
            );
            
            if ($userPassword && password_verify($currentPassword, $userPassword['password'])) {

                $passwordHash = password_hash($newPassword, PASSWORD_DEFAULT);
                
                $db->update(
                    'users',
                    [
                        'password' => $passwordHash,
                        'updated_at' => date('Y-m-d H:i:s')
                    ],
                    'id = ?',
                    [$_SESSION['user_id']]
                );
                
                setAlert('Votre mot de passe a été mis à jour avec succès.', 'success');
                redirect('profile.php');
                exit;
            } else {
                setAlert('Le mot de passe actuel est incorrect.', 'danger');
            }
        } catch (Exception $e) {
            setAlert('Erreur lors de la mise à jour du mot de passe: ' . $e->getMessage(), 'danger');
        }
    } else {
        setAlert(implode('<br>', $errors), 'danger');
    }
}

include_once __DIR__ . '/../includes/header.php';
?>

<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">Mon Profil</h1>
    </div>

    <div class="row">
        <div class="col-xl-8 col-lg-7">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Informations personnelles</h6>
                </div>
                <div class="card-body">
                    <form action="profile.php" method="post" enctype="multipart/form-data">
                        <input type="hidden" name="update_profile" value="1">
                        
                        <div class="row mb-4">
                            <div class="col-md-4 text-center">
                                <div class="mb-3">
                                    <img src="<?= !empty($user['profile_picture']) ? APP_URL . '/' . $user['profile_picture'] : 'https://via.placeholder.com/150' ?>" class="img-thumbnail rounded-circle mb-3" alt="Photo de profil" style="width: 150px; height: 150px; object-fit: cover;">
                                    <div class="mb-3">
                                        <label for="profile_picture" class="form-label">Changer la photo</label>
                                        <input class="form-control form-control-sm" id="profile_picture" name="profile_picture" type="file" accept="image/*">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-8">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="first_name" class="form-label">Prénom</label>
                                        <input type="text" class="form-control" id="first_name" name="first_name" value="<?= htmlspecialchars($user['first_name'] ?? '') ?>" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="last_name" class="form-label">Nom</label>
                                        <input type="text" class="form-control" id="last_name" name="last_name" value="<?= htmlspecialchars($user['last_name'] ?? '') ?>" required>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label for="email" class="form-label">Email</label>
                                    <input type="email" class="form-control" id="email" name="email" value="<?= htmlspecialchars($user['email'] ?? '') ?>" required>
                                </div>
                                <div class="mb-3">
                                    <label for="phone" class="form-label">Téléphone</label>
                                    <input type="tel" class="form-control" id="phone" name="phone" value="<?= htmlspecialchars($user['phone'] ?? '') ?>">
                                </div>
                            </div>
                        </div>
                        
                        <hr class="my-4">
                        
                        <h6 class="mb-3 text-primary">Informations professionnelles</h6>
                        
                        <div class="mb-3">
                            <label for="specialization_id" class="form-label">Spécialisation</label>
                            <select class="form-select" id="specialization_id" name="specialization_id" required>
                                <option value="">Sélectionner une spécialisation</option>
                                <?php foreach ($specializations as $specialization): ?>
                                    <option value="<?= $specialization['id'] ?>" <?= ($user['specialization_id'] ?? 0) == $specialization['id'] ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($specialization['name']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        
                        <div class="mb-3">
                            <label for="hourly_rate" class="form-label">Taux horaire (€)</label>
                            <input type="number" class="form-control" id="hourly_rate" name="hourly_rate" value="<?= htmlspecialchars($user['hourly_rate'] ?? '0') ?>" min="0" step="0.01" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="bio" class="form-label">Biographie</label>
                            <textarea class="form-control" id="bio" name="bio" rows="5"><?= htmlspecialchars($user['bio'] ?? '') ?></textarea>
                            <div class="form-text">Décrivez votre expérience, vos compétences et vos services.</div>
                        </div>
                        
                        <div class="text-end">
                            <button type="submit" class="btn btn-primary">Enregistrer les modifications</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        

        <div class="col-xl-4 col-lg-5">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Sécurité</h6>
                </div>
                <div class="card-body">
                    <form action="profile.php" method="post">
                        <input type="hidden" name="change_password" value="1">
                        
                        <div class="mb-3">
                            <label for="current_password" class="form-label">Mot de passe actuel</label>
                            <input type="password" class="form-control" id="current_password" name="current_password" required>
                        </div>

                        <div class="mb-3">
                            <label for="new_password" class="form-label">Nouveau mot de passe</label>
                            <input type="password" class="form-control" id="new_password" name="new_password" required minlength="8">
                            <div class="form-text">8 caractères minimum</div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="confirm_password" class="form-label">Confirmer le mot de passe</label>
                            <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                        </div>
                        
                        <div class="text-end">
                            <button type="submit" class="btn btn-primary">Changer le mot de passe</button>
                        </div>
                    </form>
                </div>
            </div>
            
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Statut du compte</h6>
                </div>
                <div class="card-body">
                    <div class="mb-4">
                        <h6>Statut de vérification</h6>
                        <?php if ($user['is_verified'] ?? 0): ?>
                            <div class="alert alert-success d-flex align-items-center" role="alert">
                                <i class="fas fa-check-circle me-2"></i>
                                <div>
                                    <strong>Compte vérifié</strong><br>
                                    Votre compte a été vérifié par l'équipe Business Care.
                                </div>
                            </div>
                        <?php else: ?>
                            <div class="alert alert-warning d-flex align-items-center" role="alert">
                                <i class="fas fa-exclamation-circle me-2"></i>
                                <div>
                                    <strong>En attente de vérification</strong><br>
                                    Votre compte est en cours de vérification par l'équipe Business Care.
                                </div>
                            </div>
                            <div class="text-center">
                                <a href="verification.php" class="btn btn-sm btn-outline-primary">Compléter ma vérification</a>
                            </div>
                        <?php endif; ?>
                    </div>
                    
                    <div>
                        <h6>Satisfaction client</h6>
                        <div class="d-flex align-items-center mb-2">
                            <div class="me-3">
                                <span class="display-6"><?= number_format($user['rating'] ?? 0, 1) ?></span>
                                <span class="text-muted">/5</span>
                            </div>
                            <div>
                                <?php for ($i = 1; $i <= 5; $i++): ?>
                                    <?php if ($i <= floor(($user['rating'] ?? 0))): ?>
                                        <i class="fas fa-star text-warning"></i>
                                    <?php elseif ($i - 0.5 <= ($user['rating'] ?? 0)): ?>
                                        <i class="fas fa-star-half-alt text-warning"></i>
                                    <?php else: ?>
                                        <i class="far fa-star text-warning"></i>
                                    <?php endif; ?>
                                <?php endfor; ?>
                            </div>
                        </div>
                        <a href="reviews.php" class="btn btn-sm btn-outline-primary w-100">Voir tous les avis</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
include_once __DIR__ . '/../includes/footer.php';
?>