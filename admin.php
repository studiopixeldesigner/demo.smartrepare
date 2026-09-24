<?php
session_start();

// ============================================================
// CONFIGURATION
// ============================================================
// Mot de passe admin : son empreinte est dans config.php (fichier privé, non publié — voir config.example.php)
if (file_exists(__DIR__ . '/config.php')) {
    require __DIR__ . '/config.php';
}
$admin_hash    = defined('ADMIN_PASSWORD_HASH') ? ADMIN_PASSWORD_HASH : '';
$contact_email = "contact@smart-repare.fr";
$json_file     = __DIR__ . '/tarifs.json';

// Icônes des services (partagées avec les pages publiques)
require_once __DIR__ . '/tarifs-services.php';

// ============================================================
// API INTERNE - ENVOI DU CODE DE SECOURS
// ============================================================
if (isset($_GET['action']) && $_GET['action'] === 'send_code') {
    header('Content-Type: application/json');

    $code = rand(100000, 999999);
    $_SESSION['reset_code']   = $code;
    $_SESSION['code_expires'] = time() + (15 * 60);

    $to      = $contact_email;
    $subject = "Code de secours - Administration SMART REPARE";
    $message = "Bonjour,\n\nVoici votre code de secours temporaire pour accéder à l'administration : " . $code . "\n\nCe code est valable pendant 15 minutes.";
    $headers = "From: contact@smart-repare.fr\r\nReply-To: " . $contact_email . "\r\nContent-Type: text/plain; charset=UTF-8";

    if (@mail($to, $subject, $message, $headers)) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => "Impossible d'envoyer l'e-mail depuis le serveur."]);
    }
    exit;
}

// ============================================================
// CHARGEMENT DE TARIFS.JSON (source de vérité pour les catégories)
// ============================================================
$default_tarifs = [
    "reparation-smartphone" => [
        "title"               => "Réparation Smartphone",
        "is_grouped_by_gamme" => true,
        "gammes"              => []
    ],
    "reparation-ordinateur" => [
        "title"    => "Réparation Ordinateur",
        "services" => []
    ],
    "reparation-console" => [
        "title"    => "Réparation Console",
        "services" => []
    ],
    "reparations-trottinettes" => [
        "title"    => "Réparations Trottinettes",
        "services" => []
    ]
];

// Services fixes proposés pour chaque modèle de smartphone
const SMARTPHONE_SERVICES = ['Écran', 'Batterie', 'Connecteur de charge', 'Vitre arrière', 'Écouteur interne'];

