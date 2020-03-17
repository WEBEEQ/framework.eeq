<?php

declare(strict_types=1);

require('src/Core/core.php');

use App\Controller\{
    AcceptSiteController,
    ActivateUserController,
    AdminAccountController,
    ChangePasswordController,
    ContactFormController,
    EditSiteController,
    EditUserController,
    LogInUserController,
    LogOutUserController,
    MainPageController,
    RegisterUserController,
    ResetPasswordController,
    ShowInfoController,
    ShowLinkController,
    ShowSiteController,
    UserAccountController,
    UserHelpController,
    UserPrivacyController,
    UserRegulationController
};
use App\Controller\Ajax\CityListController;
use App\Controller\Api\{
    AddSiteController,
    DeleteSiteController,
    UpdateSiteController
};
use App\Core\{Config, CookieLogin, Param};

$config = new Config();
$param = new Param();
$cookieLogin = new CookieLogin($config, $param);
$cookieLogin->setCookieLogin();

switch ($_GET['option']) {
    case 'user-account':
    case 'edit-user':
    case 'edit-site':
    case 'show-link':
    case 'show-site':
        if (!$_SESSION['user']) {
            header('Location: ' . $config->getUrl() . '/logowanie');
            exit;
        }
        break;
    case 'admin-account':
    case 'accept-site':
        if (!$_SESSION['admin']) {
            header('Location: ' . $config->getUrl() . '/logowanie');
            exit;
        }
        break;
    default:
        break;
}

