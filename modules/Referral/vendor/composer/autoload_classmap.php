<?php
/*
 * Copyright (c) 2022 - Do Group LLC - All Right Reserved.
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Emanuele (ebalo) Balsamo <emanuele.balsamo@do-inc.co>, 2022
 */

// autoload_classmap.php @generated by Composer

$vendorDir = dirname(dirname(__FILE__));
$baseDir = dirname($vendorDir);

return array(
    'Composer\\InstalledVersions' => $vendorDir . '/composer/InstalledVersions.php',
    'Modules\\Referral\\database\\seeders\\ReferralDatabaseSeeder' => $baseDir . '/database/seeders/ReferralDatabaseSeeder.php',
    'Modules\\Referral\\enums\\ReferralRoutes' => $baseDir . '/enums/ReferralRoutes.php',
    'Modules\\Referral\\events\\ReferralRedeemed' => $baseDir . '/events/ReferralRedeemed.php',
    'Modules\\Referral\\http\\controllers\\ReferralController' => $baseDir . '/http/controllers/ReferralController.php',
    'Modules\\Referral\\http\\controllers\\ReferralStaticController' => $baseDir . '/http/controllers/ReferralStaticController.php',
    'Modules\\Referral\\models\\Referral' => $baseDir . '/models/Referral.php',
    'Modules\\Referral\\models\\Referred' => $baseDir . '/models/Referred.php',
    'Modules\\Referral\\providers\\ReferralServiceProvider' => $baseDir . '/providers/ReferralServiceProvider.php',
    'Modules\\Referral\\providers\\RouteServiceProvider' => $baseDir . '/providers/RouteServiceProvider.php',
);
