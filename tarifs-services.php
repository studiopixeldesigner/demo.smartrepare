<?php
// ============================================================
// Lecture des services gérés depuis admin.php (tarifs.json)
// Utilisé par ordinateurs.php, consoles.php et trottinettes.php
// ============================================================

function load_category_services($cat_key) {
    $json_file = __DIR__ . '/tarifs.json';
    $tarifs = file_exists($json_file) ? json_decode(file_get_contents($json_file), true) : null;
    $cat = $tarifs[$cat_key] ?? [];

    if (isset($cat['services']) && is_array($cat['services'])) {
        return $cat['services'];
    }

    // Ancien format "models" (tant que admin.php n'a pas converti le fichier)
    $services = [];
    foreach ($cat['models'] ?? [] as $name => $prices) {
        $prices = is_array($prices) ? $prices : ['Prix' => $prices];
        $services[] = ['name' => (string)$name, 'price' => (int)(reset($prices) ?: 0), 'description' => ''];
    }
    return $services;
}

// ============================================================
// Icônes disponibles pour les services (choisies dans admin.php)
// Rangées par thème : thème => [clé => [libellé, contenu SVG 24x24 en trait]]
// Ne pas renommer une clé existante : elle est enregistrée dans tarifs.json.
// ============================================================
const DEFAULT_SERVICE_ICON = 'outil';

