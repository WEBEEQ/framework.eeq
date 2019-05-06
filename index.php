<?php
declare(strict_types = 1);

require('src/Core/core.php');

use AppBundle\Controller\{
    AddUserController,
    ContactFormController,
    MainPageController,
    UserSearchController,
    UserDataController
};
use AppBundle\Core\Param;

switch ($_GET['option']) {
    case '':
    case 'main-page':
        $level = Param::prepareInt($_GET['level'], 1);

        $title = 'Top Osoba';
        $activeMenu = 'main-page';
        $content = 'src/View/main-page.php';
        $mainPageController = new MainPageController();
        $array = $mainPageController->mainPageAction($url, $level);
        break;
    case 'user-data':
        $user = Param::prepareInt($_GET['user']);

        $activeMenu = '';
        $content = 'src/View/user-data.php';
        $userDataController = new UserDataController();
        $array = $userDataController->userDataAction($url, $user);
        $title = $array['title'];
        break;
    case 'add-user':
        $name = Param::prepareString($_POST['name']);
        $surname = Param::prepareString($_POST['surname']);
        $province = Param::prepareInt($_POST['province']);
        $city = Param::prepareInt($_POST['city']);
        $description = Param::prepareString($_POST['description']);
        $submit = Param::prepareBool($_POST['submit']);

        $remoteAddress = $_SERVER['REMOTE_ADDR'];
        $date = date('Y-m-d H:i:s');

        $title = 'Dodawanie osoby';
        $activeMenu = 'add-user';
        $content = 'src/View/add-user.php';
        $addUserController = new AddUserController();
        $array = $addUserController->addUserAction(
            $name,
            $surname,
            $province,
            $city,
            $description,
            $submit,
            $remoteAddress,
            $date
        );
        break;
    case 'user-search':
        $_GET['submit'] = ($_GET['submit']) ? 1 : 0;
        $parameter = Param::getParameter($_GET);

        $name = Param::prepareString($_GET['name']);
        $surname = Param::prepareString($_GET['surname']);
        $province = Param::prepareInt($_GET['province']);
        $city = Param::prepareInt($_GET['city']);
        $submit = Param::prepareBool((string) $_GET['submit']);
        $level = Param::prepareInt($_GET['level'], 1);

        $title = 'Szukanie osób';
        $activeMenu = 'user-search';
        $content = 'src/View/user-search.php';
        $userSearchController = new UserSearchController();
        $array = $userSearchController->userSearchAction(
            $url,
            $parameter,
            $name,
            $surname,
            $province,
            $city,
            $submit,
            $level
        );
        break;
    case 'contact-form':
        $email = stripslashes(trim($_POST['email'] ?? ''));
        $subject = stripslashes(trim($_POST['subject'] ?? ''));
        $text = stripslashes(trim($_POST['message'] ?? ''));
        $submit = Param::prepareBool($_POST['submit']);

        $title = 'Kontakt';
        $activeMenu = 'contact-form';
        $content = 'src/View/contact-form.php';
        $contactFormController = new ContactFormController();
        $array = $contactFormController->contactFormAction($email, $subject, $text, $submit);
        break;
    default:
        $title = '';
        $activeMenu = '';
        $content = '';
        $array = null;
        break;
}

include('src/Layout/main.php');
