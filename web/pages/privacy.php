<?php
require_once __DIR__ . '/../config/config.php';
$pageTitle = "Politique de Confidentialité";
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
                        <h1 class="h3 mb-0">Politique de Confidentialité</h1>
                    </div>
                    <div class="card-body">
                        <div class="mb-4">
                            <p class="text-muted">Dernière mise à jour : <?= date('d/m/Y') ?></p>
                        </div>

                        <div class="mb-4">
                            <h2 class="h4">1. Introduction</h2>
                            <p>Business Care s'engage à protéger la vie privée de ses utilisateurs et à traiter leurs
                                données personnelles conformément aux réglementations en vigueur, notamment le Règlement
                                Général sur la Protection des Données (RGPD).</p>
                            <p>Cette politique de confidentialité explique comment nous collectons, utilisons,
                                partageons et protégeons les informations personnelles des clients, utilisateurs et
                                prestataires qui utilisent notre plateforme.</p>
                        </div>

                        <div class="mb-4">
                            <h2 class="h4">2. Collecte des données personnelles</h2>
                            <p>Nous collectons les données personnelles suivantes :</p>
                            <p><strong>2.1 Pour les Clients (entreprises) :</strong></p>
                            <ul>
                                <li>Nom et coordonnées de l'entreprise (adresse, téléphone, email)</li>
                                <li>Informations de facturation et coordonnées bancaires</li>
                                <li>Nom, prénom, fonction, email et téléphone des personnes de contact</li>
                                <li>Historique des contrats et des services souscrits</li>
                            </ul>
                            <p><strong>2.2 Pour les Utilisateurs (salariés) :</strong></p>
                            <ul>
                                <li>Nom, prénom</li>
                                <li>Adresse email professionnelle</li>
                                <li>Entreprise d'appartenance</li>
                                <li>Numéro de téléphone (optionnel)</li>
                                <li>Genre (optionnel)</li>
                                <li>Date de naissance (optionnel)</li>
                                <li>Adresse postale (optionnel)</li>
                                <li>Historique des activités et rendez-vous</li>
                                <li>Préférences pour les événements et services</li>
                            </ul>
                            <p><strong>2.3 Pour les Prestataires :</strong></p>
                            <ul>
                                <li>Nom, prénom</li>
                                <li>Adresse email et téléphone</li>
                                <li>Adresse postale</li>
                                <li>Informations bancaires pour les paiements</li>
                                <li>Qualifications professionnelles et certificats</li>
                                <li>Spécialités et services proposés</li>
                                <li>Disponibilités et tarifs</li>
                                <li>Évaluations et avis</li>
                            </ul>
                        </div>

                        <div class="mb-4">
                            <h2 class="h4">3. Finalités du traitement des données</h2>
                            <p>Nous utilisons les données personnelles collectées pour :</p>
                            <ul>
                                <li>Fournir et gérer les services de la plateforme</li>
                                <li>Faciliter les réservations entre utilisateurs et prestataires</li>
                                <li>Gérer les comptes utilisateurs et les abonnements</li>
                                <li>Traiter les paiements et la facturation</li>
                                <li>Communiquer avec les utilisateurs (notifications, rappels de rendez-vous, etc.)</li>
                                <li>Anonymiser les données pour des analyses statistiques et l'amélioration des services
                                </li>
                                <li>Gérer les avis et évaluations des prestataires</li>
                                <li>Assurer la sécurité de la plateforme</li>
                                <li>Respecter nos obligations légales</li>
                            </ul>
                        </div>

                        <div class="mb-4">
                            <h2 class="h4">4. Base légale du traitement</h2>
                            <p>Le traitement de vos données personnelles repose sur les bases légales suivantes :</p>
                            <ul>
                                <li><strong>Exécution d'un contrat :</strong> Le traitement est nécessaire à l'exécution
                                    du contrat auquel vous êtes partie ou à l'exécution de mesures précontractuelles
                                    prises à votre demande.</li>
                                <li><strong>Consentement :</strong> Vous avez consenti au traitement de vos données à
                                    caractère personnel pour une ou plusieurs finalités spécifiques.</li>
                                <li><strong>Intérêts légitimes :</strong> Le traitement est nécessaire aux fins des
                                    intérêts légitimes poursuivis par Business Care ou par un tiers, à moins que ne
                                    prévalent les intérêts ou les libertés et droits fondamentaux de la personne
                                    concernée.</li>
                                <li><strong>Obligation légale :</strong> Le traitement est nécessaire au respect d'une
                                    obligation légale à laquelle Business Care est soumise.</li>
                            </ul>
                        </div>

                        <div class="mb-4">
                            <h2 class="h4">5. Confidentialité des données médicales</h2>
                            <p>Business Care attache une importance particulière à la protection des données relatives à
                                la santé :</p>
                            <ul>
                                <li>Les rendez-vous médicaux des salariés sont anonymisés au niveau de l'entreprise.
                                </li>
                                <li>Les informations sur la nature des consultations ne sont pas partagées avec
                                    l'employeur.</li>
                                <li>Seuls les prestataires concernés ont accès aux informations nécessaires à la
                                    réalisation de leurs services.</li>
                                <li>Les signalements anonymes sont traités avec la plus grande confidentialité.</li>
                            </ul>
                        </div>

                        <div class="mb-4">
                            <h2 class="h4">6. Conservation des données</h2>
                            <p>Nous conservons vos données personnelles aussi longtemps que nécessaire pour vous fournir
                                nos services ou pour répondre à nos obligations légales :</p>
                            <ul>
                                <li><strong>Données des comptes actifs :</strong> Conservées pendant toute la durée de
                                    la relation contractuelle.</li>
                                <li><strong>Données de facturation :</strong> Conservées pendant 10 ans conformément aux
                                    obligations légales.</li>
                                <li><strong>Données des comptes inactifs :</strong> Conservées pendant 3 ans après la
                                    dernière activité, puis anonymisées ou supprimées.</li>
                                <li><strong>Données des prestataires :</strong> Conservées pendant toute la durée de la
                                    collaboration, puis 5 ans après la fin de celle-ci pour des raisons légales et de
                                    gestion des litiges.</li>
                            </ul>
                        </div>

                        <div class="mb-4">
                            <h2 class="h4">7. Partage des données</h2>
                            <p>Nous pouvons partager vos données personnelles avec :</p>
                            <ul>
                                <li><strong>Les prestataires de services :</strong> Uniquement les informations
                                    nécessaires pour assurer les rendez-vous et services.</li>
                                <li><strong>Les sous-traitants :</strong> Pour l'hébergement, la maintenance, les
                                    paiements et autres services nécessaires au fonctionnement de la plateforme. Ces
                                    sous-traitants sont soumis à des clauses strictes de confidentialité.</li>
                                <li><strong>Les autorités :</strong> En cas d'obligation légale, de décision judiciaire
                                    ou de demande d'une autorité habilitée.</li>
                            </ul>
                            <p>Nous ne vendons pas vos données personnelles à des tiers et nous ne les partageons pas à
                                des fins publicitaires sans votre consentement explicite.</p>
                        </div>

                        <div class="mb-4">
                            <h2 class="h4">8. Transferts internationaux de données</h2>
                            <p>Business Care stocke et traite vos données en France et dans l'Union Européenne. Si un
                                transfert de données vers un pays tiers est nécessaire, nous nous assurons que des
                                garanties appropriées sont mises en place conformément au RGPD, notamment par le biais
                                de clauses contractuelles types approuvées par la Commission européenne.</p>
                        </div>

                        <div class="mb-4">
                            <h2 class="h4">9. Sécurité des données</h2>
                            <p>Business Care met en œuvre des mesures techniques et organisationnelles appropriées pour
                                protéger vos données personnelles contre la perte, l'accès non autorisé, la divulgation,
                                l'altération ou la destruction :</p>
                            <ul>
                                <li>Cryptage des données sensibles (HTTPS, chiffrement des mots de passe)</li>
                                <li>Contrôles d'accès stricts et authentification à deux facteurs</li>
                                <li>Sauvegardes régulières</li>
                                <li>Mises à jour de sécurité</li>
                                <li>Formation du personnel à la sécurité des données</li>
                                <li>Audits de sécurité réguliers</li>
                            </ul>
                        </div>

                        <div class="mb-4">
                            <h2 class="h4">10. Vos droits</h2>
                            <p>Conformément au RGPD, vous disposez des droits suivants concernant vos données
                                personnelles :</p>
                            <ul>
                                <li><strong>Droit d'accès :</strong> Vous pouvez demander une copie des données
                                    personnelles que nous détenons vous concernant.</li>
                                <li><strong>Droit de rectification :</strong> Vous pouvez demander la correction des
                                    données inexactes ou incomplètes.</li>
                                <li><strong>Droit à l'effacement :</strong> Vous pouvez demander la suppression de vos
                                    données dans certaines circonstances.</li>
                                <li><strong>Droit à la limitation du traitement :</strong> Vous pouvez demander la
                                    limitation du traitement de vos données dans certaines circonstances.</li>
                                <li><strong>Droit à la portabilité :</strong> Vous pouvez demander à recevoir vos
                                    données dans un format structuré, couramment utilisé et lisible par machine.</li>
                                <li><strong>Droit d'opposition :</strong> Vous pouvez vous opposer au traitement de vos
                                    données dans certaines circonstances.</li>
                                <li><strong>Droit de retirer votre consentement :</strong> Lorsque le traitement est
                                    basé sur votre consentement, vous pouvez le retirer à tout moment.</li>
                                <li><strong>Droit d'introduire une réclamation :</strong> Vous pouvez déposer une
                                    plainte auprès d'une autorité de contrôle (en France, la CNIL).</li>
                            </ul>
                            <p>Pour exercer ces droits, vous pouvez nous contacter à l'adresse <a
                                    href="mailto:businesscareams@gmail.com">businesscareams@gmail.com</a> ou par courrier à
                                l'adresse : Business Care - Service Protection des Données, 110 rue de Rivoli, 75001
                                Paris.</p>
                        </div>

                        <div class="mb-4">
                            <h2 class="h4">11. Cookies et technologies similaires</h2>
                            <p>Notre plateforme utilise des cookies et technologies similaires pour améliorer votre
                                expérience utilisateur, analyser l'utilisation de notre site et personnaliser le
                                contenu. Vous pouvez gérer vos préférences concernant les cookies via notre bannière de
                                cookies lors de votre première visite sur notre site.</p>
                            <p>Nous utilisons différents types de cookies :</p>
                            <ul>
                                <li><strong>Cookies essentiels :</strong> Nécessaires au fonctionnement du site.</li>
                                <li><strong>Cookies de fonctionnalité :</strong> Permettent de mémoriser vos
                                    préférences.</li>
                                <li><strong>Cookies analytiques :</strong> Nous aident à comprendre comment vous
                                    utilisez notre site.</li>
                                <li><strong>Cookies de session :</strong> Temporaires, supprimés lorsque vous fermez
                                    votre navigateur.</li>
                            </ul>
                        </div>

                        <div class="mb-4">
                            <h2 class="h4">12. Modifications de la politique de confidentialité</h2>
                            <p>Nous pouvons modifier cette politique de confidentialité à tout moment. La version mise à
                                jour sera publiée sur notre site web avec la date de la dernière mise à jour. Nous vous
                                encourageons à consulter régulièrement cette page pour rester informé des changements.
                            </p>
                            <p>Pour les modifications importantes, nous vous informerons par email ou par notification
                                sur notre plateforme.</p>
                        </div>

                        <div class="mb-4">
                            <h2 class="h4">13. Contact</h2>
                            <p>Pour toute question concernant cette politique de confidentialité ou vos données
                                personnelles, vous pouvez nous contacter :</p>
                            <ul>
                                <li>Par email : <a href="mailto:businesscareams@gmail.com">businesscareams@gmail.com</a>
                                </li>
                                <li>Par courrier : Business Care - Service Protection des Données, 110 rue de Rivoli,
                                    75001 Paris</li>
                                <li>Par téléphone : 07 68 16 39 48</li>
                            </ul>
                            <p>Notre Délégué à la Protection des Données peut être contacté à l'adresse : <a
                                    href="mailto:businesscareams@gmail.com">businesscareams@gmail.com</a></p>
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