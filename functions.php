<?php

// Escape output for security
function e($string) {
    return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
}

// Set flash message
function setFlash($key, $message, $type = 'success') {
    $_SESSION['flash'][$key] = [
        'message' => $message,
        'type' => $type
    ];
}

// Get and clear flash message
function getFlash($key) {
    if (isset($_SESSION['flash'][$key])) {
        $flash = $_SESSION['flash'][$key];
        unset($_SESSION['flash'][$key]);
        return $flash;
    }
    return null;
}

// Format date
function fmtDate($date, $format = 'Y-m-d H:i') {
    return date($format, strtotime($date));
}

// Check if user is logged in
function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

// Check if user has role
function hasRole($role) {
    return isset($_SESSION['role']) && $_SESSION['role'] === $role;
}

// Redirect helper
function redirect($url) {
    header("Location: $url");
    exit;
}

// Get current user
function getCurrentUser($pdo) {
    if (!isLoggedIn()) {
        return null;
    }
    $stmt = $pdo->prepare("SELECT * FROM users1 WHERE id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    return $stmt->fetch();
}

// Generate 4-digit delivery code
function generateDeliveryCode() {
    return str_pad(rand(0, 9999), 4, '0', STR_PAD_LEFT);
}

// Get district name by language
function getDistrictName($district, $lang = 'ar') {
    return $lang === 'ar' ? $district['name_ar'] : $district['name_en'];
}

// Translation array
$translations = [
    'ar' => [
        'app_name' => 'برق',
        'tagline' => 'نظام التوصيل للمقاطعات',
        'login' => 'تسجيل الدخول',
        'register' => 'إنشاء حساب',
        'logout' => 'تسجيل الخروج',
        'phone' => 'رقم الهاتف',
        'password' => 'كلمة المرور',
        'full_name' => 'الاسم الكامل',
        'submit' => 'إرسال',
        'dashboard' => 'لوحة التحكم',
        'my_orders' => 'طلباتي',
        'new_order' => 'طلب جديد',
        'order_details' => 'تفاصيل الطلب',
        'customer_phone' => 'هاتف العميل',
        'pickup_district' => 'مقاطعة الاستلام',
        'delivery_district' => 'مقاطعة التوصيل',
        'delivery_fee' => 'رسوم التوصيل',
        'detailed_address' => 'العنوان التفصيلي',
        'select_district' => 'اختر المقاطعة',
        'status' => 'الحالة',
        'pending' => 'قيد الانتظار',
        'accepted' => 'مقبول',
        'picked_up' => 'تم الاستلام',
        'delivered' => 'تم التوصيل',
        'cancelled' => 'ملغي',
        'cancel_order' => 'إلغاء الطلب',
        'accept_order' => 'قبول الطلب',
        'pickup_order' => 'تم الاستلام',
        'deliver_order' => 'تم التوصيل',
        'your_driver' => 'السائق الخاص بك',
        'driver_name' => 'اسم السائق',
        'driver_phone' => 'هاتف السائق',
        'driver_rating' => 'تقييم السائق',
        'delivery_code' => 'كود التوصيل',
        'whatsapp' => 'واتساب',
        'settings' => 'الإعدادات',
        'select_districts' => 'اختر المقاطعات',
        'profile' => 'الملف الشخصي',
        'update_profile' => 'تحديث الملف',
        'my_points' => 'نقاطي',
        'points' => 'نقاط',
        'admin_panel' => 'لوحة الإدارة',
        'users' => 'المستخدمين',
        'orders' => 'الطلبات',
        'districts' => 'المقاطعات',
        'manage_users' => 'إدارة المستخدمين',
        'manage_orders' => 'إدارة الطلبات',
        'manage_districts' => 'إدارة المقاطعات',
        'add_points' => 'إضافة نقاط',
        'role' => 'الدور',
        'admin' => 'مدير',
        'driver' => 'سائق',
        'customer' => 'عميل',
        'created_at' => 'تاريخ الإنشاء',
        'updated_at' => 'تاريخ التحديث',
        'customer_name' => 'اسم العميل',
        'from' => 'من',
        'to' => 'إلى',
        'fee' => 'الرسوم',
        'mru' => 'أوقية',
        'available_orders' => 'الطلبات المتاحة',
        'my_active_orders' => 'طلباتي النشطة',
        'order_history' => 'سجل الطلبات',
        'no_orders' => 'لا توجد طلبات',
        'welcome' => 'مرحبا',
        'help' => 'مساعدة',
        'contact_us' => 'اتصل بنا',
        'save' => 'حفظ',
        'cancel' => 'إلغاء',
        'confirm' => 'تأكيد',
        'are_you_sure' => 'هل أنت متأكد؟',
        'yes' => 'نعم',
        'no' => 'لا',
        'success' => 'نجح',
        'error' => 'خطأ',
        'order_placed' => 'تم تقديم الطلب بنجاح',
        'order_cancelled' => 'تم إلغاء الطلب',
        'order_accepted' => 'تم قبول الطلب',
        'order_picked_up' => 'تم استلام الطلب',
        'order_delivered' => 'تم توصيل الطلب',
        'login_success' => 'تم تسجيل الدخول بنجاح',
        'register_success' => 'تم إنشاء الحساب بنجاح',
        'profile_updated' => 'تم تحديث الملف الشخصي',
        'districts_updated' => 'تم تحديث المقاطعات',
        'invalid_credentials' => 'بيانات الاعتماد غير صحيحة',
        'phone_exists' => 'رقم الهاتف مسجل بالفعل',
        'required_fields' => 'جميع الحقول مطلوبة',
        'insufficient_points' => 'نقاط غير كافية',
    ],
    'fr' => [
        'app_name' => 'Barq',
        'tagline' => 'Système de livraison par districts',
        'login' => 'Connexion',
        'register' => 'S\'inscrire',
        'logout' => 'Déconnexion',
        'phone' => 'Téléphone',
        'password' => 'Mot de passe',
        'full_name' => 'Nom complet',
        'submit' => 'Soumettre',
        'dashboard' => 'Tableau de bord',
        'my_orders' => 'Mes commandes',
        'new_order' => 'Nouvelle commande',
        'order_details' => 'Détails de la commande',
        'customer_phone' => 'Téléphone client',
        'pickup_district' => 'District de ramassage',
        'delivery_district' => 'District de livraison',
        'delivery_fee' => 'Frais de livraison',
        'detailed_address' => 'Adresse détaillée',
        'select_district' => 'Sélectionner le district',
        'status' => 'Statut',
        'pending' => 'En attente',
        'accepted' => 'Accepté',
        'picked_up' => 'Ramassé',
        'delivered' => 'Livré',
        'cancelled' => 'Annulé',
        'cancel_order' => 'Annuler la commande',
        'accept_order' => 'Accepter la commande',
        'pickup_order' => 'Ramasser',
        'deliver_order' => 'Livrer',
        'your_driver' => 'Votre chauffeur',
        'driver_name' => 'Nom du chauffeur',
        'driver_phone' => 'Téléphone du chauffeur',
        'driver_rating' => 'Note du chauffeur',
        'delivery_code' => 'Code de livraison',
        'whatsapp' => 'WhatsApp',
        'settings' => 'Paramètres',
        'select_districts' => 'Sélectionner les districts',
        'profile' => 'Profil',
        'update_profile' => 'Mettre à jour le profil',
        'my_points' => 'Mes points',
        'points' => 'Points',
        'admin_panel' => 'Panneau Admin',
        'users' => 'Utilisateurs',
        'orders' => 'Commandes',
        'districts' => 'Districts',
        'manage_users' => 'Gérer les utilisateurs',
        'manage_orders' => 'Gérer les commandes',
        'manage_districts' => 'Gérer les districts',
        'add_points' => 'Ajouter des points',
        'role' => 'Rôle',
        'admin' => 'Admin',
        'driver' => 'Chauffeur',
        'customer' => 'Client',
        'created_at' => 'Créé le',
        'updated_at' => 'Mis à jour le',
        'customer_name' => 'Nom du client',
        'from' => 'De',
        'to' => 'À',
        'fee' => 'Frais',
        'mru' => 'MRU',
        'available_orders' => 'Commandes disponibles',
        'my_active_orders' => 'Mes commandes actives',
        'order_history' => 'Historique des commandes',
        'no_orders' => 'Aucune commande',
        'welcome' => 'Bienvenue',
        'help' => 'Aide',
        'contact_us' => 'Contactez-nous',
        'save' => 'Enregistrer',
        'cancel' => 'Annuler',
        'confirm' => 'Confirmer',
        'are_you_sure' => 'Êtes-vous sûr?',
        'yes' => 'Oui',
        'no' => 'Non',
        'success' => 'Succès',
        'error' => 'Erreur',
        'order_placed' => 'Commande passée avec succès',
        'order_cancelled' => 'Commande annulée',
        'order_accepted' => 'Commande acceptée',
        'order_picked_up' => 'Commande ramassée',
        'order_delivered' => 'Commande livrée',
        'login_success' => 'Connexion réussie',
        'register_success' => 'Inscription réussie',
        'profile_updated' => 'Profil mis à jour',
        'districts_updated' => 'Districts mis à jour',
        'invalid_credentials' => 'Identifiants invalides',
        'phone_exists' => 'Téléphone déjà enregistré',
        'required_fields' => 'Tous les champs sont requis',
        'insufficient_points' => 'Points insuffisants',
    ]
];

// Get translation
function t($key, $lang = 'ar') {
    global $translations;
    return $translations[$lang][$key] ?? $key;
}

// Get current language
function getCurrentLang() {
    return $_SESSION['lang'] ?? 'ar';
}

// Set language
function setLang($lang) {
    $_SESSION['lang'] = in_array($lang, ['ar', 'fr']) ? $lang : 'ar';
}