function service_icon_groups() {
    return [
        'Général' => [
            'outil'        => ['Outil / Réparation', '<path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"/>'],
            'reglages'     => ['Réglages / Entretien', '<circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"/>'],
            'reglage-fin'  => ['Réglage / Calibrage', '<line x1="4" y1="21" x2="4" y2="14"/><line x1="4" y1="10" x2="4" y2="3"/><line x1="12" y1="21" x2="12" y2="12"/><line x1="12" y1="8" x2="12" y2="3"/><line x1="20" y1="21" x2="20" y2="16"/><line x1="20" y1="12" x2="20" y2="3"/><line x1="1" y1="14" x2="7" y2="14"/><line x1="9" y1="8" x2="15" y2="8"/><line x1="17" y1="16" x2="23" y2="16"/>'],
            'diagnostic'   => ['Diagnostic', '<circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/>'],
            'rapport'      => ['Rapport / Diagnostic écrit', '<path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"/><rect x="8" y="2" width="8" height="4" rx="1" ry="1"/>'],
            'inspection'   => ['Inspection / Contrôle visuel', '<path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/>'],
            'precision'    => ['Précision / Microsoudure', '<circle cx="12" cy="12" r="10"/><line x1="22" y1="12" x2="18" y2="12"/><line x1="6" y1="12" x2="2" y2="12"/><line x1="12" y1="6" x2="12" y2="2"/><line x1="12" y1="22" x2="12" y2="18"/>'],
            'test'         => ['Tests / Contrôle', '<polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/>'],
            'alerte'       => ['Panne / Alerte', '<path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/>'],
            'allumage'     => ['Ne s\'allume plus / Démarrage', '<path d="M18.36 6.64a9 9 0 1 1-12.73 0"/><line x1="12" y1="2" x2="12" y2="12"/>'],
            'horloge'      => ['Délai / Express', '<circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/>'],
            'valide'       => ['Garantie / Validé', '<path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/>'],
            'qualite'      => ['Qualité / Label', '<circle cx="12" cy="8" r="7"/><polyline points="8.21 13.89 7 23 12 20 17 23 15.79 13.88"/>'],
            'etoile'       => ['Premium / Recommandé', '<polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/>'],
            'performance'  => ['Upgrade / Performance', '<polyline points="23 6 13.5 15.5 8.5 10.5 1 18"/><polyline points="17 6 23 6 23 12"/>'],
            'ajout'        => ['Ajout / Option', '<circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="16"/><line x1="8" y1="12" x2="16" y2="12"/>'],
        ],
        'Appareils' => [
            'pc-portable'  => ['PC portable / Écran', '<rect x="3" y="4" width="18" height="12" rx="2"/><path d="M2 20h20"/>'],
            'ecran'        => ['PC fixe / Moniteur', '<rect x="2" y="3" width="20" height="14" rx="2"/><line x1="8" y1="21" x2="16" y2="21"/><line x1="12" y1="17" x2="12" y2="21"/>'],
            'serveur'      => ['Tour / Serveur', '<rect x="2" y="2" width="20" height="8" rx="2" ry="2"/><rect x="2" y="14" width="20" height="8" rx="2" ry="2"/><line x1="6" y1="6" x2="6.01" y2="6"/><line x1="6" y1="18" x2="6.01" y2="18"/>'],
            'smartphone'   => ['Smartphone', '<rect x="5" y="2" width="14" height="20" rx="2"/><line x1="12" y1="18" x2="12.01" y2="18"/>'],
            'tablette'     => ['Tablette', '<rect x="3" y="2" width="18" height="20" rx="2"/><line x1="12" y1="18" x2="12.01" y2="18"/>'],
            'montre'       => ['Montre connectée', '<circle cx="12" cy="12" r="7"/><polyline points="12 9 12 12 13.5 13.5"/><path d="M16.51 17.35l-.35 3.83a2 2 0 0 1-2 1.82H9.83a2 2 0 0 1-2-1.82l-.35-3.83m.01-10.7l.35-3.83A2 2 0 0 1 9.83 1h4.35a2 2 0 0 1 2 1.82l.35 3.83"/>'],
            'manette'      => ['Console / Manette', '<rect x="2" y="6" width="20" height="12" rx="6"/><line x1="6" y1="12" x2="10" y2="12"/><line x1="8" y1="10" x2="8" y2="14"/><circle cx="15" cy="13" r="1"/><circle cx="18" cy="11" r="1"/>'],
            'television'   => ['Télévision', '<rect x="2" y="7" width="20" height="15" rx="2" ry="2"/><polyline points="17 2 12 7 7 2"/>'],
            'imprimante'   => ['Imprimante', '<polyline points="6 9 6 2 18 2 18 9"/><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/><rect x="6" y="14" width="12" height="8"/>'],
            'souris'       => ['Souris', '<rect x="5" y="2" width="14" height="20" rx="7"/><path d="M12 6v4"/>'],
            'casque'       => ['Casque audio', '<path d="M3 18v-6a9 9 0 0 1 18 0v6"/><path d="M21 19a2 2 0 0 1-2 2h-1a2 2 0 0 1-2-2v-3a2 2 0 0 1 2-2h3zM3 19a2 2 0 0 0 2 2h1a2 2 0 0 0 2-2v-3a2 2 0 0 0-2-2H3z"/>'],
            'enceinte'     => ['Enceinte', '<rect x="4" y="2" width="16" height="20" rx="2" ry="2"/><circle cx="12" cy="14" r="4"/><line x1="12" y1="6" x2="12.01" y2="6"/>'],
            'telephone'    => ['Téléphone / Appels', '<path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/>'],
            'disque'       => ['Lecteur disque / Blu-ray', '<circle cx="12" cy="12" r="10"/><circle cx="12" cy="12" r="3"/>'],
        ],
        'Composants & pannes' => [
            'batterie'     => ['Batterie', '<rect x="1" y="6" width="18" height="12" rx="2"/><line x1="23" y1="11" x2="23" y2="13"/><line x1="5" y1="10" x2="5" y2="14"/><line x1="9" y1="10" x2="9" y2="14"/>'],
            'recharge'     => ['Recharge / Charge lente', '<path d="M5 18H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h3.19M15 6h2a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2h-3.19"/><line x1="23" y1="13" x2="23" y2="11"/><polyline points="11 6 7 12 13 12 9 18"/>'],
            'prise'        => ['Connecteur / Prise', '<path d="M12 22v-5"/><path d="M9 8V2"/><path d="M15 8V2"/><path d="M18 8v5a4 4 0 0 1-4 4h-4a4 4 0 0 1-4-4V8z"/>'],
            'cable'        => ['Câble / Connexion', '<path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"/><path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"/>'],
            'eclair'       => ['Alimentation / Électricité', '<polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"/>'],
            'affichage'    => ['Écran / Affichage', '<rect x="3" y="3" width="18" height="18" rx="2" ry="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/>'],
            'vitre'        => ['Vitre / Verre trempé', '<polygon points="12 2 2 7 12 12 22 7 12 2"/><polyline points="2 17 12 22 22 17"/><polyline points="2 12 12 17 22 12"/>'],
            'clavier'      => ['Clavier', '<rect x="2" y="4" width="20" height="16" rx="2"/><path d="M6 8h.01M10 8h.01M14 8h.01M18 8h.01M6 12h.01M10 12h.01M14 12h.01M18 12h.01M8 16h8"/>'],
            'stockage'     => ['SSD / Disque dur', '<line x1="22" y1="12" x2="2" y2="12"/><path d="M5.45 5.11L2 12v6a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-6l-3.45-6.89A2 2 0 0 0 16.76 4H7.24a2 2 0 0 0-1.79 1.11z"/><line x1="6" y1="16" x2="6.01" y2="16"/><line x1="10" y1="16" x2="10.01" y2="16"/>'],
            'composant'    => ['Composant / Carte mère', '<rect x="4" y="4" width="16" height="16" rx="2"/><rect x="9" y="9" width="6" height="6"/><line x1="9" y1="1" x2="9" y2="4"/><line x1="15" y1="1" x2="15" y2="4"/><line x1="9" y1="20" x2="9" y2="23"/><line x1="15" y1="20" x2="15" y2="23"/><line x1="20" y1="9" x2="23" y2="9"/><line x1="20" y1="15" x2="23" y2="15"/><line x1="1" y1="9" x2="4" y2="9"/><line x1="1" y1="15" x2="4" y2="15"/>'],
            'ventilateur'  => ['Ventilateur / Nettoyage', '<path d="M12 12c-2.2 0-4-1.8-4-4 0-1.5.8-2.8 2-3.5 1.5 2.5 3.5 3.5 6 3.5M12 12c0 2.2 1.8 4 4 4 1.5 0 2.8-.8 3.5-2-2.5-1.5-3.5-3.5-3.5-6M12 12c0-2.2-1.8-4-4-4-1.5 0-2.8.8-3.5 2 2.5 1.5 3.5 3.5 3.5 6M12 12c2.2 0 4 1.8 4 4 0 1.5-.8 2.8-2 3.5-1.5-2.5-3.5-3.5-6-3.5"/><circle cx="12" cy="12" r="2"/>'],
            'soufflage'    => ['Dépoussiérage / Soufflage', '<path d="M9.59 4.59A2 2 0 1 1 11 8H2m10.59 11.41A2 2 0 1 0 14 16H2m15.73-8.27A2.5 2.5 0 1 1 19.5 12H2"/>'],
            'temperature'  => ['Surchauffe / Pâte thermique', '<path d="M14 14.76V3.5a2.5 2.5 0 0 0-5 0v11.26a4.5 4.5 0 1 0 5 0z"/>'],
            'goutte'       => ['Désoxydation / Liquide', '<path d="M12 2.69l5.66 5.66a8 8 0 1 1-11.31 0z"/>'],
            'etancheite'   => ['Étanchéité', '<path d="M23 12a11.05 11.05 0 0 0-22 0zm-5 7a3 3 0 0 1-6 0v-7"/>'],
            'son'          => ['Son / Haut-parleur', '<polygon points="11 5 6 9 2 9 2 15 6 15 11 19 11 5"/><path d="M15.54 8.46a5 5 0 0 1 0 7.07"/><path d="M19.07 4.93a10 10 0 0 1 0 14.14"/>'],
            'micro'        => ['Micro', '<path d="M12 1a3 3 0 0 0-3 3v8a3 3 0 0 0 6 0V4a3 3 0 0 0-3-3z"/><path d="M19 10v2a7 7 0 0 1-14 0v-2"/><line x1="12" y1="19" x2="12" y2="23"/><line x1="8" y1="23" x2="16" y2="23"/>'],
            'camera'       => ['Caméra / Appareil photo', '<path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"/><circle cx="12" cy="13" r="4"/>'],
            'video'        => ['Caméra vidéo / Webcam', '<polygon points="23 7 16 12 23 17 23 7"/><rect x="1" y="5" width="15" height="14" rx="2" ry="2"/>'],
            'wifi'         => ['Réseau / Wi-Fi', '<path d="M5 12.55a11 11 0 0 1 14.08 0"/><path d="M1.42 9a16 16 0 0 1 21.16 0"/><path d="M8.53 16.11a6 6 0 0 1 6.95 0"/><line x1="12" y1="20" x2="12.01" y2="20"/>'],
            'bluetooth'    => ['Bluetooth', '<polyline points="6.5 6.5 17.5 17.5 12 23 12 1 17.5 6.5 6.5 17.5"/>'],
            'gps'          => ['GPS / Boussole', '<circle cx="12" cy="12" r="10"/><polygon points="16.24 7.76 14.12 14.12 7.76 16.24 9.88 9.88 16.24 7.76"/>'],
        ],
        'Logiciel & données' => [
            'installation' => ['Installation / Réinstallation', '<path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/>'],
            'code'         => ['Logiciel / Pilotes', '<polyline points="16 18 22 12 16 6"/><polyline points="8 6 2 12 8 18"/>'],
            'terminal'     => ['Système / BIOS', '<polyline points="4 17 10 11 4 5"/><line x1="12" y1="19" x2="20" y2="19"/>'],
            'mise-a-jour'  => ['Mise à jour', '<polyline points="23 4 23 10 17 10"/><polyline points="1 20 1 14 7 14"/><path d="M3.51 9a9 9 0 0 1 14.85-3.36L23 10M1 14l4.64 4.36A9 9 0 0 0 20.49 15"/>'],
            'reinitialiser' => ['Réinitialisation', '<polyline points="1 4 1 10 7 10"/><path d="M3.51 15a9 9 0 1 0 2.13-9.36L1 10"/>'],
            'donnees'      => ['Récupération de données', '<ellipse cx="12" cy="5" rx="9" ry="3"/><path d="M21 12c0 1.66-4 3-9 3s-9-1.34-9-3"/><path d="M3 5v14c0 1.66 4 3 9 3s9-1.34 9-3V5"/>'],
            'sauvegarde'   => ['Sauvegarde', '<path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/>'],
            'transfert'    => ['Transfert / Cloud', '<polyline points="16 16 12 12 8 16"/><line x1="12" y1="12" x2="12" y2="21"/><path d="M20.39 18.39A5 5 0 0 0 18 9h-1.26A8 8 0 1 0 3 16.3"/>'],
            'nettoyage'    => ['Nettoyage / Suppression', '<polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/><line x1="10" y1="11" x2="10" y2="17"/><line x1="14" y1="11" x2="14" y2="17"/>'],
            'bouclier'     => ['Virus / Sécurité', '<path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/><polyline points="9 12 11 14 15 10"/>'],
            'cadenas'      => ['Verrouillage / Mot de passe', '<rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/>'],
            'deblocage'    => ['Déblocage', '<rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 9.9-1"/>'],
            'cle'          => ['Clé / Licence', '<path d="M21 2l-2 2m-7.61 7.61a5.5 5.5 0 1 1-7.778 7.778 5.5 5.5 0 0 1 7.777-7.777zm0 0L15.5 7.5m0 0l3 3L22 7l-3-3m-3.5 3.5L19 4"/>'],
            'internet'     => ['Internet', '<circle cx="12" cy="12" r="10"/><line x1="2" y1="12" x2="22" y2="12"/><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"/>'],
        ],
        'Mobilité' => [
            'trottinette'  => ['Trottinette', '<circle cx="5" cy="18" r="2.5"/><circle cx="19" cy="18" r="2.5"/><line x1="7.5" y1="18" x2="16.5" y2="18"/><line x1="19" y1="18" x2="16" y2="4"/><line x1="14" y1="4" x2="18" y2="4"/>'],
            'velo'         => ['Vélo électrique', '<circle cx="18.5" cy="17.5" r="3.5"/><circle cx="5.5" cy="17.5" r="3.5"/><circle cx="15" cy="5" r="1"/><path d="M12 17.5V14l-3-3 4-3 2 3h2"/>'],
            'roue'         => ['Roue / Pneu', '<circle cx="12" cy="12" r="10"/><circle cx="12" cy="12" r="4"/><line x1="12" y1="2" x2="12" y2="8"/><line x1="12" y1="16" x2="12" y2="22"/><line x1="2" y1="12" x2="8" y2="12"/><line x1="16" y1="12" x2="22" y2="12"/>'],
            'frein'        => ['Freins', '<polygon points="7.86 2 16.14 2 22 7.86 22 16.14 16.14 22 7.86 22 2 16.14 2 7.86 7.86 2"/><line x1="8" y1="12" x2="16" y2="12"/>'],
            'compteur'     => ['Compteur / Dashboard', '<path d="M12 14l4-4"/><path d="M3.34 19a10 10 0 1 1 17.32 0"/>'],
            'eclairage'    => ['Éclairage / Phare', '<circle cx="12" cy="12" r="5"/><line x1="12" y1="1" x2="12" y2="3"/><line x1="12" y1="21" x2="12" y2="23"/><line x1="4.22" y1="4.22" x2="5.64" y2="5.64"/><line x1="18.36" y1="18.36" x2="19.78" y2="19.78"/><line x1="1" y1="12" x2="3" y2="12"/><line x1="21" y1="12" x2="23" y2="12"/><line x1="4.22" y1="19.78" x2="5.64" y2="18.36"/><line x1="18.36" y1="5.64" x2="19.78" y2="4.22"/>'],
        ],
        'Services & boutique' => [
            'pieces'       => ['Pièces détachées', '<line x1="16.5" y1="9.4" x2="7.5" y2="4.21"/><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/><polyline points="3.27 6.96 12 12.01 20.73 6.96"/><line x1="12" y1="22.08" x2="12" y2="12"/>'],
            'devis'        => ['Devis', '<path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><polyline points="10 9 9 9 8 9"/>'],
            'conseil'      => ['Conseil / Assistance', '<path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"/>'],
            'question'     => ['Question / Aide', '<circle cx="12" cy="12" r="10"/><path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"/><line x1="12" y1="17" x2="12.01" y2="17"/>'],
            'rendez-vous'  => ['Rendez-vous', '<rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/>'],
            'domicile'     => ['Intervention à domicile', '<path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/>'],
            'atelier'      => ['Atelier / Adresse', '<path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/>'],
            'livraison'    => ['Livraison / Enlèvement', '<rect x="1" y="3" width="15" height="13"/><polygon points="16 8 20 8 23 11 23 16 16 16 16 8"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/>'],
            'boutique'     => ['Vente / Accessoires', '<circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/>'],
            'prix'         => ['Prix / Étiquette', '<path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"/><line x1="7" y1="7" x2="7.01" y2="7"/>'],
            'euro'         => ['Tarif / Euro', '<path d="M4 10h12"/><path d="M4 14h9"/><path d="M19 6a7.7 7.7 0 0 0-5.2-2A7.9 7.9 0 0 0 6 12c0 4.4 3.5 8 7.8 8 2 0 3.8-.8 5.2-2"/>'],
            'reduction'    => ['Réduction / Bonus réparation', '<line x1="19" y1="5" x2="5" y2="19"/><circle cx="6.5" cy="6.5" r="2.5"/><circle cx="17.5" cy="17.5" r="2.5"/>'],
            'paiement'     => ['Paiement', '<rect x="1" y="4" width="22" height="16" rx="2" ry="2"/><line x1="1" y1="10" x2="23" y2="10"/>'],
            'cadeau'       => ['Offert / Cadeau', '<polyline points="20 12 20 22 4 22 4 12"/><rect x="2" y="7" width="20" height="5"/><line x1="12" y1="22" x2="12" y2="7"/><path d="M12 7H7.5a2.5 2.5 0 0 1 0-5C11 2 12 7 12 7z"/><path d="M12 7h4.5a2.5 2.5 0 0 0 0-5C13 2 12 7 12 7z"/>'],
            'satisfaction' => ['Satisfaction', '<path d="M14 9V5a3 3 0 0 0-3-3l-4 9v11h11.28a2 2 0 0 0 2-1.7l1.38-9a2 2 0 0 0-2-2.3zM7 22H4a2 2 0 0 1-2-2v-7a2 2 0 0 1 2-2h3"/>'],
        ],
    ];
}

