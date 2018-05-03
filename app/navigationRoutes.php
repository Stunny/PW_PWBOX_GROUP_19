<?php
/**
 * Created by PhpStorm.
 * User: Alex
 * Date: 4/13/2018
 * Time: 5:32 PM
 */

$this->any('[landing[/]]', \PWBox\controller\NavigationControllers\LandingPageController::class)
    ->setName('landing-page');


//Profile Page
$this->any('profile/[{userEmail}[/]]', \PWBox\controller\NavigationControllers\ProfilePageController::class)
    ->setName("profile-page");

//Dashboard Page
$this->any('dashboard[/]', \PWBox\controller\NavigationControllers\DashboardPageController::class)
    ->setName("dashboard");

//Settings Page
$this->any('settings[/]', \PWBox\controller\NavigationControllers\SettingsPageController::class)
    ->setName("settings-page");