<?php
declare(strict_types = 1);

require('src/Core/core.php');

use AppBundle\Controller\{
    AddController,
    ContactController,
    MainController,
    SearchController,
    UserController
};
use AppBundle\Core\Param;

switch ($_GET['option']) {
    case '':
    case 'page':
        $level = (is_numeric($_GET['level']) && $_GET['level'] >= 1) ? (int) $_GET['level'] : 1;

        $title = 'Top Osoba';
        $activeMenu = 'main';
        $content = 'src/View/index.php';
        $mainController = new MainController();
        $array = $mainController->mainAction($url, $level);
        break;
    case 'person':
        $user = (is_numeric($_GET['user']) && $_GET['user'] >= 1) ? (int) $_GET['user'] : 0;

        $activeMenu = '';
        $content = 'src/View/user.php';
        $userController = new UserController();
        $array = $userController->userAction($url, $user);
        $title = $array['title'];
        break;
    case 'addition':
        $name = Param::prepare($_POST['name'] ?? '');
        $surname = Param::prepare($_POST['surname'] ?? '');
        $province = (is_numeric($_POST['province']) && $_POST['province'] >= 1) ? (int) $_POST['province'] : 0;
        $city = (is_numeric($_POST['city']) && $_POST['city'] >= 1) ? (int) $_POST['city'] : 0;
        $description = Param::prepare($_POST['description'] ?? '');
        $submit = ($_POST['submit']) ? true : false;

        $remoteAddress = $_SERVER['REMOTE_ADDR'];
        $date = date('Y-m-d H:i:s');

        $title = 'Dodawanie osoby';
        $activeMenu = 'addition';
        $content = 'src/View/add.php';
        $addController = new AddController();
        $array = $addController->addAction(
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
    case 'search':
        $_GET['submit'] = ($_GET['submit']) ? 1 : 0;
        $parameter = Param::getParameter($_GET);

        $name = Param::prepare($_GET['name'] ?? '');
        $surname = Param::prepare($_GET['surname'] ?? '');
        $province = (is_numeric($_GET['province']) && $_GET['province'] >= 1) ? (int) $_GET['province'] : 0;
        $city = (is_numeric($_GET['city']) && $_GET['city'] >= 1) ? (int) $_GET['city'] : 0;
        $submit = ($_GET['submit']) ? true : false;
        $level = (is_numeric($_GET['level']) && $_GET['level'] >= 1) ? (int) $_GET['level'] : 1;

        $title = 'Szukanie osób';
        $activeMenu = 'search';
        $content = 'src/View/search.php';
        $searchController = new SearchController();
        $array = $searchController->searchAction(
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
    case 'contact':
        $email = stripslashes(trim($_POST['email'] ?? ''));
        $subject = stripslashes(trim($_POST['subject'] ?? ''));
        $text = stripslashes(trim($_POST['message'] ?? ''));
        $submit = ($_POST['submit']) ? true : false;

        $title = 'Kontakt';
        $activeMenu = 'contact';
        $content = 'src/View/contact.php';
        $contactController = new ContactController();
        $array = $contactController->contactAction($email, $subject, $text, $submit);
        break;
    default:
        $title = '';
        $activeMenu = '';
        $content = '';
        $array = null;
        break;
}

include('src/Layout/main.php');