if (!file_exists($json_file)) {
    file_put_contents($json_file, json_encode($default_tarifs, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
}

$tarifs = json_decode(file_get_contents($json_file), true);
if (!is_array($tarifs)) {
    $tarifs = $default_tarifs;
}

// Les catégories affichées sont directement celles présentes dans tarifs.json
$categories = [];
foreach ($tarifs as $cat_key => $cat_data) {
    $categories[$cat_key] = $cat_data['title'] ?? $cat_key;
}

function is_grouped_cat($tarifs, $cat_key) {
    return !empty($tarifs[$cat_key]['is_grouped_by_gamme']) || isset($tarifs[$cat_key]['gammes']);
}

function save_tarifs_file($json_file, $tarifs) {
    // Les caractères mal encodés sont remplacés au lieu de faire échouer l'encodage,
    // et le fichier n'est jamais écrasé par un contenu vide.
    $json = json_encode($tarifs, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_INVALID_UTF8_SUBSTITUTE);
    if ($json === false) return false;
    return file_put_contents($json_file, $json, LOCK_EX);
}

// Retourne $name, suffixé « (2) », « (3) »... s'il existe déjà comme clé de $arr
function unique_key($arr, $name) {
    $key = $name;
    $suffix = 2;
    while (isset($arr[$key])) {
        $key = $name . ' (' . $suffix . ')';
        $suffix++;
    }
    return $key;
}

// Déplace une clé d'un tableau associatif d'un cran vers le haut ou le bas
function move_assoc_key($arr, $key, $dir) {
    $keys = array_map('strval', array_keys($arr));
    $pos  = array_search((string)$key, $keys, true);
    if ($pos === false) return $arr;

    $swap = ($dir === 'up') ? $pos - 1 : $pos + 1;
    if (!isset($keys[$swap])) return $arr;

    [$keys[$pos], $keys[$swap]] = [$keys[$swap], $keys[$pos]];
    $moved = [];
    foreach ($keys as $k) $moved[$k] = $arr[$k];
    return $moved;
}

// Vrai si la réparation d'un modèle de smartphone est affichée « À partir de »
function is_from_price($cat, $gamme, $model, $repair) {
    return in_array((string)$repair, $cat['from_prices'][$gamme][$model] ?? [], true);
}

// Sélecteur d'icône d'un service : l'icône actuelle, et au clic une grille de choix (boutons radio)
// Les icônes sont rangées par thème, avec un champ de recherche (sans « name » : jamais envoyé).
function icon_picker($name, $selected) {
    $selected = valid_service_icon($selected);
    $html  = '<details class="icon-picker">';
    $html .= '<summary title="Choisir l\'icône">' . service_icon_svg($selected) . '</summary>';
    $html .= '<div class="icon-picker-panel">';
    $html .= '<input type="search" class="icon-search form-control" placeholder="Rechercher une icône..." autocomplete="off">';
    foreach (service_icon_groups() as $group => $icons) {
        $html .= '<div class="icon-group"><p class="icon-group-title">' . htmlspecialchars($group) . '</p><div class="icon-grid">';
        foreach ($icons as $key => [$label]) {
            $html .= '<label title="' . htmlspecialchars($label) . '" data-label="' . htmlspecialchars($label) . '">'
                   . '<input type="radio" name="' . htmlspecialchars($name) . '" value="' . $key . '"' . ($key === $selected ? ' checked' : '') . '>'
                   . '<span>' . service_icon_svg($key) . '</span></label>';
        }
        $html .= '</div></div>';
    }
    $html .= '<p class="icon-no-result hidden">Aucune icône trouvée.</p>';
    return $html . '</div></details>';
}

// Valeur d'un bouton d'action du formulaire principal (lue par le traitement POST)
function op_value($data) {
    return htmlspecialchars(json_encode($data, JSON_UNESCAPED_UNICODE), ENT_QUOTES);
}

// Garantit qu'un modèle de smartphone possède tous les services standard (dans l'ordre),
// en conservant les éventuels services supplémentaires déjà présents.
function normalize_smartphone_repairs($repairs) {
    $normalized = [];
    foreach (SMARTPHONE_SERVICES as $service) {
        $normalized[$service] = (int)($repairs[$service] ?? 0);
    }
    foreach ($repairs as $service => $price) {
        if (!isset($normalized[$service])) $normalized[$service] = (int)$price;
    }
    return $normalized;
}

// - Catégories groupées par gamme : chaque modèle reçoit les services standard.
// - Autres catégories : l'ancien format "models" est converti en liste de services
//   { name, price, description } modifiables individuellement.
function normalize_tarifs($tarifs) {
    foreach ($tarifs as $cat_key => $cat) {
        if (is_grouped_cat($tarifs, $cat_key)) {
            foreach ($cat['gammes'] ?? [] as $gamme_key => $models) {
                foreach ($models as $model_name => $repairs) {
                    $tarifs[$cat_key]['gammes'][$gamme_key][$model_name] = normalize_smartphone_repairs(is_array($repairs) ? $repairs : []);
                }
            }
        } else {
            if (!isset($cat['services'])) {
                $services = [];
                foreach ($cat['models'] ?? [] as $name => $prices) {
                    $prices = is_array($prices) ? $prices : ['Prix' => $prices];
                    $services[] = [
                        'name'        => (string)$name,
                        'price'       => (int)(reset($prices) ?: 0),
                        'description' => ''
                    ];
                }
                $tarifs[$cat_key]['services'] = $services;
            }
            unset($tarifs[$cat_key]['models']);
        }
    }
    return $tarifs;
}

$normalized_tarifs = normalize_tarifs($tarifs);
if ($normalized_tarifs !== $tarifs) {
    $tarifs = $normalized_tarifs;
    if (isset($_SESSION['admin_logged'])) {
        save_tarifs_file($json_file, $tarifs);
    }
}

$active_cat = $_GET['cat'] ?? null;
if ($active_cat && !isset($categories[$active_cat])) {
    $active_cat = null;
}

$error = "";

// ============================================================
// AUTHENTIFICATION
// ============================================================

// 1. Connexion par mot de passe
if (isset($_POST['password'])) {
    if ($admin_hash === '') {
        $error = "Configuration manquante : le fichier config.php est introuvable (voir config.example.php).";
    } elseif (password_verify($_POST['password'], $admin_hash)) {
        $_SESSION['admin_logged'] = true;
    } else {
        $error = "Mot de passe incorrect.";
    }
}

// 2. Connexion par code de secours
if (isset($_POST['verify_code'])) {
    $user_code = trim($_POST['code'] ?? '');
    if (isset($_SESSION['reset_code']) && isset($_SESSION['code_expires']) && time() <= $_SESSION['code_expires']) {
        if ($user_code == $_SESSION['reset_code']) {
            $_SESSION['admin_logged'] = true;
            unset($_SESSION['reset_code'], $_SESSION['code_expires']);
        } else {
            $error = "Code de secours incorrect.";
        }
    } else {
        $error = "Le code a expiré ou n'a pas été demandé.";
    }
}

// Déconnexion
if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: admin.php");
    exit;
}

$message = "";

// ============================================================
// ACTIONS ADMIN (nécessitent d'être connecté)
// ============================================================
if (isset($_SESSION['admin_logged'])) {

    // ============================================================
    // ENREGISTREMENT + ACTION (créer / supprimer / déplacer)
    // Tous les boutons d'action envoient le formulaire principal : les modifications
    // en cours sont d'abord reprises, puis l'action demandée est appliquée.
    // Rien de ce qui a été saisi n'est donc perdu.
    // ============================================================
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save_tarifs']) && $active_cat) {
        $op    = json_decode($_POST['op'] ?? '', true);
        $op    = is_array($op) ? $op : [];
        $type  = $op['type'] ?? '';
        $dir   = ($op['dir'] ?? '') === 'up' ? 'up' : 'down';
        $flash = "Modifications enregistrées !";

        if (is_grouped_cat($tarifs, $active_cat)) {
            $new_gammes = [];
            $new_from   = []; // réparations affichées « À partir de » : gamme => modèle => [réparations]
            $gamme_map  = []; // ancienne clé de gamme => nouvelle clé

            foreach ($_POST['gammes'] ?? [] as $gamme_key => $gamme_data) {
                $gamme_key = (string)$gamme_key;

                if ($type === 'del_gamme' && ($op['gamme'] ?? null) === $gamme_key) {
                    $flash = "Gamme « " . htmlspecialchars($gamme_key) . " » supprimée.";
                    continue;
                }

                $new_models = [];
                $gamme_from = [];
                $model_map  = []; // ancien nom de modèle => nouveau nom
                foreach ($gamme_data['models'] ?? [] as $old_model_name => $mdata) {
                    $old_model_name = (string)$old_model_name;

                    if ($type === 'del_model' && ($op['gamme'] ?? null) === $gamme_key && ($op['model'] ?? null) === $old_model_name) {
                        $flash = "Modèle « " . htmlspecialchars($old_model_name) . " » supprimé.";
                        continue;
                    }

                    $new_model_name = trim($mdata['name'] ?? '');
                    if ($new_model_name === '') $new_model_name = $old_model_name;
                    $new_model_name = unique_key($new_models, $new_model_name);

                    $new_repairs = [];
                    foreach ($mdata['repairs'] ?? [] as $repair_name => $price) {
                        $new_repairs[$repair_name] = (int)$price;
                    }
                    $new_models[$new_model_name] = normalize_smartphone_repairs($new_repairs);
                    $model_map[$old_model_name]  = $new_model_name;

                    // Cases « À partir de » cochées pour ce modèle
                    $checked = array_map('strval', array_keys(array_filter($mdata['from'] ?? [])));
                    $from_repairs = array_values(array_intersect(array_map('strval', array_keys($new_models[$new_model_name])), $checked));
                    if ($from_repairs) $gamme_from[$new_model_name] = $from_repairs;
                }

                if ($type === 'add_model' && ($op['gamme'] ?? null) === $gamme_key) {
                    $model_name = trim($_POST['new_model'][$gamme_key] ?? '');
                    if ($model_name !== '') {
                        $model_name = unique_key($new_models, $model_name);
                        $new_models[$model_name] = normalize_smartphone_repairs([]);
                        $flash = "Modèle « " . htmlspecialchars($model_name) . " » ajouté !";
                    }
                }

                if ($type === 'move_model' && ($op['gamme'] ?? null) === $gamme_key && isset($model_map[$op['model'] ?? ''])) {
                    $new_models = move_assoc_key($new_models, $model_map[$op['model']], $dir);
                }

                $new_title = trim($gamme_data['title'] ?? '');
                if ($new_title === '') $new_title = $gamme_key;
                $new_title = unique_key($new_gammes, $new_title);

                $new_gammes[$new_title] = $new_models;
                $gamme_map[$gamme_key]  = $new_title;
                if ($gamme_from) $new_from[$new_title] = $gamme_from;
            }

            if ($type === 'move_gamme' && isset($gamme_map[$op['gamme'] ?? ''])) {
                $new_gammes = move_assoc_key($new_gammes, $gamme_map[$op['gamme']], $dir);
            }

            if ($type === 'add_gamme') {
                $title = trim($_POST['new_gamme_title'] ?? '');
                if ($title !== '') {
                    $title = unique_key($new_gammes, $title);
                    $new_gammes[$title] = [];
                    $flash = "Gamme « " . htmlspecialchars($title) . " » créée !";
                }
            }

            $tarifs[$active_cat]['gammes'] = $new_gammes;
            if ($new_from) {
                $tarifs[$active_cat]['from_prices'] = $new_from;
            } else {
                unset($tarifs[$active_cat]['from_prices']);
            }

        } else {
            $old_services = $tarifs[$active_cat]['services'] ?? [];
            $new_services = [];
            $index_map    = []; // ancien index => nouvel index

            foreach ($_POST['services'] ?? [] as $i => $sdata) {
                $i = (int)$i;
                $name = trim($sdata['name'] ?? '');
                if ($name === '') $name = $old_services[$i]['name'] ?? '';

                if ($type === 'del_service' && (int)($op['index'] ?? -1) === $i) {
                    $flash = "Service « " . htmlspecialchars($name) . " » supprimé.";
                    continue;
                }
                if ($name === '') continue;

                $index_map[$i]  = count($new_services);
                $new_services[] = [
                    'name'        => $name,
                    'icon'        => valid_service_icon($sdata['icon'] ?? ''),
                    'price'       => (int)($sdata['price'] ?? 0),
                    'from'        => !empty($sdata['from']),
                    'qualirepar'  => !empty($sdata['qualirepar']),
                    'description' => trim($sdata['description'] ?? '')
                ];
            }

            if ($type === 'move_service' && isset($index_map[(int)($op['index'] ?? -1)])) {
                $from = $index_map[(int)$op['index']];
                $to   = $dir === 'up' ? $from - 1 : $from + 1;
                if (isset($new_services[$to])) {
                    [$new_services[$from], $new_services[$to]] = [$new_services[$to], $new_services[$from]];
                }
            }

            if ($type === 'add_service') {
                $name = trim($_POST['new_service']['name'] ?? '');
                if ($name !== '') {
                    $new_services[] = [
                        'name'        => $name,
                        'icon'        => valid_service_icon($_POST['new_service']['icon'] ?? ''),
                        'price'       => (int)($_POST['new_service']['price'] ?? 0),
                        'from'        => !empty($_POST['new_service']['from']),
                        'qualirepar'  => !empty($_POST['new_service']['qualirepar']),
                        'description' => trim($_POST['new_service']['description'] ?? '')
                    ];
                    $flash = "Service « " . htmlspecialchars($name) . " » créé !";
                }
            }

            $tarifs[$active_cat]['services'] = $new_services;
        }

        save_tarifs_file($json_file, $tarifs);
        $_SESSION['flash'] = $flash;
        header("Location: admin.php?cat=" . urlencode($active_cat));
        exit;
    }
}

