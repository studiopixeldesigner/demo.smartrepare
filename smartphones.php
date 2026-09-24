<?php
$json_file = 'tarifs.json';

// Structure par défaut si le fichier JSON n'existe pas encore
$default_tarifs = [
    "iphone-x" => [
        "title" => "Gamme iPhone X",
        "models" => [
            "iPhone X" => ["Écran" => 59, "Batterie" => 35, "Connecteur de charge" => 35, "Vitre arrière" => 45, "Écouteur interne" => 30],
            "iPhone XS" => ["Écran" => 65, "Batterie" => 35, "Connecteur de charge" => 35, "Vitre arrière" => 49, "Écouteur interne" => 30],
            "iPhone XR" => ["Écran" => 59, "Batterie" => 35, "Connecteur de charge" => 35, "Vitre arrière" => 45, "Écouteur interne" => 30],
            "iPhone XS Max" => ["Écran" => 79, "Batterie" => 39, "Connecteur de charge" => 39, "Vitre arrière" => 49, "Écouteur interne" => 30]
        ]
    ],
    "iphone-11" => [
        "title" => "Gamme iPhone 11",
        "models" => [
            "iPhone 11" => ["Écran" => 69, "Batterie" => 39, "Connecteur de charge" => 39, "Vitre arrière" => 49, "Écouteur interne" => 30],
            "iPhone 11 Pro" => ["Écran" => 89, "Batterie" => 45, "Connecteur de charge" => 45, "Vitre arrière" => 59, "Écouteur interne" => 35],
            "iPhone 11 Pro Max" => ["Écran" => 99, "Batterie" => 45, "Connecteur de charge" => 45, "Vitre arrière" => 69, "Écouteur interne" => 35]
        ]
    ],
    "iphone-12" => [
        "title" => "Gamme iPhone 12",
        "models" => [
            "iPhone 12 Mini" => ["Écran" => 79, "Batterie" => 45, "Connecteur de charge" => 39, "Vitre arrière" => 59, "Écouteur interne" => 35],
            "iPhone 12" => ["Écran" => 89, "Batterie" => 45, "Connecteur de charge" => 39, "Vitre arrière" => 59, "Écouteur interne" => 35],
            "iPhone 12 Pro" => ["Écran" => 89, "Batterie" => 45, "Connecteur de charge" => 39, "Vitre arrière" => 69, "Écouteur interne" => 35],
            "iPhone 12 Pro Max" => ["Écran" => 119, "Batterie" => 49, "Connecteur de charge" => 45, "Vitre arrière" => 69, "Écouteur interne" => 35]
        ]
    ],
    "iphone-13" => [
        "title" => "Gamme iPhone 13",
        "models" => [
            "iPhone 13 Mini" => ["Écran" => 89, "Batterie" => 49, "Connecteur de charge" => 45, "Vitre arrière" => 69, "Écouteur interne" => 35],
            "iPhone 13" => ["Écran" => 99, "Batterie" => 49, "Connecteur de charge" => 45, "Vitre arrière" => 69, "Écouteur interne" => 35],
            "iPhone 13 Pro" => ["Écran" => 159, "Batterie" => 59, "Connecteur de charge" => 49, "Vitre arrière" => 79, "Écouteur interne" => 39],
            "iPhone 13 Pro Max" => ["Écran" => 179, "Batterie" => 59, "Connecteur de charge" => 49, "Vitre arrière" => 89, "Écouteur interne" => 39]
        ]
    ],
    "iphone-14" => [
        "title" => "Gamme iPhone 14",
        "models" => [
            "iPhone 14" => ["Écran" => 119, "Batterie" => 59, "Connecteur de charge" => 49, "Vitre arrière" => 79, "Écouteur interne" => 39],
            "iPhone 14 Plus" => ["Écran" => 139, "Batterie" => 59, "Connecteur de charge" => 49, "Vitre arrière" => 89, "Écouteur interne" => 39],
            "iPhone 14 Pro" => ["Écran" => 179, "Batterie" => 69, "Connecteur de charge" => 59, "Vitre arrière" => 99, "Écouteur interne" => 45],
            "iPhone 14 Pro Max" => ["Écran" => 199, "Batterie" => 69, "Connecteur de charge" => 59, "Vitre arrière" => 109, "Écouteur interne" => 45]
        ]
    ],
    "iphone-15" => [
        "title" => "Gamme iPhone 15",
        "models" => [
            "iPhone 15" => ["Écran" => 139, "Batterie" => 69, "Connecteur de charge" => 59, "Vitre arrière" => 89, "Écouteur interne" => 45],
            "iPhone 15 Plus" => ["Écran" => 159, "Batterie" => 69, "Connecteur de charge" => 59, "Vitre arrière" => 99, "Écouteur interne" => 45],
            "iPhone 15 Pro" => ["Écran" => 199, "Batterie" => 79, "Connecteur de charge" => 69, "Vitre arrière" => 109, "Écouteur interne" => 49],
            "iPhone 15 Pro Max" => ["Écran" => 229, "Batterie" => 79, "Connecteur de charge" => 69, "Vitre arrière" => 119, "Écouteur interne" => 49]
        ]
    ],
    "iphone-16" => [
        "title" => "Gamme iPhone 16",
        "models" => [
            "iPhone 16e" => ["Écran" => 119, "Batterie" => 69, "Connecteur de charge" => 59, "Vitre arrière" => 79, "Écouteur interne" => 39],
            "iPhone 16" => ["Écran" => 149, "Batterie" => 75, "Connecteur de charge" => 65, "Vitre arrière" => 89, "Écouteur interne" => 45],
            "iPhone 16 Plus" => ["Écran" => 169, "Batterie" => 75, "Connecteur de charge" => 65, "Vitre arrière" => 99, "Écouteur interne" => 45],
            "iPhone 16 Pro" => ["Écran" => 209, "Batterie" => 85, "Connecteur de charge" => 75, "Vitre arrière" => 119, "Écouteur interne" => 49],
            "iPhone 16 Pro Max" => ["Écran" => 239, "Batterie" => 85, "Connecteur de charge" => 75, "Vitre arrière" => 129, "Écouteur interne" => 49]
        ]
    ],
    "iphone-17" => [
        "title" => "Gamme iPhone 17",
        "models" => [
            "iPhone 17e" => ["Écran" => 129, "Batterie" => 69, "Connecteur de charge" => 59, "Vitre arrière" => 89, "Écouteur interne" => 39],
            "iPhone 17" => ["Écran" => 159, "Batterie" => 79, "Connecteur de charge" => 69, "Vitre arrière" => 99, "Écouteur interne" => 49],
            "iPhone Air" => ["Écran" => 179, "Batterie" => 79, "Connecteur de charge" => 69, "Vitre arrière" => 109, "Écouteur interne" => 49],
            "iPhone 17 Pro" => ["Écran" => 219, "Batterie" => 89, "Connecteur de charge" => 79, "Vitre arrière" => 119, "Écouteur interne" => 55],
            "iPhone 17 Pro Max" => ["Écran" => 249, "Batterie" => 89, "Connecteur de charge" => 79, "Vitre arrière" => 129, "Écouteur interne" => 55]
        ]
    ]
];

