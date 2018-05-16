<?php
/**
 * Created by PhpStorm.
 * User: Alex
 * Date: 4/13/2018
 * Time: 5:32 PM
 */

$this->any('[landing[/]]', \PWBox\controller\NavigationControllers\LandingPageController::class)
    ->setName('landing-page');

$this->get('login[/]', \PWBox\controller\NavigationControllers\LoginPageController::class)->setName('login-page');
$this->post('login[/]', '\PWBox\controller\UserController:login')->setName('user-login');

$this->any('logout[/]', '\PWBox\controller\UserController:logout')->setName('user-logout');

$this->any('register[/]', \PWBox\controller\NavigationControllers\RegisterPageController::class)->setName('login-page');


//Profile Page
$this->any('profile[/{userEmail}[/]]', \PWBox\controller\NavigationControllers\ProfilePageController::class)
    ->setName("profile-page")->add(\PWBox\controller\middleware\LoginMiddleware::class);

//Dashboard Page
$this->any('dashboard[/]', \PWBox\controller\NavigationControllers\DashboardPageController::class)
    ->setName("dashboard")->add(\PWBox\controller\middleware\LoginMiddleware::class);

//Settings Page
$this->any('settings[/]', \PWBox\controller\NavigationControllers\SettingsPageController::class)
    ->setName("settings-page")->add(\PWBox\controller\middleware\LoginMiddleware::class);