// Message de confirmation de la dernière action (après redirection)
if (isset($_SESSION['flash'])) {
    $message = $_SESSION['flash'];
    unset($_SESSION['flash']);
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administration - Tarifs & Catégories</title>
    <link rel="icon" type="image/png" href="favicon.png?v=2">
    <style>
        :root {
            --primary: #00a88f;
            --primary-hover: #008f7a;
            --primary-light: rgba(0, 168, 143, 0.15);
            --primary-glow: rgba(0, 168, 143, 0.35);
            --bg-dark: #111827;
            --bg-slate: #1f2937;
            --card-bg: rgba(31, 41, 55, 0.85);
            --card-inner: #111827;
            --danger: #ef4444;
            --danger-bg: rgba(239, 68, 68, 0.12);
            --border: rgba(255, 255, 255, 0.08);
            --border-emerald: rgba(0, 168, 143, 0.3);
            --text-main: #f3f4f6;
            --text-muted: #9ca3af;
            --radius: 14px;
        }

        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            background: linear-gradient(135deg, #111827 0%, #1f2937 40%, #063d35 100%);
            background-attachment: fixed;
            color: var(--text-main);
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            padding-bottom: 110px;
            min-height: 100vh;
        }

        .admin-container { max-width: 1150px; margin: 0 auto; padding: 30px 20px; }

        .admin-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-bottom: 25px;
            margin-bottom: 25px;
            border-bottom: 1px solid var(--border-emerald);
        }
        .admin-title { font-size: 1.6rem; font-weight: 700; color: #fff; display: flex; align-items: center; gap: 10px; }
        .admin-title span { color: var(--primary); }

        .btn-logout {
            background: rgba(239, 68, 68, 0.1);
            color: var(--danger);
            border: 1px solid rgba(239, 68, 68, 0.3);
            padding: 8px 16px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            font-size: 0.88rem;
            transition: all 0.2s ease;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }
        .btn-logout:hover { background: var(--danger); color: #fff; }

        /* SÉLECTEUR DE CATÉGORIES PRINCIPALES */
        .categories-nav { margin-bottom: 30px; }
        .categories-nav p {
            color: var(--text-muted);
            font-size: 0.95rem;
            margin-bottom: 12px;
            font-weight: 600;
        }
        .categories-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 14px;
        }

        /* Chaque tuile de catégorie contient désormais son propre bouton "+" */
        .category-tile {
            position: relative;
            background: var(--card-bg);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            backdrop-filter: blur(10px);
            transition: all 0.2s ease;
            overflow: hidden;
        }
        .category-tile:hover { border-color: var(--primary); transform: translateY(-2px); }
        .category-tile.active { border-color: var(--primary); box-shadow: 0 4px 15px var(--primary-glow); }

        .category-tile-row {
            display: flex;
            align-items: stretch;
        }
        .category-tab {
            flex: 1;
            padding: 16px 14px;
            color: var(--text-main);
            text-decoration: none;
            font-weight: 700;
            text-align: center;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .category-tile.active .category-tab { color: #fff; }
        .category-tile:hover .category-tab { color: #fff; }

        .create-bar { padding: 16px 24px; }
        .create-bar-head {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 12px;
            flex-wrap: wrap;
        }
        .create-bar-head strong { color: #fff; font-size: 1.05rem; }
        .create-form {
            display: flex;
            gap: 10px;
            align-items: flex-start;
            flex-wrap: wrap;
            margin-top: 12px;
        }
        .create-form input[type="text"] { flex: 1; min-width: 220px; }
        .create-form textarea { flex-basis: 100%; }

        .default-submit {
            position: absolute;
            left: -9999px;
            width: 1px;
            height: 1px;
            overflow: hidden;
        }

        button.btn-move { cursor: pointer; font: inherit; line-height: 1; }
        button.btn-del { font-family: inherit; }

        .service-card .model-card-header .input-model-title { max-width: none; flex: 1; }
        .service-card textarea.form-control {
            width: 100%;
            min-height: 70px;
            resize: vertical;
            font-family: inherit;
            font-size: 0.85rem;
            margin-top: 4px;
            box-sizing: border-box;
        }

        .card {
            background: var(--card-bg);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            padding: 24px;
            margin-bottom: 25px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.35);
            backdrop-filter: blur(10px);
        }
        .card-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            padding-bottom: 15px;
            margin-bottom: 20px;
            border-bottom: 1px solid var(--border-emerald);
            gap: 15px;
            flex-wrap: wrap;
        }

        .input-gamme-title {
            background: transparent;
            border: 1px dashed var(--border-emerald);
            color: var(--primary);
            font-size: 1.25rem;
            font-weight: 700;
            padding: 6px 10px;
            border-radius: 8px;
            width: 100%;
            max-width: 400px;
            transition: all 0.2s;
        }
        .input-gamme-title:focus {
            background: var(--card-inner);
            border-style: solid;
            outline: none;
            box-shadow: 0 0 0 3px var(--primary-light);
        }

        .input-model-title {
            background: transparent;
            border: 1px dashed var(--border);
            color: #fff;
            font-size: 0.98rem;
            font-weight: 700;
            padding: 4px 8px;
            border-radius: 6px;
            width: 100%;
            max-width: 180px;
            transition: all 0.2s;
        }
        .input-model-title:focus {
            background: var(--bg-slate);
            border-color: var(--primary);
            outline: none;
        }

        .form-control {
            background: var(--card-inner);
            border: 1px solid var(--border);
            color: #fff;
            padding: 10px 14px;
            border-radius: 8px;
            font-size: 0.92rem;
            outline: none;
            transition: border-color 0.2s, box-shadow 0.2s;
        }
        .form-control:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px var(--primary-light);
        }

        .btn {
            background: var(--primary);
            color: #ffffff;
            font-weight: 700;
            border: none;
            padding: 10px 20px;
            border-radius: 8px;
            cursor: pointer;
            font-size: 0.9rem;
            transition: all 0.2s ease;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            box-shadow: 0 4px 14px var(--primary-glow);
        }
        .btn:hover { background: var(--primary-hover); transform: translateY(-1px); }
        .btn-blue { background: #3b82f6; color: #fff; box-shadow: 0 4px 14px rgba(59, 130, 246, 0.25); }
        .btn-blue:hover { background: #2563eb; }

        .btn-link {
            background: none;
            border: none;
            color: #38bdf8;
            cursor: pointer;
            font-size: 0.85rem;
            text-decoration: underline;
            margin-top: 15px;
            display: inline-block;
        }
        .btn-link:hover { color: #7dd3fc; }

        .btn-del {
            background: var(--danger-bg);
            color: var(--danger);
            border: 1px solid rgba(239, 68, 68, 0.3);
            padding: 6px 12px;
            border-radius: 6px;
            font-size: 0.8rem;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            transition: all 0.2s;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }
        .btn-del:hover { background: var(--danger); color: #fff; }

        .btn-move {
            background: rgba(255, 255, 255, 0.05);
            color: var(--text-muted) !important;
            border: 1px solid var(--border);
            padding: 5px 8px;
            border-radius: 6px;
            text-decoration: none !important;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s ease;
        }
        .btn-move:hover {
            background: var(--card-inner);
            border-color: var(--primary);
            color: var(--primary) !important;
        }

        .alert {
            padding: 14px 18px;
            background: rgba(0, 168, 143, 0.15);
            border: 1px solid var(--primary);
            color: #34d399;
            border-radius: 10px;
            margin-bottom: 25px;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .model-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
            gap: 18px;
        }
        .model-card {
            background: var(--card-inner);
            border: 1px solid var(--border);
            border-radius: 10px;
            padding: 18px;
        }
        .model-card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 14px;
            padding-bottom: 8px;
            border-bottom: 1px dashed var(--border);
            gap: 8px;
        }

        .price-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 10px;
            gap: 8px;
        }
        .price-row label { font-size: 0.88rem; color: var(--text-muted); flex: 1; min-width: 0; }

        /* Bouton « À partir de » (case à cocher présentée comme une pastille) */
        .price-row label.from-toggle { flex: none; }
        .from-toggle {
            position: relative;
            display: inline-flex;
            cursor: pointer;
            user-select: none;
        }
        .from-toggle input {
            position: absolute;
            opacity: 0;
            width: 1px;
            height: 1px;
        }
        .from-toggle span {
            font-size: 0.72rem;
            font-weight: 600;
            white-space: nowrap;
            color: var(--text-muted);
            border: 1px solid var(--border);
            background: rgba(255, 255, 255, 0.04);
            padding: 5px 9px;
            border-radius: 20px;
            transition: all 0.2s ease;
        }
        .from-toggle:hover span { border-color: var(--primary); color: #fff; }
        .from-toggle input:checked + span {
            background: var(--primary);
            border-color: var(--primary);
            color: #fff;
        }
        .from-toggle input:focus-visible + span { box-shadow: 0 0 0 3px var(--primary-light); }

        /* Case à cocher « Réduction QualiRépar » */
        .check-row {
            display: flex;
            align-items: center;
            gap: 10px;
            margin: 4px 0 14px;
            font-size: 0.88rem;
            color: var(--text-main);
            cursor: pointer;
            user-select: none;
        }
        .check-row input { position: absolute; opacity: 0; width: 1px; height: 1px; }
        .check-row .check-box {
            width: 18px;
            height: 18px;
            flex-shrink: 0;
            border-radius: 5px;
            border: 1.5px solid var(--text-muted);
            background: var(--card-inner);
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.15s ease;
        }
        .check-row .check-box::after {
            content: '';
            width: 5px;
            height: 9px;
            border: solid #fff;
            border-width: 0 2px 2px 0;
            transform: rotate(45deg) translate(-1px, -1px);
            opacity: 0;
        }
        .check-row:hover .check-box { border-color: var(--primary); }
        .check-row input:checked + .check-box { background: var(--primary); border-color: var(--primary); }
        .check-row input:checked + .check-box::after { opacity: 1; }
        .check-row input:focus-visible + .check-box { box-shadow: 0 0 0 3px var(--primary-light); }
        .create-form .check-row { margin: 0; align-self: center; }

        /* Sélecteur d'icône des services */
        .icon-picker { position: relative; flex-shrink: 0; }
        /* Grille ouverte au-dessus de la barre d'enregistrement fixe (la carte a son propre plan à cause de backdrop-filter) */
        .icon-picker[open] { z-index: 1001; }
        .card:has(.icon-picker[open]) { position: relative; z-index: 1001; }
        .icon-picker summary {
            list-style: none;
            width: 38px;
            height: 38px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 8px;
            border: 1px dashed var(--border-emerald);
            background: var(--primary-light);
            color: var(--primary);
            cursor: pointer;
            transition: all 0.2s ease;
        }
        .icon-picker summary::-webkit-details-marker { display: none; }
        .icon-picker summary:hover,
        .icon-picker[open] summary { border-style: solid; border-color: var(--primary); }
        .icon-picker svg { width: 20px; height: 20px; }
        .icon-picker-panel {
            position: absolute;
            top: calc(100% + 6px);
            left: 0;
            width: 340px;
            max-width: calc(100vw - 40px);
            max-height: 360px;
            overflow-y: auto;
            padding: 0 10px 10px;
            background: var(--bg-slate);
            border: 1px solid var(--border-emerald);
            border-radius: 10px;
            box-shadow: 0 12px 30px rgba(0, 0, 0, 0.55);
            scroll-margin-bottom: 90px; /* garde la grille au-dessus de la barre d'enregistrement */
        }
        .icon-picker-panel .icon-search {
            position: sticky;
            top: 0;
            z-index: 1;
            width: 100%;
            box-sizing: border-box;
            margin: 0 0 4px;
            padding: 8px 10px;
            font-size: 0.82rem;
            border-radius: 0 0 8px 8px;
            border-top: 10px solid var(--bg-slate); /* espace au-dessus, garde le fond opaque en défilant */
        }
        .icon-group-title {
            font-size: 0.7rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: var(--text-muted);
            margin: 10px 0 6px;
        }
        .icon-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, 36px);
            gap: 6px;
        }
        .icon-no-result { font-size: 0.82rem; color: var(--text-muted); text-align: center; margin: 14px 0 4px; }
        .icon-picker-panel label { cursor: pointer; }
        .icon-picker-panel input[type="radio"] { position: absolute; opacity: 0; width: 1px; height: 1px; }
        .icon-picker-panel span {
            width: 36px;
            height: 36px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 8px;
            color: var(--text-muted);
            border: 1px solid transparent;
            transition: all 0.15s ease;
        }
        .icon-picker-panel label:hover span { color: #fff; background: rgba(255, 255, 255, 0.06); }
        .icon-picker-panel input:checked + span {
            color: #fff;
            background: var(--primary);
            border-color: var(--primary);
        }
        .icon-picker-panel input:focus-visible + span { box-shadow: 0 0 0 3px var(--primary-light); }

        .input-currency {
            position: relative;
            display: flex;
            align-items: center;
        }
        .input-currency input {
            padding-right: 26px;
            text-align: right;
            width: 85px;
            font-weight: 600;
        }
        .input-currency span {
            position: absolute;
            right: 10px;
            color: var(--text-muted);
            font-size: 0.85rem;
            pointer-events: none;
        }

        .sticky-toolbar {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            background: rgba(17, 24, 39, 0.92);
            backdrop-filter: blur(12px);
            border-top: 1px solid var(--border-emerald);
            padding: 15px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            z-index: 1000;
            box-shadow: 0 -5px 25px rgba(0, 0, 0, 0.5);
        }

        .login-box {
            max-width: 380px;
            margin: 100px auto;
            text-align: center;
        }

        .hidden { display: none !important; }
        .empty-state { text-align: center; padding: 40px; color: var(--text-muted); font-size: 1rem; }
    </style>
</head>
<body>

<div class="admin-container">

<?php if (!isset($_SESSION['admin_logged'])): ?>
    <!-- FORMULAIRE DE CONNEXION -->
    <div class="card login-box">
        <h2 style="margin-bottom: 10px; color: #fff;">Connexion Admin</h2>
        <p style="color: var(--text-muted); font-size: 0.88rem; margin-bottom: 20px;">Gestion des tarifs & catégories</p>

        <?php if (!empty($error)): ?>
            <p style="color: var(--danger); margin-bottom: 15px; font-size: 0.9rem;"><?= htmlspecialchars($error) ?></p>
        <?php endif; ?>

        <form method="POST" id="form-password">
            <input type="password" name="password" placeholder="Mot de passe" class="form-control" style="width: 100%; margin-bottom: 15px;" required autofocus>
            <button type="submit" class="btn" style="width: 100%;">Se connecter</button>
        </form>

        <form method="POST" id="form-code" class="hidden">
            <p style="font-size: 0.85rem; color: var(--text-muted); margin-bottom: 12px;">
                Un code à 6 chiffres a été envoyé à <strong><?= htmlspecialchars($contact_email) ?></strong>.
            </p>
            <input type="text" name="code" placeholder="Code à 6 chiffres" maxlength="6" class="form-control" style="width: 100%; margin-bottom: 15px; text-align: center; font-weight: 700; letter-spacing: 2px;" required>
            <button type="submit" name="verify_code" value="1" class="btn" style="width: 100%;">Valider le code</button>
        </form>

        <button type="button" id="btn-forgot" class="btn-link">Code de secours oublié ?</button>
    </div>

    <script>
        document.getElementById('btn-forgot').addEventListener('click', function() {
            const btn = this;
            btn.disabled = true;
            btn.textContent = "Envoi du code par e-mail...";

            fetch('admin.php?action=send_code')
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        document.getElementById('form-password').classList.add('hidden');
                        document.getElementById('form-code').classList.remove('hidden');
                        btn.style.display = 'none';
                    } else {
                        alert(data.message || "Erreur d'envoi.");
                        btn.disabled = false;
                        btn.textContent = "Code de secours oublié ?";
                    }
                })
                .catch(() => {
                    alert("Erreur de communication.");
                    btn.disabled = false;
                    btn.textContent = "Code de secours oublié ?";
                });
        });
    </script>

<?php else: ?>

    <!-- HEADER TOPBAR -->
    <div class="admin-header">
        <div class="admin-title">
            Administration <span>Tarifs & Catégories</span>
        </div>
        <a href="?logout=1" class="btn-logout">Déconnexion</a>
    </div>

    <!-- SÉLECTEUR DE CATÉGORIE PRINCIPALE (toute création se fait dans la vue de la catégorie) -->
    <div class="categories-nav">
        <p>Sélectionnez une catégorie pour afficher et gérer ses gammes, modèles et services :</p>
        <div class="categories-grid">
            <?php foreach ($categories as $ckey => $clabel): ?>
                <div class="category-tile <?= ($active_cat === $ckey) ? 'active' : '' ?>">
                    <div class="category-tile-row">
                        <a href="?cat=<?= urlencode($ckey) ?>" class="category-tab">
                            <?= htmlspecialchars($clabel) ?>
                        </a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <?php if ($message): ?>
        <div class="alert"><?= $message ?></div>
    <?php endif; ?>

    <?php if (!$active_cat): ?>
        <!-- ÉCRAN SI AUCUNE CATÉGORIE N'EST ENCORE SÉLECTIONNÉE -->
        <div class="card empty-state">
            <h3 style="color:#fff; margin-bottom:8px;">Sélectionnez une catégorie ci-dessus</h3>
            <p>Cliquez sur l'un des boutons de catégorie pour afficher les réparations, gammes et modèles associés.</p>
        </div>
    <?php else: ?>

        <!-- FORMULAIRE PRINCIPAL DE LA CATÉGORIE ACTIVE
             Tous les boutons (créer, supprimer, déplacer, enregistrer) envoient ce formulaire :
             les modifications en cours sont ainsi conservées à chaque action. -->
        <form method="POST" action="?cat=<?= urlencode($active_cat) ?>" id="form-save-tarifs">
            <input type="hidden" name="save_tarifs" value="1">
            <!-- Bouton par défaut (touche Entrée) : simple enregistrement -->
            <button type="submit" class="default-submit" tabindex="-1" aria-hidden="true"></button>

            <!-- CRÉER UNE GAMME / UN SERVICE DANS LA CATÉGORIE ACTIVE -->
            <div class="card create-bar">
                <div class="create-bar-head">
                    <strong><?= htmlspecialchars($categories[$active_cat]) ?></strong>
                    <?php if (is_grouped_cat($tarifs, $active_cat)): ?>
                        <button type="button" class="btn" id="btn-create" onclick="toggleCreateForm()">+ Créer une gamme</button>
                    <?php else: ?>
                        <button type="button" class="btn" id="btn-create" onclick="toggleCreateForm()">+ Créer un service</button>
                    <?php endif; ?>
                </div>
                <div class="create-form hidden" id="form-create">
                    <?php if (is_grouped_cat($tarifs, $active_cat)): ?>
                        <input type="text" name="new_gamme_title" id="new-gamme-title" placeholder="Nom de la nouvelle gamme (ex: iPhone 15)" class="form-control" data-enter-click="btn-add-gamme">
                        <button type="submit" name="op" value="<?= op_value(['type' => 'add_gamme']) ?>" class="btn" id="btn-add-gamme" data-require="new-gamme-title">Créer</button>
                    <?php else: ?>
                        <?= icon_picker('new_service[icon]', DEFAULT_SERVICE_ICON) ?>
                        <input type="text" name="new_service[name]" id="new-service-name" placeholder="Nom du service (ex: Remplacement SSD)" class="form-control" data-enter-click="btn-add-service">
                        <label class="from-toggle" title="Afficher « À partir de » devant ce tarif">
                            <input type="checkbox" name="new_service[from]" value="1">
                            <span>À partir de</span>
                        </label>
                        <div class="input-currency">
                            <input type="number" name="new_service[price]" value="0" min="0" class="form-control" title="Prix" data-enter-click="btn-add-service">
                            <span>€</span>
                        </div>
                        <label class="check-row" title="Affiche « Réduction QualiRépar appliquée » à côté du tarif sur la page client">
                            <input type="checkbox" name="new_service[qualirepar]" value="1">
                            <span class="check-box"></span>
                            Réduction QualiRépar
                        </label>
                        <textarea name="new_service[description]" rows="2" placeholder="Description du service (optionnel)" class="form-control"></textarea>
                        <button type="submit" name="op" value="<?= op_value(['type' => 'add_service']) ?>" class="btn" id="btn-add-service" data-require="new-service-name">Créer</button>
                    <?php endif; ?>
                    <button type="button" class="btn-link" onclick="toggleCreateForm()">Annuler</button>
                </div>
            </div>

            <?php if (is_grouped_cat($tarifs, $active_cat)): ?>

                <?php
                $current_gammes = $tarifs[$active_cat]['gammes'] ?? [];
                if (empty($current_gammes)):
                ?>
                    <div class="card empty-state">
                        <p>Aucune gamme pour le moment dans cette catégorie. Utilisez le bouton « Créer une gamme » ci-dessus pour en créer une.</p>
                    </div>
                <?php else: ?>
                    <?php $gi = 0; foreach ($current_gammes as $gamme_key => $models): $gamme_key = (string)$gamme_key; $gi++; ?>
                        <div class="card">
                            <div class="card-header">
                                <div style="flex: 1; min-width: 260px;">
                                    <input type="text"
                                           name="gammes[<?= htmlspecialchars($gamme_key) ?>][title]"
                                           value="<?= htmlspecialchars($gamme_key) ?>"
                                           class="input-gamme-title">
                                </div>

                                <div style="display: flex; gap: 8px; align-items: center;">
                                    <button type="submit" name="op" value="<?= op_value(['type' => 'move_gamme', 'gamme' => $gamme_key, 'dir' => 'up']) ?>" class="btn-move" title="Monter">↑</button>
                                    <button type="submit" name="op" value="<?= op_value(['type' => 'move_gamme', 'gamme' => $gamme_key, 'dir' => 'down']) ?>" class="btn-move" title="Descendre">↓</button>
                                    <button type="submit" name="op" value="<?= op_value(['type' => 'del_gamme', 'gamme' => $gamme_key]) ?>" class="btn-del" onclick="return confirm('Supprimer cette gamme ?')">Supprimer</button>
                                </div>
                            </div>

                            <div class="model-grid">
                                <?php foreach ($models as $model_name => $repairs): $model_name = (string)$model_name; ?>
                                    <div class="model-card">
                                        <div class="model-card-header">
                                            <input type="text"
                                                   name="gammes[<?= htmlspecialchars($gamme_key) ?>][models][<?= htmlspecialchars($model_name) ?>][name]"
                                                   value="<?= htmlspecialchars($model_name) ?>"
                                                   class="input-model-title">

                                            <div style="display: flex; gap: 4px; align-items: center;">
                                                <button type="submit" name="op" value="<?= op_value(['type' => 'move_model', 'gamme' => $gamme_key, 'model' => $model_name, 'dir' => 'up']) ?>" class="btn-move" title="Monter">↑</button>
                                                <button type="submit" name="op" value="<?= op_value(['type' => 'move_model', 'gamme' => $gamme_key, 'model' => $model_name, 'dir' => 'down']) ?>" class="btn-move" title="Descendre">↓</button>
                                                <button type="submit" name="op" value="<?= op_value(['type' => 'del_model', 'gamme' => $gamme_key, 'model' => $model_name]) ?>" class="btn-del" style="padding:4px 8px;" title="Supprimer" onclick="return confirm('Supprimer ce modèle ?')">×</button>
                                            </div>
                                        </div>

                                        <?php foreach ($repairs as $repair_type => $price): ?>
                                            <div class="price-row">
                                                <label><?= htmlspecialchars($repair_type) ?></label>
                                                <label class="from-toggle" title="Afficher « À partir de » devant ce tarif">
                                                    <input type="checkbox"
                                                           name="gammes[<?= htmlspecialchars($gamme_key) ?>][models][<?= htmlspecialchars($model_name) ?>][from][<?= htmlspecialchars($repair_type) ?>]"
                                                           value="1"
                                                           <?= is_from_price($tarifs[$active_cat], $gamme_key, $model_name, $repair_type) ? 'checked' : '' ?>>
                                                    <span>À partir de</span>
                                                </label>
                                                <div class="input-currency">
                                                    <input type="number"
                                                           name="gammes[<?= htmlspecialchars($gamme_key) ?>][models][<?= htmlspecialchars($model_name) ?>][repairs][<?= htmlspecialchars($repair_type) ?>]"
                                                           value="<?= (int)$price ?>"
                                                           class="form-control">
                                                    <span>€</span>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                <?php endforeach; ?>
                            </div>

                            <!-- AJOUTER UN MODÈLE DANS LA GAMME -->
                            <div style="margin-top: 20px; padding-top: 15px; border-top: 1px dashed var(--border);">
                                <div style="display: flex; gap: 10px; flex-wrap: wrap;">
                                    <input type="text" name="new_model[<?= htmlspecialchars($gamme_key) ?>]" id="new-model-<?= $gi ?>" placeholder="Nouveau modèle (ex: iPhone 14)" class="form-control" style="flex: 1; min-width: 220px;" data-enter-click="btn-add-model-<?= $gi ?>">
                                    <button type="submit" name="op" value="<?= op_value(['type' => 'add_model', 'gamme' => $gamme_key]) ?>" id="btn-add-model-<?= $gi ?>" class="btn btn-blue" data-require="new-model-<?= $gi ?>">Ajouter le modèle</button>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>

            <?php else: ?>

                <?php
                $current_services = $tarifs[$active_cat]['services'] ?? [];
                if (empty($current_services)):
                ?>
                    <div class="card empty-state">
                        <p>Aucun service pour le moment dans cette catégorie. Utilisez le bouton « Créer un service » ci-dessus pour en créer un.</p>
                    </div>
                <?php else: ?>
                    <div class="card">
                        <div class="model-grid">
                            <?php foreach ($current_services as $i => $service): ?>
                                <div class="model-card service-card">
                                    <div class="model-card-header">
                                        <?= icon_picker("services[$i][icon]", $service['icon'] ?? '') ?>
                                        <input type="text"
                                               name="services[<?= $i ?>][name]"
                                               value="<?= htmlspecialchars($service['name']) ?>"
                                               class="input-model-title"
                                               title="Nom du service">

                                        <div style="display: flex; gap: 4px; align-items: center;">
                                            <button type="submit" name="op" value="<?= op_value(['type' => 'move_service', 'index' => $i, 'dir' => 'up']) ?>" class="btn-move" title="Monter">↑</button>
                                            <button type="submit" name="op" value="<?= op_value(['type' => 'move_service', 'index' => $i, 'dir' => 'down']) ?>" class="btn-move" title="Descendre">↓</button>
                                            <button type="submit" name="op" value="<?= op_value(['type' => 'del_service', 'index' => $i]) ?>" class="btn-del" style="padding:4px 8px;" title="Supprimer" onclick="return confirm('Supprimer ce service ?')">×</button>
                                        </div>
                                    </div>

                                    <div class="price-row">
                                        <label>Prix</label>
                                        <label class="from-toggle" title="Afficher « À partir de » devant ce tarif">
                                            <input type="checkbox" name="services[<?= $i ?>][from]" value="1" <?= !empty($service['from']) ? 'checked' : '' ?>>
                                            <span>À partir de</span>
                                        </label>
                                        <div class="input-currency">
                                            <input type="number"
                                                   name="services[<?= $i ?>][price]"
                                                   value="<?= (int)($service['price'] ?? 0) ?>"
                                                   min="0"
                                                   class="form-control">
                                            <span>€</span>
                                        </div>
                                    </div>

                                    <label class="check-row" title="Affiche « Réduction QualiRépar appliquée » à côté du tarif sur la page client">
                                        <input type="checkbox" name="services[<?= $i ?>][qualirepar]" value="1" <?= !empty($service['qualirepar']) ? 'checked' : '' ?>>
                                        <span class="check-box"></span>
                                        Réduction QualiRépar
                                    </label>

                                    <label style="font-size: 0.88rem; color: var(--text-muted);">Description</label>
                                    <textarea name="services[<?= $i ?>][description]"
                                              rows="3"
                                              placeholder="Description du service (optionnel)"
                                              class="form-control"><?= htmlspecialchars($service['description'] ?? '') ?></textarea>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endif; ?>

            <?php endif; ?>

            <!-- BARRE FLOTTANTE DE SAUVEGARDE -->
            <div class="sticky-toolbar">
                <span style="font-size: 0.9rem; color: var(--text-muted);">Modifications pour : <strong><?= htmlspecialchars($categories[$active_cat]) ?></strong></span>
                <button type="submit" class="btn" style="padding: 12px 28px; font-size: 1rem;">Enregistrer les modifications</button>
            </div>
        </form>

    <?php endif; ?>

<?php endif; ?>

</div>

<script>
    function toggleCreateForm() {
        const form = document.getElementById('form-create');
        const btn = document.getElementById('btn-create');
        const opening = form.classList.contains('hidden');
        form.classList.toggle('hidden');
        btn.classList.toggle('hidden', opening);
        if (opening) form.querySelector('input[type="text"]').focus();
    }

    document.addEventListener("DOMContentLoaded", () => {
        const scrollPos = sessionStorage.getItem("adminScrollPos");
        if (scrollPos !== null) {
            window.scrollTo(0, parseInt(scrollPos, 10));
            sessionStorage.removeItem("adminScrollPos");
        }

        const form = document.getElementById('form-save-tarifs');
        if (!form) return;

        // Un bouton « Créer » / « Ajouter » exige que son champ de nom soit rempli
        form.querySelectorAll('[data-require]').forEach(button => {
            button.addEventListener('click', event => {
                const input = document.getElementById(button.dataset.require);
                if (input && input.value.trim() === '') {
                    event.preventDefault();
                    input.setCustomValidity('Veuillez saisir un nom.');
                    input.reportValidity();
                    input.addEventListener('input', () => input.setCustomValidity(''), { once: true });
                }
            });
        });

        // Touche Entrée dans un champ de création : déclenche son propre bouton
        form.querySelectorAll('[data-enter-click]').forEach(input => {
            input.addEventListener('keydown', event => {
                if (event.key === 'Enter') {
                    event.preventDefault();
                    document.getElementById(input.dataset.enterClick).click();
                }
            });
        });

        // Sélecteur d'icône : le choix remplace l'icône affichée et referme la grille
        // Compare sans accents ni majuscules (« ecran » trouve « Écran »)
        const normalize = text => text.normalize('NFD').replace(/[̀-ͯ]/g, '').toLowerCase();

        document.querySelectorAll('.icon-picker').forEach(picker => {
            const search = picker.querySelector('.icon-search');

            picker.addEventListener('change', event => {
                if (event.target.type !== 'radio') return;
                picker.querySelector('summary').innerHTML = event.target.nextElementSibling.innerHTML;
                picker.open = false;
            });

            // À l'ouverture : grille visible à l'écran, recherche vidée et prête à la saisie
            picker.addEventListener('toggle', () => {
                if (!picker.open) return;
                search.value = '';
                search.dispatchEvent(new Event('input'));
                picker.querySelector('.icon-picker-panel').scrollIntoView({ block: 'nearest' });
                search.focus({ preventScroll: true });
            });

            // Filtre les icônes selon leur libellé, masque les thèmes vides
            search.addEventListener('input', () => {
                const query = normalize(search.value.trim());
                let total = 0;
                picker.querySelectorAll('.icon-group').forEach(group => {
                    let visible = 0;
                    group.querySelectorAll('label').forEach(label => {
                        const match = normalize(label.dataset.label).includes(query);
                        label.classList.toggle('hidden', !match);
                        if (match) visible++;
                    });
                    group.classList.toggle('hidden', visible === 0);
                    total += visible;
                });
                picker.querySelector('.icon-no-result').classList.toggle('hidden', total > 0);
            });

            // Entrée dans la recherche : choisit la première icône trouvée (au lieu d'enregistrer la page)
            search.addEventListener('keydown', event => {
                if (event.key !== 'Enter') return;
                event.preventDefault();
                const first = picker.querySelector('.icon-group:not(.hidden) label:not(.hidden) input');
                if (first) first.click();
            });
        });
        // Ferme les grilles ouvertes au clic à côté ou avec Échap
        document.addEventListener('click', event => {
            document.querySelectorAll('.icon-picker[open]').forEach(picker => {
                if (!picker.contains(event.target)) picker.open = false;
            });
        });
        document.addEventListener('keydown', event => {
            if (event.key === 'Escape') {
                document.querySelectorAll('.icon-picker[open]').forEach(picker => picker.open = false);
            }
        });

        // Avertit avant de quitter la page (autre catégorie, déconnexion...) si des modifications ne sont pas enregistrées
        let dirty = false;
        form.addEventListener('input', event => {
            if (!event.target.classList.contains('icon-search')) dirty = true; // la recherche d'icône ne modifie rien
        });
        window.addEventListener('beforeunload', event => {
            if (dirty) {
                event.preventDefault();
                event.returnValue = '';
            }
        });

        // Conserve la position de défilement après l'enregistrement / l'action
        form.addEventListener('submit', () => {
            dirty = false;
            sessionStorage.setItem("adminScrollPos", window.scrollY);
        });
    });
</script>

</body>
</html>