switch ($_GET['option']) {
    case '':
    case 'main-page':
        $mainPageController = new MainPageController();
        $array = $mainPageController->mainPageAction();
        break;
    case 'register-user':
        $name = $param->prepareString($_POST['name']);
        $surname = $param->prepareString($_POST['surname']);
        $login = $param->prepareString($_POST['login']);
        $password = $param->preparePassString($_POST['password']);
        $repeatPassword = $param->preparePassString($_POST['repeat_password']);
        $email = $param->prepareString($_POST['email']);
        $repeatEmail = $param->prepareString($_POST['repeat_email']);
        $accept = $param->prepareBool($_POST['accept']);
        $submit = $param->prepareBool($_POST['submit']);
        $token = $param->prepareString($_POST['token']);

        $registerUserController = new RegisterUserController();
        $array = $registerUserController->registerUserAction(
            $name,
            $surname,
            $login,
            $password,
            $repeatPassword,
            $email,
            $repeatEmail,
            $accept,
            $submit,
            $token
        );
        break;
    case 'activate-user':
        $user = $param->prepareString($_GET['user']);
        $code = $param->prepareString($_GET['code']);

        $activateUserController = new ActivateUserController();
        $array = $activateUserController->activateUserAction($user, $code);
        break;
    case 'log-in-user':
        $login = $param->prepareString($_POST['login']);
        $password = $param->preparePassString($_POST['password']);
        $remember = $param->prepareBool($_POST['remember']);
        $submit = $param->prepareBool($_POST['submit']);
        $token = $param->prepareString($_POST['token']);

        $logInUserController = new LogInUserController();
        $array = $logInUserController->logInUserAction(
            $login,
            $password,
            $remember,
            $submit,
            $token
        );
        break;
    case 'reset-password':
        $login = $param->prepareString($_POST['login']);
        $submit = $param->prepareBool($_POST['submit']);
        $token = $param->prepareString($_POST['token']);

        $resetPasswordController = new ResetPasswordController();
        $array = $resetPasswordController->resetPasswordAction(
            $login,
            $submit,
            $token
        );
        break;
    case 'change-password':
        $newPassword = $param->preparePassString($_POST['new_password']);
        $repeatPassword = $param->preparePassString($_POST['repeat_password']);
        $submit = $param->prepareBool($_POST['submit']);
        $token = $param->prepareString($_POST['token']);
        $user = $param->prepareString($_GET['user']);
        $code = $param->prepareString($_GET['code']);

        $changePasswordController = new ChangePasswordController();
        $array = $changePasswordController->changePasswordAction(
            $newPassword,
            $repeatPassword,
            $submit,
            $token,
            $user,
            $code
        );
        break;
    case 'user-account':
        $name = $param->prepareString($_POST['name']);
        $www = $param->prepareString($_POST['www']);
        $submit = $param->prepareBool($_POST['submit']);
        $token = $param->prepareString($_POST['token']);
        $account = $param->prepareInt($_GET['account']);
        $level = $param->prepareInt($_GET['level'], 1);

        $id = (int) $_SESSION['id'];

        $userAccountController = new UserAccountController();
        $array = $userAccountController->userAccountAction(
            $name,
            $www,
            $submit,
            $token,
            $account,
            $level,
            $id
        );
        break;
    case 'edit-user':
        $lastLogin = $param->prepareString($_POST['login']);
        $password = $param->preparePassString($_POST['password']);
        $newPassword = $param->preparePassString($_POST['new_password']);
        $repeatPassword = $param->preparePassString($_POST['repeat_password']);
        $name = $param->prepareString($_POST['name']);
        $surname = $param->prepareString($_POST['surname']);
        $street = $param->prepareString($_POST['street']);
        $postcode = $param->prepareString($_POST['postcode']);
        $province = $param->prepareInt($_POST['province']);
        $city = $param->prepareInt($_POST['city']);
        $phone = $param->prepareString($_POST['phone']);
        $email = $param->prepareString($_POST['email']);
        $newEmail = $param->prepareString($_POST['new_email']);
        $repeatEmail = $param->prepareString($_POST['repeat_email']);
        $www = $param->prepareString($_POST['www']);
        $description = $param->prepareString($_POST['description']);
        $submit = $param->prepareBool($_POST['submit']);
        $token = $param->prepareString($_POST['token']);
        $user = $param->prepareInt($_GET['user']);

        $id = (int) $_SESSION['id'];
        $login = $_SESSION['user'];

        $editUserController = new EditUserController();
        $array = $editUserController->editUserAction(
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
            $token,
            $user,
            $id,
            $login
        );
        break;
    case 'edit-site':
        $name = $param->prepareString($_POST['name']);
        $www = $param->prepareString($_POST['www']);
        $visible = $param->prepareIntBool($_POST['visible']);
        $delete = $param->prepareBool($_POST['delete']);
        $submit = $param->prepareBool($_POST['submit']);
        $token = $param->prepareString($_POST['token']);
        $site = $param->prepareInt($_GET['site']);

        $id = (int) $_SESSION['id'];

        $editSiteController = new EditSiteController();
        $array = $editSiteController->editSiteAction(
            $name,
            $www,
            $visible,
            $delete,
            $submit,
            $token,
            $site,
            $id
        );
        break;
    case 'admin-account':
        $account = $param->prepareInt($_GET['account']);
        $level = $param->prepareInt($_GET['level'], 1);

        $id = (int) $_SESSION['id'];

        $adminAccountController = new AdminAccountController();
        $array = $adminAccountController->adminAccountAction(
            $account,
            $level,
            $id
        );
        break;
    case 'accept-site':
        $name = $param->prepareString($_POST['name']);
        $www = $param->prepareString($_POST['www']);
        $active = $param->prepareIntBool($_POST['active']);
        $visible = $param->prepareIntBool($_POST['visible']);
        $delete = $param->prepareBool($_POST['delete']);
        $submit = $param->prepareBool($_POST['submit']);
        $token = $param->prepareString($_POST['token']);
        $site = $param->prepareInt($_GET['site']);

        $acceptSiteController = new AcceptSiteController();
        $array = $acceptSiteController->acceptSiteAction(
            $name,
            $www,
            $active,
            $visible,
            $delete,
            $submit,
            $token,
            $site
        );
        break;
    case 'log-out-user':
        $logOutUserController = new LogOutUserController();
        $array = $logOutUserController->logOutUserAction();
        break;
    case 'user-regulation':
        $userRegulationController = new UserRegulationController();
        $array = $userRegulationController->userRegulationAction();
        break;
    case 'user-privacy':
        $userPrivacyController = new UserPrivacyController();
        $array = $userPrivacyController->userPrivacyAction();
        break;
    case 'user-help':
        $userHelpController = new UserHelpController();
        $array = $userHelpController->userHelpAction();
        break;
    case 'contact-form':
        $email = stripslashes(trim($_POST['email'] ?? ''));
        $subject = stripslashes(trim($_POST['subject'] ?? ''));
        $text = stripslashes(trim($_POST['message'] ?? ''));
        $submit = $param->prepareBool($_POST['submit']);
        $token = $param->prepareString($_POST['token']);

        $contactFormController = new ContactFormController();
        $array = $contactFormController->contactFormAction(
            $email,
            $subject,
            $text,
            $submit,
            $token
        );
        break;
    case 'show-link':
        $www = $_GET['www'];

        $showLinkController = new ShowLinkController();
        $array = $showLinkController->showLinkAction($www);
        break;
    case 'show-site':
        $id = (int) $_SESSION['id'];

        $showSiteController = new ShowSiteController();
        $array = $showSiteController->showSiteAction($id);
        break;
    case 'show-info':
        $showInfoController = new ShowInfoController();
        $array = $showInfoController->showInfoAction();
        break;
    case 'city-list':
        $province = $param->prepareInt($_GET['province']);

        $cityListController = new CityListController();
        $array = $cityListController->cityListAction($province);

        include($array['content']);
        exit;
        break;
    case 'add-site':
        $user = $param->prepareString((string) $_SERVER['PHP_AUTH_USER']);
        $password = $param->preparePassString(
            (string) $_SERVER['PHP_AUTH_PW']
        );

        $data = json_decode(file_get_contents('php://input'), true);

        $name = $param->prepareString((string) $data['name']);
        $www = $param->prepareString((string) $data['www']);

        $addSiteController = new AddSiteController();
        $array = $addSiteController->addSiteAction(
            $user,
            $password,
            $name,
            $www
        );

        echo json_encode($array);
        exit;
        break;
    case 'update-site':
        $user = $param->prepareString((string) $_SERVER['PHP_AUTH_USER']);
        $password = $param->preparePassString(
            (string) $_SERVER['PHP_AUTH_PW']
        );

        $data = json_decode(file_get_contents('php://input'), true);

        $id = $param->prepareInt((string) $data['id']);
        $name = $param->prepareString((string) $data['name']);
        $visible = $param->prepareIntBool((string) $data['visible']);

        $updateSiteController = new UpdateSiteController();
        $array = $updateSiteController->updateSiteAction(
            $user,
            $password,
            $id,
            $name,
            $visible
        );

        echo json_encode($array);
        exit;
        break;
    case 'delete-site':
        $user = $param->prepareString((string) $_SERVER['PHP_AUTH_USER']);
        $password = $param->preparePassString(
            (string) $_SERVER['PHP_AUTH_PW']
        );

        $data = json_decode(file_get_contents('php://input'), true);

        $id = $param->prepareInt((string) $data['id']);

        $deleteSiteController = new DeleteSiteController();
        $array = $deleteSiteController->deleteSiteAction(
            $user,
            $password,
            $id
        );

        echo json_encode($array);
        exit;
        break;
    default:
        $array = array();

        $array['layout'] = 'src/Layout/main/main.php';
        $array['content'] = 'src/View/default/default.php';
        $array['activeMenu'] = '';
        $array['title'] = 'Pusta strona';
        break;
}

$array['url'] = $config->getUrl();

include($array['layout']);
