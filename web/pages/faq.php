<?php
require_once __DIR__ . '/../config/config.php';
$pageTitle = "FAQ";
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $pageTitle ?> | Business Care</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        .accordion-button:not(.collapsed) {
            background-color: rgba(78, 115, 223, 0.1);
            color: #4e73df;
        }

        .accordion-button:focus {
            box-shadow: 0 0 0 0.25rem rgba(78, 115, 223, 0.25);
        }

        .category-title {
            padding-bottom: 0.5rem;
            margin-bottom: 1.5rem;
            color: #4e73df;
            border-bottom: 2px solid #4e73df;
        }
    </style>
</head>

<body>

    <div class="container my-5">
        <div class="row">
            <div class="col-lg-10 mx-auto">
                <div class="card shadow">
                    <div class="card-header bg-primary text-white">
                        <h1 class="h3 mb-0">Foire Aux Questions (FAQ)</h1>
                    </div>
                    <div class="card-body">
                        <div class="mb-4">
                            <p>Vous trouverez ci-dessous les réponses aux questions les plus fréquemment posées
                                concernant Business Care. Si vous ne trouvez pas la réponse à votre question, n'hésitez
                                pas à <a href="index.php?page=contact">nous contacter</a>.</p>
                        </div>

                      
                        <div class="mb-5">
                            <h2 class="h4 category-title">Questions générales</h2>

                            <div class="accordion" id="accordionGeneral">
                               
                                <div class="accordion-item">
                                    <h3 class="accordion-header" id="headingG1">
                                        <button class="accordion-button collapsed" type="button"
                                            data-bs-toggle="collapse" data-bs-target="#collapseG1" aria-expanded="false"
                                            aria-controls="collapseG1">
                                            Qu'est-ce que Business Care ?
                                        </button>
                                    </h3>
                                    <div id="collapseG1" class="accordion-collapse collapse" aria-labelledby="headingG1"
                                        data-bs-parent="#accordionGeneral">
                                        <div class="accordion-body">
                                            <p>Business Care est une société créée à Paris en 2018 qui propose une
                                                solution pour améliorer la santé, le bien-être et la cohésion en milieu
                                                professionnel. Nous offrons une plateforme qui met en relation les
                                                entreprises et leurs salariés avec des prestataires de services de
                                                bien-être et permet l'organisation d'activités diverses.</p>
                                        </div>
                                    </div>
                                </div>

                                
                                <div class="accordion-item">
                                    <h3 class="accordion-header" id="headingG2">
                                        <button class="accordion-button collapsed" type="button"
                                            data-bs-toggle="collapse" data-bs-target="#collapseG2" aria-expanded="false"
                                            aria-controls="collapseG2">
                                            Comment contacter le service client ?
                                        </button>
                                    </h3>
                                    <div id="collapseG2" class="accordion-collapse collapse" aria-labelledby="headingG2"
                                        data-bs-parent="#accordionGeneral">
                                        <div class="accordion-body">
                                            <p>Vous pouvez contacter notre service client :</p>
                                            <ul>
                                                <li>Par téléphone : 07 68 16 39 48 (lundi-vendredi, 9h-18h)</li>
                                                <li>Par email : <a
                                                        href="mailto:businesscareams@gmail.com">businesscareams@gmail.com</a>
                                                </li>
                                                <li>Via le <a href="index.php?page=contact">formulaire de contact</a> sur notre
                                                    site</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                       
                        <div class="mb-5">
                            <h2 class="h4 category-title">Pour les entreprises</h2>

                            <div class="accordion" id="accordionCompanies">
                                
                                <div class="accordion-item">
                                    <h3 class="accordion-header" id="headingC1">
                                        <button class="accordion-button collapsed" type="button"
                                            data-bs-toggle="collapse" data-bs-target="#collapseC1" aria-expanded="false"
                                            aria-controls="collapseC1">
                                            Comment souscrire à vos services ?
                                        </button>
                                    </h3>
                                    <div id="collapseC1" class="accordion-collapse collapse" aria-labelledby="headingC1"
                                        data-bs-parent="#accordionCompanies">
                                        <div class="accordion-body">
                                            <p>Pour souscrire à nos services, rendez-vous sur notre page d'accueil et
                                                cliquez sur "Demander un devis". Remplissez le formulaire avec vos
                                                besoins, et notre équipe commerciale vous contactera sous 24h pour vous
                                                proposer un programme personnalisé.</p>
                                        </div>
                                    </div>
                                </div>

                               
                                <div class="accordion-item">
                                    <h3 class="accordion-header" id="headingC2">
                                        <button class="accordion-button collapsed" type="button"
                                            data-bs-toggle="collapse" data-bs-target="#collapseC2" aria-expanded="false"
                                            aria-controls="collapseC2">
                                            Quelles sont vos formules d'abonnement ?
                                        </button>
                                    </h3>
                                    <div id="collapseC2" class="accordion-collapse collapse" aria-labelledby="headingC2"
                                        data-bs-parent="#accordionCompanies">
                                        <div class="accordion-body">
                                            <p>Nous proposons trois formules d'abonnement :</p>
                                            <ul>
                                                <li><strong>Starter</strong> : Pour les entreprises jusqu'à 30 salariés
                                                </li>
                                                <li><strong>Basic</strong> : Pour les entreprises jusqu'à 250 salariés
                                                </li>
                                                <li><strong>Premium</strong> : Pour les entreprises de plus de 250
                                                    salariés</li>
                                            </ul>
                                    
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                       
                        <div class="mb-5">
                            <h2 class="h4 category-title">Pour les salariés</h2>

                            <div class="accordion" id="accordionEmployees">
                                
                                <div class="accordion-item">
                                    <h3 class="accordion-header" id="headingE1">
                                        <button class="accordion-button collapsed" type="button"
                                            data-bs-toggle="collapse" data-bs-target="#collapseE1" aria-expanded="false"
                                            aria-controls="collapseE1">
                                            Comment accéder aux services ?
                                        </button>
                                    </h3>
                                    <div id="collapseE1" class="accordion-collapse collapse" aria-labelledby="headingE1"
                                        data-bs-parent="#accordionEmployees">
                                        <div class="accordion-body">
                                            <p>Une fois que votre entreprise a souscrit à nos services, vous recevrez un
                                                email d'invitation avec un lien d'activation. Cliquez sur ce lien, créez
                                                votre mot de passe, et vous aurez accès à votre espace personnel avec
                                                tous les services disponibles.</p>
                                        </div>
                                    </div>
                                </div>

                                
                                <div class="accordion-item">
                                    <h3 class="accordion-header" id="headingE2">
                                        <button class="accordion-button collapsed" type="button"
                                            data-bs-toggle="collapse" data-bs-target="#collapseE2" aria-expanded="false"
                                            aria-controls="collapseE2">
                                            Mon employeur peut-il voir mes consultations médicales ?
                                        </button>
                                    </h3>
                                    <div id="collapseE2" class="accordion-collapse collapse" aria-labelledby="headingE2"
                                        data-bs-parent="#accordionEmployees">
                                        <div class="accordion-body">
                                            <p>Non. Votre employeur ne peut voir que des statistiques anonymisées sur
                                                l'utilisation globale des services. Les détails de vos consultations,
                                                rendez-vous et activités individuelles sont strictement confidentiels et
                                                ne sont jamais partagés avec votre employeur.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                       
                        <div class="mb-5">
                            <h2 class="h4 category-title">Pour les prestataires</h2>

                            <div class="accordion" id="accordionProviders">
                               
                                <div class="accordion-item">
                                    <h3 class="accordion-header" id="headingP1">
                                        <button class="accordion-button collapsed" type="button"
                                            data-bs-toggle="collapse" data-bs-target="#collapseP1" aria-expanded="false"
                                            aria-controls="collapseP1">
                                            Comment devenir prestataire ?
                                        </button>
                                    </h3>
                                    <div id="collapseP1" class="accordion-collapse collapse" aria-labelledby="headingP1"
                                        data-bs-parent="#accordionProviders">
                                        <div class="accordion-body">
                                            <p>Pour devenir prestataire, rendez-vous sur la page "Devenir prestataire"
                                                et remplissez le formulaire de candidature avec vos qualifications et
                                                services proposés. Notre équipe examinera votre profil et vous
                                                contactera pour un entretien si votre candidature correspond à nos
                                                critères.</p>
                                        </div>
                                    </div>
                                </div>

                                
                                <div class="accordion-item">
                                    <h3 class="accordion-header" id="headingP2">
                                        <button class="accordion-button collapsed" type="button"
                                            data-bs-toggle="collapse" data-bs-target="#collapseP2" aria-expanded="false"
                                            aria-controls="collapseP2">
                                            Comment sont calculés mes revenus ?
                                        </button>
                                    </h3>
                                    <div id="collapseP2" class="accordion-collapse collapse" aria-labelledby="headingP2"
                                        data-bs-parent="#accordionProviders">
                                        <div class="accordion-body">
                                            <p>Vos revenus sont calculés en fonction des prestations réalisées durant le
                                                mois. À la fin de chaque mois, une facture automatique est générée,
                                                détaillant l'ensemble de vos prestations et le montant total à
                                                percevoir. Le paiement est effectué par virement bancaire dans les 15
                                                jours suivant la fin du mois.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div>
                            <h2 class="h4 category-title">Questions techniques</h2>

                            <div class="accordion" id="accordionTechnical">
                              
                                <div class="accordion-item">
                                    <h3 class="accordion-header" id="headingT1">
                                        <button class="accordion-button collapsed" type="button"
                                            data-bs-toggle="collapse" data-bs-target="#collapseT1" aria-expanded="false"
                                            aria-controls="collapseT1">
                                            J'ai oublié mon mot de passe, que faire ?
                                        </button>
                                    </h3>
                                    <div id="collapseT1" class="accordion-collapse collapse" aria-labelledby="headingT1"
                                        data-bs-parent="#accordionTechnical">
                                        <div class="accordion-body">
                                            <p>Sur la page de connexion, cliquez sur "Mot de passe oublié". Saisissez
                                                votre adresse email, puis suivez les instructions envoyées par email
                                                pour réinitialiser votre mot de passe.</p>
                                        </div>
                                    </div>
                                </div>

                               
                                <div class="accordion-item">
                                    <h3 class="accordion-header" id="headingT2">
                                        <button class="accordion-button collapsed" type="button"
                                            data-bs-toggle="collapse" data-bs-target="#collapseT2" aria-expanded="false"
                                            aria-controls="collapseT2">
                                            L'application est-elle disponible sur mobile ?
                                        </button>
                                    </h3>
                                    <div id="collapseT2" class="accordion-collapse collapse" aria-labelledby="headingT2"
                                        data-bs-parent="#accordionTechnical">
                                        <div class="accordion-body">
                                            <p>Oui, Business Care est accessible via notre application mobile disponible
                                                sur Android.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php include 'includes/footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>