// Toutes les icônes à plat : clé => [libellé, contenu SVG]
function service_icons() {
    static $icons = null;
    if ($icons === null) {
        $icons = [];
        foreach (service_icon_groups() as $group) $icons += $group;
    }
    return $icons;
}

// Retourne une clé d'icône valide (l'icône par défaut si inconnue)
function valid_service_icon($key) {
    return array_key_exists((string)$key, service_icons()) ? (string)$key : DEFAULT_SERVICE_ICON;
}

function service_icon_svg($key) {
    $icons = service_icons();
    return '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">'
        . $icons[valid_service_icon($key)][1] . '</svg>';
}

// $from : option « À partir de » cochée dans admin.php
function format_service_price($price, $from = false) {
    if ((int)$price <= 0) return 'Sur devis';
    return ($from ? 'À partir de ' : '') . (int)$price . ' €';
}

// Affiche la grille des services d'une catégorie
function render_category_services($cat_key) {
    $services = load_category_services($cat_key);

    if (empty($services)): ?>
        <p class="services-empty">Nos tarifs pour cette catégorie seront bientôt disponibles. Contactez-nous pour obtenir un devis.</p>
    <?php return; endif; ?>

    <div class="services-list-grid">
        <?php foreach ($services as $service): ?>
            <div class="service-item">
                <div class="service-icon">
                    <?= service_icon_svg($service['icon'] ?? DEFAULT_SERVICE_ICON) ?>
                </div>
                <div class="service-text">
                    <h4><?= htmlspecialchars($service['name'] ?? '') ?></h4>
                    <div class="service-price-row">
                        <span class="service-price"><?= format_service_price($service['price'] ?? 0, !empty($service['from'])) ?></span>
                        <?php if (!empty($service['qualirepar'])): ?>
                            <span class="service-qualirepar">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                                Réduction QualiRépar appliquée
                            </span>
                        <?php endif; ?>
                    </div>
                    <?php if (!empty($service['description'])): ?>
                        <p><?= nl2br(htmlspecialchars($service['description'])) ?></p>
                    <?php endif; ?>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php
}
