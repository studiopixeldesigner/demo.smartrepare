<?php require __DIR__ . '/tarifs-services.php'; ?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Réparation Ordinateurs</title>
    <link rel="stylesheet" href="style.css?v=3">
    <link rel="icon" type="image/png" href="favicon.png?v=2">
    <style>
        /* BASE & THÈME SOMBRE */
        body {
            background-color: #0b0f17;
            color: #e2e8f0;
        }

        /* BANNIÈRE QUALIRÉPAR */
        .qualirepar-banner {
            background: linear-gradient(135deg, rgba(16, 185, 129, 0.12) 0%, rgba(15, 23, 42, 0.95) 100%);
            border: 1px solid rgba(16, 185, 129, 0.4);
            border-radius: 16px;
            padding: 25px 30px;
            margin: 0 auto 45px auto;
            max-width: 1100px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.5), inset 0 0 20px rgba(16, 185, 129, 0.08);
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 30px;
            backdrop-filter: blur(10px);
        }

        .qualirepar-content {
            flex: 1;
        }

        .qualirepar-badge {
            display: inline-block;
            background: rgba(16, 185, 129, 0.2);
            color: #34d399;
            font-size: 0.82rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            padding: 5px 12px;
            border-radius: 20px;
            border: 1px solid rgba(16, 185, 129, 0.4);
            margin-bottom: 12px;
        }

        .qualirepar-content h2 {
            font-size: 1.8rem;
            color: #f8fafc;
            margin-bottom: 10px;
            line-height: 1.2;
        }

        .qualirepar-content h2 span {
            color: #10b981;
        }

        .qualirepar-content p {
            color: #94a3b8;
            font-size: 0.98rem;
            line-height: 1.5;
            margin-bottom: 15px;
        }

        .qualirepar-features {
            display: flex;
            gap: 20px;
            flex-wrap: wrap;
        }

        .qualirepar-feature-item {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 0.88rem;
            color: #cbd5e1;
            font-weight: 600;
        }

        .qualirepar-feature-item svg {
            width: 18px;
            height: 18px;
            stroke: #10b981;
            flex-shrink: 0;
        }

        /* ENCADRÉ IMAGE QUALIRÉPAR */
        .qualirepar-img-wrapper {
            width: 320px;
            max-width: 100%;
            border-radius: 12px;
            overflow: hidden;
            border: 1px solid rgba(255, 255, 255, 0.15);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.4);
            flex-shrink: 0;
            background: #000;
        }

        .qualirepar-img-wrapper img {
            width: 100%;
            height: auto;
            display: block;
            object-fit: cover;
        }

        /* SECTIONS DE PRESTATIONS */
        .services-section-title {
            font-size: 1.5rem;
            color: #f8fafc;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 12px;
            border-bottom: 2px solid rgba(16, 185, 129, 0.3);
            padding-bottom: 10px;
        }

        .services-section-title svg {
            width: 28px;
            height: 28px;
            stroke: #10b981;
            fill: none;
            flex-shrink: 0;
        }

        .services-block {
            background: linear-gradient(145deg, #161f2e, #0e1524);
            border: 1px solid rgba(255, 255, 255, 0.07);
            border-radius: 16px;
            padding: 30px;
            margin-bottom: 40px;
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.35);
        }

        .services-list-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(min(360px, 100%), 1fr));
            gap: 24px;
        }

        .service-item {
            background: rgba(15, 23, 42, 0.6);
            border: 1px solid rgba(255, 255, 255, 0.05);
            border-radius: 10px;
            padding: 22px 24px;
            display: flex;
            align-items: flex-start;
            gap: 16px;
            transition: border-color 0.3s ease, transform 0.2s ease;
        }

        .service-item:hover {
            border-color: rgba(16, 185, 129, 0.4);
            transform: translateY(-2px);
        }

        .service-icon {
            width: 26px;
            height: 26px;
            flex-shrink: 0;
            margin-top: 2px;
        }

        .service-icon svg {
            width: 100%;
            height: 100%;
            stroke: #10b981;
            fill: none;
        }

        .service-text h4 {
            font-size: 1.05rem;
            color: #f1f5f9;
            margin-bottom: 4px;
            font-weight: 600;
        }

        .service-text p {
            font-size: 0.88rem;
            color: #94a3b8;
            line-height: 1.4;
            margin: 0;
        }

        .service-text {
            flex: 1;
        }

        .service-text h4 {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 8px;
        }

        .service-price {
            font-size: 0.85rem;
            color: #10b981;
            background: rgba(16, 185, 129, 0.12);
            padding: 2px 8px;
            border-radius: 20px;
            font-weight: 600;
            white-space: nowrap;
        }

        .services-empty {
            color: #94a3b8;
            text-align: center;
            margin: 0;
        }

        /* Carte de service : nom, puis tarif + mention QualiRépar, puis description */
        .service-text h4 {
            display: block;
            font-size: 1.08rem;
            line-height: 1.35;
            margin-bottom: 10px;
        }

        .service-price-row {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            gap: 8px;
            margin-bottom: 12px;
        }

        .service-price-row:last-child {
            margin-bottom: 0;
        }

        .service-price {
            font-size: 0.92rem;
            padding: 4px 12px;
        }

        .service-qualirepar {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-size: 0.8rem;
            font-weight: 600;
            color: #34d399;
            background: rgba(16, 185, 129, 0.06);
            border: 1px solid rgba(16, 185, 129, 0.45);
            padding: 3px 10px;
            border-radius: 20px;
            max-width: 100%;
        }

        .service-qualirepar svg {
            width: 13px;
            height: 13px;
            flex-shrink: 0;
        }

        .service-text p {
            font-size: 0.9rem;
            line-height: 1.55;
        }

        /* Petits écrans : un peu moins de marges pour laisser la place au contenu */
        @media (max-width: 480px) {
            .services-block {
                padding: 20px 16px;
            }

            .service-item {
                padding: 18px 16px;
                gap: 12px;
            }
        }

        /* CALL TO ACTION BOTTOM */
        .cta-box {
            background: linear-gradient(135deg, #182232, #0f172a);
            border: 1px solid #10b981;
            border-radius: 16px;
            padding: 35px;
            text-align: center;
            margin-top: 50px;
            box-shadow: 0 0 25px rgba(16, 185, 129, 0.15);
        }

        .cta-box h3 {
            font-size: 1.5rem;
            color: #f8fafc;
            margin-bottom: 10px;
        }

        .cta-box p {
            color: #94a3b8;
            max-width: 600px;
            margin: 0 auto 20px auto;
            font-size: 0.95rem;
        }

        /* RESPONSIVE */
        @media (max-width: 850px) {
            .qualirepar-banner {
                flex-direction: column;
                text-align: center;
            }
            .qualirepar-features {
                justify-content: center;
            }
            .qualirepar-img-wrapper {
                width: 100%;
            }
        }
    </style>
</head>
<body>

    <!-- HEADER -->
    <header class="header">
    <div class="container header__wrapper">
        <a href="index.html"><img src="images/logo_sr.webp" alt="Logo" class="header__logo"></a>
        
        <!-- 1. Ajout de id="nav-menu" -->
        <nav class="nav" id="nav-menu">
            <ul class="nav__list">
                <li><a href="index.html" class="nav__link">Accueil</a></li>
                <li><a href="index.html#services" class="nav__link">Services & Tarifs</a></li>
                <li><a href="contact.html" class="nav__link">Contact & Devis</a></li>
                <li><a href="a-propos.html" class="nav__link">À Propos</a></li>
            </ul>
        </nav>

        <!-- 2. Ajout de la classe header__btn (pour le masquer sur mobile) -->
        <a href="contact.html" class="btn btn--primary header__btn">Devis gratuit</a>

        <!-- 3. Ajout du bouton burger -->
        <button class="burger" id="burger-menu" aria-label="Ouvrir le menu">
            <span></span>
            <span></span>
            <span></span>
        </button>
    </div>
</header>

    <!-- BANNIÈRE EN-TÊTE -->
    <section class="section" style="padding: 40px 0 20px 0;">
        <div class="container text-center">
            <a href="index.html#services" style="color: #10b981; text-decoration: none; font-weight: 600; font-size: 0.9rem;">← Retour aux prestations</a>
            <h1 class="section-title" style="margin-top: 10px; color: #f8fafc;">Dépannage & Réparation Informatique</h1>
        </div>
    </section>

    <section class="section" style="padding-top: 0;">
        <div class="container">

            <!-- AFFICHAGE QUALIRÉPAR (50€ DE RÉDUCTION) -->
            <div class="qualirepar-banner">
                <div class="qualirepar-content">
                    <span class="qualirepar-badge">Labellisé QualiRépar</span>
                    <h2>Réparez votre ordinateur et économisez <span>50 € sur le champ</span></h2>
                    <p>Grâce au label QualiRépar, bénéficiez d'une réduction immédiate de 50 € appliquée directement sur votre facture de réparation. Pas de démarches complexes : nous nous occupons de tout !</p>
                    <div class="qualirepar-features">
                        <div class="qualirepar-feature-item">
                            <svg viewBox="0 0 24 24" fill="none" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                            Réduction immédiate en caisse
                        </div>
                        <div class="qualirepar-feature-item">
                            <svg viewBox="0 0 24 24" fill="none" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                            Réparation éco-responsable
                        </div>
                        <div class="qualirepar-feature-item">
                            <svg viewBox="0 0 24 24" fill="none" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                            Prise en charge directe
                        </div>
                    </div>
                </div>
                <div class="qualirepar-img-wrapper">
                    <img src="images/label_ordinateur.png" alt="Label QualiRépar - 50€ de réduction sur la réparation ordinateur">
                </div>
            </div>

            <!-- PRESTATIONS (gérées depuis admin.php) -->
            <div class="services-block">
                <h3 class="services-section-title">
                    <svg viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="3" width="20" height="14" rx="2" ry="2"/><line x1="8" y1="21" x2="16" y2="21"/><line x1="12" y1="17" x2="12" y2="21"/></svg>
                    Nos prestations informatiques
                </h3>
                <?php render_category_services('reparation-ordinateur'); ?>
            </div>

            <!-- APPEL À L'ACTION -->
            <div class="cta-box">
                <h3>Une question ou besoin d'un devis précis ?</h3>
                <p>Chaque modèle de PC et chaque panne étant unique, nous réalisons un diagnostic gratuit en atelier afin de vous proposer le tarif le plus juste.</p>
                <a href="contact.html" class="btn btn--secondary">Demander un devis sur-mesure</a>
            </div>

        </div>
    </section>

    <!-- FOOTER -->
    <footer class="footer">
        <div class="container footer__grid">
            <div class="footer__col">
                <img src="images/Logo-Bonne-resolution.webp" alt="Smart Repare Logo" class="footer__logo">
                <p class="footer__description">Votre atelier de référence pour la réparation de téléphones, ordinateurs, consoles et travaux de microsoudure à Vesoul.</p>
            </div>

            <div class="footer__col">
                <h4 class="footer__title">Navigation</h4>
                <ul class="footer__links">
                    <li><a href="index.html">Accueil</a></li>
                    <li><a href="index.html#services">Services & Tarifs</a></li>
                    <li><a href="contact.html">Contact & Devis</a></li>
                    <li><a href="a-propos.html">À Propos</a></li>
                </ul>
            </div>

            <div class="footer__col">
                <h4 class="footer__title">Contact</h4>
                <p class="footer__contact-item">
                    <svg class="icon footer__icon" viewBox="0 0 24 24"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" cy="10" r="3"></circle></svg>
                    <span>16 Av. de la Gare, 70000 Vesoul</span>
                </p>
                <p class="footer__contact-item">
                    <svg class="icon footer__icon" viewBox="0 0 24 24"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path></svg>
                    <a href="tel:0981035461">09 81 03 54 61</a>
                </p>
                <p class="footer__contact-item">
                    <svg class="icon footer__icon" viewBox="0 0 24 24"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline></svg>
                    <a href="mailto:contact@smart-repare.fr">contact@smart-repare.fr</a>
                </p>
            </div>

            <div class="footer__col">
                <h4 class="footer__title">Réseaux sociaux</h4>
                <div class="footer__socials">
                    <a href="https://www.facebook.com/Smart.Repare.Vesoul/" target="_blank" rel="noopener" class="social-btn">Facebook</a>
                    <a href="https://www.instagram.com/smart.repare.vesoul/?hl=fr" target="_blank" rel="noopener" class="social-btn">Instagram</a>
                </div>
            </div>
        </div>

        <div class="footer__bottom">
            <div class="container footer__bottom-wrapper">
                <p>&copy; 2026 Smart Repare - Tous droits réservés.</p>
                <ul class="footer__legal-links">
                    <li><a href="mentions-legales.html">Mentions légales</a></li>
                    <li><a href="cgv.html">CGV</a></li>
                </ul>
                <p class="footer__credit">Powered by <a href="https://studiopixeldesigner.github.io/pixeldesigner/" target="_blank" rel="noopener">Pixel Designer</a></p>
            </div>
        </div>
    </footer>
<script src="script.js"></script>
</body>
</html>