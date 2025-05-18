<?php
require_once __DIR__ . '/../config/config.php';
$pageTitle = "Conditions d'Utilisation";
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
</head>

<body>


    <div class="container my-5">
        <div class="row">
            <div class="col-lg-10 mx-auto">
                <div class="card shadow">
                    <div class="card-header bg-primary text-white">
                        <h1 class="h3 mb-0">Conditions Générales d'Utilisation</h1>
                    </div>
                    <div class="card-body">
                        <div class="mb-4">
                            <p class="text-muted">Dernière mise à jour : <?= date('d/m/Y') ?></p>
                        </div>

                        <div class="mb-4">
                            <h2 class="h4">1. Présentation du service</h2>
                            <p>Business Care (ci-après "nous", "notre", "nos") est une société créée à Paris en 2018,
                                proposant une solution qui améliore la santé, le bien-être et la cohésion en milieu
                                professionnel. Notre plateforme offre des services variés, tant au niveau de la
                                prévention en santé mentale que dans la création d'événements divers pour assurer une
                                cohésion d'équipe.</p>
                            <p>Le site web a pour objet de permettre à des sociétés clientes (ci-après "Clients") de proposer des services de
                                bien-être à leurs salariés (ci-après "Utilisateurs") grâce à des prestataires externes (ci-après "Prestataires") sélectionnés par Business Care.</p>
                        </div>

                        <div class="mb-4">
                            <h2 class="h4">2. Acceptation des conditions</h2>
                            <p>L'accès et l'utilisation de la plateforme Business Care sont soumis à l'acceptation et au
                                respect des présentes Conditions Générales d'Utilisation (CGU). En accédant à notre
                                plateforme, les Clients, Utilisateurs et Prestataires acceptent, sans réserve,
                                l'intégralité des présentes conditions.</p>
                            <p>Business Care se réserve le droit de modifier à tout moment ces CGU. Les modifications
                                entreront en vigueur dès leur publication sur la plateforme. L'utilisateur sera informé
                                des modifications par email et/ou par notification sur la plateforme.</p>
                        </div>

                        <div class="mb-4">
                            <h2 class="h4">3. Inscription et accès</h2>
                            <p><strong>3.1 Pour les Clients (entreprises) :</strong> L'accès à la plateforme nécessite
                                la création d'un compte client. Le Client s'engage à fournir des informations exactes et
                                à maintenir ces informations à jour. Le Client est responsable de la confidentialité de
                                ses identifiants et de toutes les opérations effectuées via son compte.</p>
                            <p><strong>3.2 Pour les Utilisateurs (salariés) :</strong> L'accès est conditionné à
                                l'abonnement de leur entreprise. Chaque Utilisateur dispose d'un compte personnel et
                                s'engage à ne pas le partager. L'Utilisateur est responsable de la confidentialité de
                                ses identifiants et de toutes les actions effectuées via son compte.</p>
                            <p><strong>3.3 Pour les Prestataires :</strong> L'inscription en tant que Prestataire est
                                soumise à un processus de validation par Business Care. Les Prestataires s'engagent à
                                fournir des services conformes à la description fournie lors de leur inscription et à
                                maintenir un niveau de qualité conforme aux standards de Business Care.</p>
                        </div>

                        <div class="mb-4">
                            <h2 class="h4">4. Abonnements et paiements</h2>
                            <p><strong>4.1 Formules d'abonnement :</strong> Business Care propose différentes formules
                                d'abonnement (Starter, Basic, Premium) avec des tarifs et des services variables selon
                                le nombre de salariés et les prestations incluses.</p>
                            <p><strong>4.2 Modalités de paiement :</strong> Les paiements se font via un système
                                sécurisé en ligne ou par prélèvement bancaire. Le Client s'engage à régler les sommes
                                dues dans les délais prévus.</p>
                            <p><strong>4.3 Facturation :</strong> Les factures sont émises mensuellement ou annuellement
                                selon le contrat établi et sont accessibles dans l'espace client. Pour les Prestataires,
                                une facturation automatique sera réalisée à la fin du mois, synthétisant l'ensemble des
                                prestations réalisées.</p>
                        </div>

                        <div class="mb-4">
                            <h2 class="h4">5. Services proposés</h2>
                            <p><strong>5.1 Services aux Clients :</strong> Gestion des contrats, facturation, gestion
                                des collaborateurs, accès aux paiements des abonnements, devis en temps réel, suivi des
                                activités.</p>
                            <p><strong>5.2 Services aux Utilisateurs :</strong> Accès au catalogue de services,
                                réservation de services, participation à des événements, prise de rendez-vous, gestion
                                de planning, chatbot de réponses automatiques, signalements anonymes, espace conseils,
                                espace associations, communautés entre salariés.</p>
                            <p><strong>5.3 Services aux Prestataires :</strong> Suivi des évaluations, validation de
                                sélection, calendrier des disponibilités, gestion des interventions, facturation
                                automatique, versement des honoraires.</p>
                        </div>

                        <div class="mb-4">
                            <h2 class="h4">6. Obligations et responsabilités</h2>
                            <p><strong>6.1 Obligations de Business Care :</strong> Nous nous engageons à fournir un
                                service conforme à sa description, à maintenir la plateforme accessible (sauf
                                maintenance), à sélectionner rigoureusement les Prestataires, et à protéger les données
                                personnelles conformément à notre politique de confidentialité.</p>
                            <p><strong>6.2 Obligations des Clients :</strong> Les Clients s'engagent à fournir des
                                informations exactes, à respecter les conditions de paiement, et à ne pas utiliser la
                                plateforme à des fins illégales ou contraires aux présentes CGU.</p>
                            <p><strong>6.3 Obligations des Utilisateurs :</strong> Les Utilisateurs s'engagent à
                                utiliser la plateforme conformément à sa destination, à respecter les conditions de
                                réservation et d'annulation, et à ne pas perturber le bon fonctionnement de la
                                plateforme.</p>
                            <p><strong>6.4 Obligations des Prestataires :</strong> Les Prestataires s'engagent à fournir
                                des services de qualité, à respecter leurs engagements de disponibilité, à maintenir à
                                jour leurs informations et certifications, et à respecter la confidentialité des
                                informations auxquelles ils ont accès.</p>
                        </div>

                        <div class="mb-4">
                            <h2 class="h4">7. Propriété intellectuelle</h2>
                            <p>L'ensemble des éléments composant la plateforme Business Care (textes, images, logos,
                                code, etc.) sont protégés par le droit de la propriété intellectuelle. Toute
                                reproduction, représentation, modification ou diffusion de ces éléments, en tout ou
                                partie, sans l'autorisation expresse de Business Care est strictement interdite.</p>
                        </div>

                        <div class="mb-4">
                            <h2 class="h4">8. Limitation de responsabilité</h2>
                            <p>Business Care ne saurait être tenue responsable des dommages directs ou indirects
                                résultant de l'utilisation de la plateforme, notamment en cas d'interruption ou
                                d'indisponibilité du service, de perte de données, ou de dommages causés par des virus
                                ou autres éléments nuisibles.</p>
                            <p>Concernant les prestations réalisées par les Prestataires, Business Care agit uniquement
                                en qualité d'intermédiaire et ne saurait être tenue responsable de la qualité des
                                services fournis, bien que nous nous efforcions de sélectionner des Prestataires
                                qualifiés.</p>
                        </div>

                        <div class="mb-4">
                            <h2 class="h4">9. Protection des données personnelles</h2>
                            <p>La collecte et le traitement des données personnelles sont soumis à notre politique de
                                confidentialité, accessible sur notre plateforme. Business Care s'engage à respecter la
                                réglementation applicable en matière de protection des données personnelles, notamment
                                le Règlement Général sur la Protection des Données (RGPD).</p>
                        </div>

                        <div class="mb-4">
                            <h2 class="h4">10. Résiliation</h2>
                            <p><strong>10.1 Résiliation par les Clients :</strong> Les Clients peuvent résilier leur
                                abonnement selon les modalités prévues dans leur contrat, généralement avec un préavis
                                d'un mois.</p>
                            <p><strong>10.2 Résiliation par les Prestataires :</strong> Les Prestataires peuvent mettre
                                fin à leur collaboration avec Business Care moyennant un préavis de deux mois.</p>
                            <p><strong>10.3 Résiliation par Business Care :</strong> Business Care se réserve le droit
                                de suspendre ou de résilier l'accès à la plateforme en cas de violation des présentes
                                CGU, de non-paiement, ou pour tout autre motif légitime, sans préjudice de tout dommage
                                et intérêt auquel elle pourrait prétendre.</p>
                        </div>

                        <div class="mb-4">
                            <h2 class="h4">11. Loi applicable et juridiction compétente</h2>
                            <p>Les présentes CGU sont soumises au droit français. En cas de litige, une solution amiable
                                sera recherchée en priorité. À défaut, les tribunaux de Paris seront seuls compétents.
                            </p>
                        </div>

                        <div class="mb-4">
                            <h2 class="h4">12. Contact</h2>
                            <p>Pour toute question relative aux présentes CGU, vous pouvez nous contacter à l'adresse
                                suivante : <a href="mailto:businesscareams@gmail.com">businesscareams@gmail.com</a> ou
                                par
                                courrier postal à : Business Care, 110 rue de Rivoli, 75001 Paris.</p>
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