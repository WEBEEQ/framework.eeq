<?php declare(strict_types=1);

require('src/Core/core.php');

use App\Controller\{
    AcceptSiteController,
    AddUserController,
    AdminAccountController,
    AjaxController,
    ContactFormController,
    EditSiteController,
    EditUserController,
    LoginUserController,
    RestController,
    ShowSiteController,
    UserAccountController
};
use App\Core\{Login, Param};

if ($_SESSION['user'] == '' && !empty($_COOKIE['login'])) {
    $login = new Login();
    $login->cookieLogIn();
}

switch ($_GET['option']) {
    case 'user-account':
    case 'edit-user':
    case 'edit-site':
    case 'show-link':
    case 'show-site':
        if ($_SESSION['user'] == '') {
            header('Location: ' . $url . '/logowanie');
            exit;
        }
        break;
    case 'admin-account':
    case 'accept-site':
        if ($_SESSION['admin'] == 0) {
            header('Location: ' . $url . '/logowanie');
            exit;
        }
        break;
    default:
        break;
}

switch ($_GET['option']) {
    case '':
    case 'main-page':
        $title = 'SIECIQ - Sieć reklamowa dla Państwa stron';
        $activeMenu = 'main-page';
        $content = 'src/View/main-page.php';
        break;
    case 'add-user':
        $name = Param::prepareString($_POST['name']);
        $surname = Param::prepareString($_POST['surname']);
        $login = Param::prepareString($_POST['login']);
        $password = str_replace(
            '&amp;',
            '&',
            Param::prepareString($_POST['password'])
        );
        $repeatPassword = str_replace(
            '&amp;',
            '&',
            Param::prepareString($_POST['repeat_password'])
        );
        $email = Param::prepareString($_POST['email']);
        $repeatEmail = Param::prepareString($_POST['repeat_email']);
        $accept = Param::prepareBool($_POST['accept']);
        $submit = Param::prepareBool($_POST['submit']);
        $user = Param::prepareString($_GET['user']);
        $code = Param::prepareString($_GET['code']);

        $remoteAddress = $_SERVER['REMOTE_ADDR'];
        $date = date('Y-m-d H:i:s');

        $title = 'Rejestracja';
        $activeMenu = 'add-user';
        $content = 'src/View/add-user.php';
        $addUserController = new AddUserController();
        $array = $addUserController->addUserAction(
            $name,
            $surname,
            $login,
            $password,
            $repeatPassword,
            $email,
            $repeatEmail,
            $accept,
            $submit,
            $user,
            $code,
            $remoteAddress,
            $date
        );
        break;
    case 'login-user':
        $login = Param::prepareString($_POST['login']);
        $password = str_replace(
            '&amp;',
            '&',
            Param::prepareString($_POST['password'])
        );
        $forget = Param::prepareBool($_POST['forget']);
        $remember = Param::prepareBool($_POST['remember']);
        $submit = Param::prepareBool($_POST['submit']);
        $user = Param::prepareString($_GET['user']);
        $code = Param::prepareString($_GET['code']);

        $remoteAddress = $_SERVER['REMOTE_ADDR'];
        $date = date('Y-m-d H:i:s');

        $title = 'Logowanie';
        $activeMenu = 'login-user';
        $content = 'src/View/login-user.php';
        $loginUserController = new LoginUserController();
        $array = $loginUserController->loginUserAction(
            $login,
            $password,
            $forget,
            $remember,
            $submit,
            $user,
            $code,
            $remoteAddress,
            $date
        );
        break;
    case 'user-account':
        $name = Param::prepareString($_POST['name']);
        $www = Param::prepareString($_POST['www']);
        $submit = Param::prepareBool($_POST['submit']);
        $account = Param::prepareInt($_GET['account']);
        $level = Param::prepareInt($_GET['level'], 1);

        $remoteAddress = $_SERVER['REMOTE_ADDR'];
        $date = date('Y-m-d H:i:s');
        $id = (int) $_SESSION['id'];

        if ($account && $account != $id) {
            header('Location: ' . $url . '/logowanie');
            exit;
        }

        $title = 'Konto';
        $activeMenu = 'user-account';
        $content = 'src/View/user-account.php';
        $userAccountController = new UserAccountController();
        $array = $userAccountController->userAccountAction(
            $url,
            $name,
            $www,
            $submit,
            $level,
            $remoteAddress,
            $date,
            $id
        );
        break;
    case 'edit-user':
        $lastLogin = Param::prepareString($_POST['login']);
        $password = str_replace(
            '&amp;',
            '&',
            Param::prepareString($_POST['password'])
        );
        $newPassword = str_replace(
            '&amp;',
            '&',
            Param::prepareString($_POST['new_password'])
        );
        $repeatPassword = str_replace(
            '&amp;',
            '&',
            Param::prepareString($_POST['repeat_password'])
        );
        $name = Param::prepareString($_POST['name']);
        $surname = Param::prepareString($_POST['surname']);
        $street = Param::prepareString($_POST['street']);
        $postcode = Param::prepareString($_POST['postcode']);
        $province = Param::prepareInt($_POST['province']);
        $city = Param::prepareInt($_POST['city']);
        $phone = Param::prepareString($_POST['phone']);
        $email = Param::prepareString($_POST['email']);
        $newEmail = Param::prepareString($_POST['new_email']);
        $repeatEmail = Param::prepareString($_POST['repeat_email']);
        $www = Param::prepareString($_POST['www']);
        $description = Param::prepareString($_POST['description']);
        $submit = Param::prepareBool($_POST['submit']);
        $user = Param::prepareInt($_GET['user']);

        $remoteAddress = $_SERVER['REMOTE_ADDR'];
        $date = date('Y-m-d H:i:s');
        $id = (int) $_SESSION['id'];
        $login = $_SESSION['user'];

        $title = 'Edycja użytkownika';
        $activeMenu = 'edit-user';
        $content = 'src/View/edit-user.php';
        $editUserController = new EditUserController();
        $array = $editUserController->editUserAction(
            $url,
            $lastLogin,
            $password,
            $newPassword,
            $repeatPassword,
            $name,
            $surname,
            $street,
            $postcode,
            $province,
            $city,
            $phone,
            $email,
            $newEmail,
            $repeatEmail,
            $www,
            $description,
            $submit,
            $user,
            $remoteAddress,
            $date,
            $id,
            $login
        );
        break;
    case 'edit-site':
        $name = Param::prepareString($_POST['name']);
        $www = Param::prepareString($_POST['www']);
        $visible = Param::prepareIntBool($_POST['visible']);
        $delete = Param::prepareBool($_POST['delete']);
        $submit = Param::prepareBool($_POST['submit']);
        $site = Param::prepareInt($_GET['site']);

        $remoteAddress = $_SERVER['REMOTE_ADDR'];
        $date = date('Y-m-d H:i:s');
        $id = (int) $_SESSION['id'];

        $title = 'Edycja strony';
        $activeMenu = 'edit-site';
        $content = 'src/View/edit-site.php';
        $editSiteController = new EditSiteController();
        $array = $editSiteController->editSiteAction(
            $url,
            $name,
            $www,
            $visible,
            $delete,
            $submit,
            $site,
            $remoteAddress,
            $date,
            $id
        );
        break;
    case 'admin-account':
        $account = Param::prepareInt($_GET['account']);
        $level = Param::prepareInt($_GET['level'], 1);

        $id = (int) $_SESSION['id'];

        if ($account && $account != $id) {
            header('Location: ' . $url . '/logowanie');
            exit;
        }

        $title = 'Admin';
        $activeMenu = 'admin-account';
        $content = 'src/View/admin-account.php';
        $adminAccountController = new AdminAccountController();
        $array = $adminAccountController->adminAccountAction(
            $url,
            $level,
            $id
        );
        break;
    case 'accept-site':
        $name = Param::prepareString($_POST['name']);
        $www = Param::prepareString($_POST['www']);
        $active = Param::prepareIntBool($_POST['active']);
        $visible = Param::prepareIntBool($_POST['visible']);
        $delete = Param::prepareBool($_POST['delete']);
        $submit = Param::prepareBool($_POST['submit']);
        $site = Param::prepareInt($_GET['site']);

        $remoteAddress = $_SERVER['REMOTE_ADDR'];
        $date = date('Y-m-d H:i:s');

        $title = 'Akceptacja strony';
        $activeMenu = 'accept-site';
        $content = 'src/View/accept-site.php';
        $acceptSiteController = new AcceptSiteController();
        $array = $acceptSiteController->acceptSiteAction(
            $url,
            $name,
            $www,
            $active,
            $visible,
            $delete,
            $submit,
            $site,
            $remoteAddress,
            $date
        );
        break;
    case 'logout-user':
        session_destroy();
        setcookie('login', '', 0, '/');
        header('Location: ' . $url . '/logowanie');
        break;
    case 'user-regulation':
        $title = 'Regulamin';
        $activeMenu = 'user-regulation';
        $content = 'src/View/user-regulation.php';
        break;
    case 'user-privacy':
        $title = 'Prywatność';
        $activeMenu = 'user-privacy';
        $content = 'src/View/user-privacy.php';
        break;
    case 'user-help':
        $title = 'Pomoc';
        $activeMenu = 'user-help';
        $content = 'src/View/user-help.php';
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
        $array = $contactFormController->contactFormAction(
            $email,
            $subject,
            $text,
            $submit
        );
        break;
    case 'show-link':
        $array['www'] = $_GET['www'];

        $title = 'Podgląd strony';
        $activeMenu = 'show-link';
        $content = 'src/View/show-site.php';

        include($content);
        exit;
        break;
    case 'show-site':
        $id = (int) $_SESSION['id'];

        $title = 'Pokaz stron';
        $activeMenu = 'show-site';
        $content = 'src/View/show-site.php';
        $showSiteController = new ShowSiteController();
        $array = $showSiteController->showSiteAction($url, $id);

        include($content);
        exit;
        break;
    case 'show-info':
        $title = 'Informacja';
        $activeMenu = 'show-info';
        $content = 'src/View/show-info.php';

        include($content);
        exit;
        break;
    case 'city-list':
        $province = Param::prepareInt($_GET['province']);

        $content = 'src/View/ajax/city-list.php';
        $ajaxController = new AjaxController();
        $array = $ajaxController->cityListAction($province);

        include($content);
        exit;
        break;
    case 'add-site':
        $user = $_SERVER['PHP_AUTH_USER'] ?? '';
        $password = $_SERVER['PHP_AUTH_PW'] ?? '';

        $data = json_decode(file_get_contents('php://input'), true);

        $name = Param::prepareString((string) $data['name']);
        $www = Param::prepareString((string) $data['www']);

        $remoteAddress = $_SERVER['REMOTE_ADDR'];
        $date = date('Y-m-d H:i:s');

        $restController = new RestController();
        $array = $restController->addSiteAction(
            $user,
            $password,
            $name,
            $www,
            $remoteAddress,
            $date
        );

        echo json_encode($array);
        exit;
        break;
    case 'update-site':
        $user = $_SERVER['PHP_AUTH_USER'] ?? '';
        $password = $_SERVER['PHP_AUTH_PW'] ?? '';

        $data = json_decode(file_get_contents('php://input'), true);

        $id = Param::prepareInt((string) $data['id']);
        $name = Param::prepareString((string) $data['name']);
        $visible = Param::prepareIntBool((string) $data['visible']);

        $remoteAddress = $_SERVER['REMOTE_ADDR'];
        $date = date('Y-m-d H:i:s');

        $restController = new RestController();
        $array = $restController->updateSiteAction(
            $user,
            $password,
            $id,
            $name,
            $visible,
            $remoteAddress,
            $date
        );

        echo json_encode($array);
        exit;
        break;
    case 'delete-site':
        $user = $_SERVER['PHP_AUTH_USER'] ?? '';
        $password = $_SERVER['PHP_AUTH_PW'] ?? '';

        $data = json_decode(file_get_contents('php://input'), true);

        $id = Param::prepareInt((string) $data['id']);

        $restController = new RestController();
        $array = $restController->deleteSiteAction($user, $password, $id);

        echo json_encode($array);
        exit;
        break;
    default:
        $title = '';
        $activeMenu = '';
        $content = '';
        $array = null;
        break;
}

include('src/Layout/main.php');