$from_prices = [];
if (file_exists($json_file)) {
    $tarifs_loaded = json_decode(file_get_contents($json_file), true);
    // Gestion compatible si le JSON vient de l'admin avec la clé 'gammes' ou direct
    if (isset($tarifs_loaded['reparation-smartphone']['gammes'])) {
        $tarifs = $tarifs_loaded['reparation-smartphone']['gammes'];
        // Réparations cochées « À partir de » dans admin.php : gamme => modèle => [réparations]
        $from_prices = $tarifs_loaded['reparation-smartphone']['from_prices'] ?? [];
    } elseif (is_array($tarifs_loaded) && !empty($tarifs_loaded)) {
        $tarifs = $tarifs_loaded;
    } else {
        $tarifs = $default_tarifs;
    }
} else {
    $tarifs = $default_tarifs;
    file_put_contents($json_file, json_encode($default_tarifs, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Réparation Smartphones</title>
    <link rel="stylesheet" href="style.css?v=3">
    <link rel="icon" type="image/png" href="favicon.png?v=2">
    <style>
        body { background-color: #0b0f17; color: #e2e8f0; }
        .discreet-card { background: linear-gradient(135deg, rgba(30, 41, 59, 0.7), rgba(15, 23, 42, 0.9)); border: 1px solid rgba(16, 185, 129, 0.3); border-radius: 12px; padding: 18px 24px; display: flex; align-items: center; justify-content: space-between; gap: 15px; margin: 0 auto 40px auto; max-width: 850px; box-shadow: 0 4px 20px rgba(0, 0, 0, 0.4), inset 0 0 15px rgba(16, 185, 129, 0.05); backdrop-filter: blur(10px); transition: border-color 0.3s ease, box-shadow 0.3s ease; }
        .discreet-card:hover { border-color: rgba(16, 185, 129, 0.7); box-shadow: 0 0 25px rgba(16, 185, 129, 0.2); }
        .discreet-card__text { font-size: 0.95rem; color: #cbd5e1; }
        .discreet-card__text strong { color: #10b981; }
        .discreet-btn { background: transparent; color: #10b981; border: 1px solid #10b981; padding: 8px 18px; border-radius: 6px; font-size: 0.88rem; font-weight: 600; text-decoration: none; white-space: nowrap; transition: all 0.3s ease; }
        .discreet-btn:hover { background: #10b981; color: #0b0f17; box-shadow: 0 0 15px rgba(16, 185, 129, 0.6); }
        .gammes-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 25px; margin-bottom: 50px; }
        .gamme-card { position: relative; background: linear-gradient(145deg, #182232, #0f172a); border: 1px solid rgba(255, 255, 255, 0.08); border-radius: 16px; padding: 35px 25px; text-align: center; cursor: pointer; overflow: hidden; transition: all 0.35s cubic-bezier(0.4, 0, 0.2, 1); box-shadow: 0 6px 20px rgba(0, 0, 0, 0.35); }
        .gamme-card::before { content: ''; position: absolute; top: 0; left: -100%; width: 100%; height: 100%; background: linear-gradient(90deg, transparent, rgba(16, 185, 129, 0.15), transparent); transition: left 0.6s ease; }
        .gamme-card:hover::before { left: 100%; }
        .gamme-card:hover { transform: translateY(-6px) scale(1.02); border-color: #10b981; box-shadow: 0 12px 30px rgba(16, 185, 129, 0.25), 0 0 20px rgba(16, 185, 129, 0.2); }
        .gamme-card.active { background: linear-gradient(145deg, #1e293b, #111827); border-color: #10b981; box-shadow: 0 0 25px rgba(16, 185, 129, 0.4), inset 0 0 12px rgba(16, 185, 129, 0.15); }
        .gamme-card h3 { font-size: 1.4rem; margin-bottom: 8px; color: #f8fafc; font-weight: 700; }
        .gamme-card span { font-size: 0.9rem; color: #94a3b8; display: block; margin-bottom: 16px; }
        .gamme-card .badge-btn { display: inline-block; font-size: 0.88rem; font-weight: 600; color: #10b981; letter-spacing: 0.5px; transition: color 0.3s; }
        .gamme-card:hover .badge-btn, .gamme-card.active .badge-btn { color: #34d399; text-shadow: 0 0 8px rgba(52, 211, 153, 0.5); }
        .gamme-panel { display: none; padding-top: 10px; scroll-margin-top: 30px; animation: slideUpFade 0.4s ease-out forwards; }
        .gamme-panel.active { display: block; }
        @keyframes slideUpFade { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
        .models-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(260px, 1fr)); gap: 22px; }
        .model-card { background: linear-gradient(145deg, #161f2e, #0e1524); border: 1px solid rgba(255, 255, 255, 0.07); border-radius: 12px; padding: 22px; box-shadow: 0 4px 15px rgba(0, 0, 0, 0.4); transition: border-color 0.3s ease; }
        .model-card:hover { border-color: rgba(16, 185, 129, 0.4); }
        .model-card h4 { font-size: 1.15rem; margin-bottom: 14px; padding-bottom: 8px; border-bottom: 2px solid #10b981; color: #f1f5f9; }
        .tarif-table { width: 100%; border-collapse: collapse; font-size: 0.9rem; }
        .tarif-table td { padding: 9px 0; border-bottom: 1px dashed rgba(255, 255, 255, 0.1); color: #94a3b8; }
        .tarif-table tr:last-child td { border-bottom: none; }
        .tarif-table td:last-child { text-align: right; font-weight: 700; color: #10b981; font-size: 0.98rem; }
        .tarifs-note { display: flex; align-items: center; justify-content: center; gap: 10px; max-width: 850px; margin: -12px auto 30px auto; padding: 12px 18px; border-radius: 10px; background: rgba(16, 185, 129, 0.1); border: 1px solid rgba(16, 185, 129, 0.35); color: #cbd5e1; font-size: 0.95rem; text-align: center; }
        .tarifs-note strong { color: #10b981; }
        .tarifs-note svg { width: 20px; height: 20px; stroke: #10b981; fill: none; flex-shrink: 0; }
        .tarif-table .price-from { font-size: 0.78rem; font-weight: 500; color: #94a3b8; margin-right: 2px; }
        .qualirepar-card { background: linear-gradient(135deg, rgba(30, 41, 59, 0.7), rgba(15, 23, 42, 0.9)); border: 1px solid rgba(16, 185, 129, 0.3); border-radius: 16px; padding: 30px; display: flex; align-items: center; justify-content: space-between; gap: 30px; margin: 0 auto 40px auto; max-width: 850px; box-shadow: 0 8px 32px rgba(0, 0, 0, 0.4), inset 0 0 15px rgba(16, 185, 129, 0.05); backdrop-filter: blur(10px); }
        .qualirepar-card__content { flex: 1; }
        .qualirepar-badge { display: inline-block; background: rgba(16, 185, 129, 0.15); border: 1px solid rgba(16, 185, 129, 0.4); color: #10b981; font-size: 0.75rem; font-weight: 700; letter-spacing: 1px; padding: 4px 12px; border-radius: 20px; margin-bottom: 15px; text-transform: uppercase; }
        .qualirepar-card__title { font-size: 1.45rem; font-weight: 700; color: #f8fafc; line-height: 1.3; margin-bottom: 12px; }
        .qualirepar-card__title span { color: #10b981; }
        .qualirepar-card__text { font-size: 0.9rem; color: #94a3b8; line-height: 1.5; margin-bottom: 20px; }
        .qualirepar-card__features { display: flex; flex-wrap: wrap; gap: 15px; font-size: 0.85rem; font-weight: 600; color: #e2e8f0; }
        .qualirepar-card__feature-item { display: flex; align-items: center; gap: 6px; }
        .qualirepar-card__feature-item span { color: #10b981; font-weight: bold; }
        .qualirepar-card__image { flex: 0 0 280px; }
        .qualirepar-card__image img { width: 100%; height: auto; border-radius: 12px; box-shadow: 0 4px 20px rgba(0, 0, 0, 0.5); display: block; }
        @media (max-width: 768px) {
            .qualirepar-card { flex-direction: column-reverse; text-align: center; padding: 20px; }
            .qualirepar-card__image { flex: 0 0 auto; width: 100%; max-width: 300px; }
            .qualirepar-card__features { justify-content: center; }
        }
    </style>
</head>
<body>

    <!-- HEADER -->
    <header class="header">
        <div class="container header__wrapper">
            <a href="index.html"><img src="images/logo_sr.webp" alt="Logo" class="header__logo"></a>
            
            <nav class="nav" id="nav-menu">
                <ul class="nav__list">
                    <li><a href="index.html" class="nav__link">Accueil</a></li>
                    <li><a href="index.html#services" class="nav__link">Services & Tarifs</a></li>
                    <li><a href="contact.html" class="nav__link">Contact & Devis</a></li>
                    <li><a href="a-propos.html" class="nav__link">À Propos</a></li>
                </ul>
            </nav>

            <a href="contact.html" class="btn btn--primary header__btn">Devis gratuit</a>

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
            <h1 class="section-title" style="margin-top: 10px; color: #f8fafc;">Réparation Smartphones & iPhone</h1>
        </div>
    </section>

    <section class="section" style="padding-top: 0;">
        <div class="container">

            <!-- CASE DISCRÈTE AUTRES MARQUES -->
            <div class="discreet-card">
                <div class="discreet-card__text">
                    <strong>Samsung, Xiaomi, Google ou autre marque ?</strong> Nous réparons tous les modèles sur devis immédiat.
                </div>
                <a href="contact.html" class="discreet-btn">Demander un tarif →</a>
            </div>

            <!-- BLOC QUALIRÉPAR -->
            <div class="qualirepar-card">
                <div class="qualirepar-card__content">
                    <div class="qualirepar-badge">LABELLISÉ QUALIRÉPAR</div>
                    <h3 class="qualirepar-card__title">
                        Réparez votre téléphone et économisez <span>25 € sur le champ</span>
                    </h3>
                    <p class="qualirepar-card__text">
                        Grâce au label QualiRépar, bénéficiez d'une réduction immédiate de 25 € appliquée directement sur votre facture de réparation. Pas de démarches complexes : nous nous occupons de tout !
                    </p>
                    <div class="qualirepar-card__features">
                        <div class="qualirepar-card__feature-item"><span>✓</span> Réduction immédiate en caisse</div>
                        <div class="qualirepar-card__feature-item"><span>✓</span> Réparation éco-responsable</div>
                        <div class="qualirepar-card__feature-item"><span>✓</span> Prise en charge directe</div>
                    </div>
                </div>
                <div class="qualirepar-card__image">
                    <img src="images/label_telephone.png" alt="Réduction QualiRépar Téléphone">
                </div>
            </div>

            <h2 style="margin-bottom: 30px; font-size: 1.4rem; color: #cbd5e1; text-align: center;">Sélectionnez votre gamme d'iPhone :</h2>

            <!-- MENTION : TARIFS AVEC RÉDUCTION QUALIRÉPAR -->
            <p class="tarifs-note">
                <svg viewBox="0 0 24 24" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                <span>Les tarifs affichés comprennent déjà la réduction QualiRépar de <strong>25 €</strong>.</span>
            </p>

            <!-- CARTES DE GAMMES DYNAMIQUES -->
            <div class="gammes-grid">
                <?php 
                $isFirst = true;
                foreach ($tarifs as $gammeKey => $gammeData): 
                    $gammeTitle = is_array($gammeData) && isset($gammeData['title']) ? $gammeData['title'] : $gammeKey;
                    $models = is_array($gammeData) && isset($gammeData['models']) ? $gammeData['models'] : (is_array($gammeData) ? $gammeData : []);
                    $modelNames = is_array($models) ? array_keys($models) : [];
                    $shortNames = array_map(function($name) {
                        return str_replace(['iPhone ', 'Gamme '], '', $name);
                    }, $modelNames);
                    $subTitle = implode(' / ', $shortNames);
                    $safeId = preg_replace('/[^a-zA-Z0-9_-]/', '-', $gammeKey);
                ?>
                    <div class="gamme-card <?= $isFirst ? 'active' : '' ?>" onclick="openGamme('<?= htmlspecialchars($safeId) ?>', this)">
                        <h3><?= htmlspecialchars($gammeTitle) ?></h3>
                        <span><?= htmlspecialchars($subTitle) ?></span>
                        <span class="badge-btn">Voir les tarifs ↓</span>
                    </div>
                <?php 
                    $isFirst = false;
                endforeach; 
                ?>
            </div>

            <!-- PANNEAUX DE DÉTAILS GENERÉS DYNAMIQUEMENT -->
            <?php 
            $isFirst = true;
            foreach ($tarifs as $gammeKey => $gammeData): 
                $gammeTitle = is_array($gammeData) && isset($gammeData['title']) ? $gammeData['title'] : $gammeKey;
                $models = is_array($gammeData) && isset($gammeData['models']) ? $gammeData['models'] : (is_array($gammeData) ? $gammeData : []);
                $safeId = preg_replace('/[^a-zA-Z0-9_-]/', '-', $gammeKey);
            ?>
                <div id="<?= htmlspecialchars($safeId) ?>" class="gamme-panel <?= $isFirst ? 'active' : '' ?>">
                    <h3 style="margin-bottom: 20px; font-size: 1.3rem; color: #10b981;">Tarifs - <?= htmlspecialchars($gammeTitle) ?></h3>
                    <div class="models-grid">
                        <?php if (is_array($models)): foreach ($models as $modelName => $repairs): ?>
                            <div class="model-card">
                                <h4><?= htmlspecialchars($modelName) ?></h4>
                                <table class="tarif-table">
                                    <?php if (is_array($repairs)): foreach ($repairs as $repairType => $price): ?>
                                        <tr>
                                            <td><?= htmlspecialchars($repairType) ?></td>
                                            <td>
                                                <?php if ((int)$price === 0): ?>
                                                    <span style="font-size: 0.82rem; font-weight: 500; color: #94a3b8;">Tarif disponible en boutique</span>
                                                <?php else: ?>
                                                    <?php if (in_array((string)$repairType, $from_prices[$gammeKey][$modelName] ?? [], true)): ?>
                                                        <span class="price-from">À partir de</span>
                                                    <?php endif; ?>
                                                    <?= (int)$price ?> €
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; endif; ?>
                                </table>
                            </div>
                        <?php endforeach; endif; ?>
                    </div>
                </div>
            <?php 
                $isFirst = false;
            endforeach; 
            ?>

        </div>
    </section>

    <!-- SCRIPT JS -->
    <script>
        function openGamme(gammeId, element) {
            const panels = document.querySelectorAll('.gamme-panel');
            panels.forEach(p => p.classList.remove('active'));

            const cards = document.querySelectorAll('.gamme-card');
            cards.forEach(c => c.classList.remove('active'));

            const targetPanel = document.getElementById(gammeId);
            if (targetPanel) {
                targetPanel.classList.add('active');
                element.classList.add('active');
                targetPanel.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }
        }
    </script>

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