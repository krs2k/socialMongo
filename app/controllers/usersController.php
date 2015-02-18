<?php
require_once __DIR__ . '/../models/userModel.php';
require_once __DIR__ . '/../helpers/usersHelper.php';

/**
 * Class UsersController
 */
class UsersController extends Controller
{
    /**Render add user view
     *
     */
    public function add()
    {
        //renderView('add');
    }

    /** Create new User
     *
     */
    public function create()
    {
        $userData = $_POST;
        if ($userData['password'] != $userData['rPassword']) {
            $this->view->addNotify("password doesn't match", 'error');
            $this->renderView('add');
        } else {
            $User = new UserModel();
            $userData['password'] = $User->hashPassword($userData['password']);
            $res = $User->insert($userData);
            if (isset($res['error'])) {
                $this->view->addNotifies($res['error'], 'error');
                $this->renderView('add');
            } else {
                $_SESSION['userId'] = $res['_id'];
                $this->redirect('users', 'show&id=' . $res['_id']);
            }

        }
    }

    /** Show user data/profile
     *
     */
    public function show()
    {
        $this->isLogged();
        $User = new UserModel();
        $Helper = new UsersHelper();

        $me = $User->findById($_SESSION['userId']);

        if (isset($_GET['id']) && $_GET['id'] != $_SESSION['userId']) {
            $user = $User->findByID($_GET['id']);
            $relation = $Helper->getRelationBetweenUsers($me, $user);
        } else {
            $user = $me;
            $relation = "me";
        }
        $me['friends'] = $User->getFriendsPublicInfo($me);
        $me['notifies'] = $User->getNotifiesText($me['notifies']);
        $this->view->data['user'] = $user;
        $this->view->data['me'] = $me;
        $this->view->data['relation'] = $relation;
    }

    /**
     *
     */
    public function edit()
    {
        $this->isLogged();
        $User = new UserModel();
        $me = $User->findById($_SESSION['userId']);
        $this->view->data['me'] = $me;
    }

    /**
     *
     */
    public function update()
    {
        $this->isLogged();
        $userData['city'] = $_POST['city'];
        $userData['street'] = $_POST['street'];
        $userData['country'] = $_POST['country'];
        $userData['job'] = $_POST['job'];
        $userData['age'] = $_POST['age'];
        $myId = $_SESSION['userId'];
        $User = new UserModel();

        $res = $User->update(['_id' => $myId], ['$set' => $userData]);
        if (isset($res['error'])) {
            $this->view->addNotifies($res['error'], 'error');
            $this->view->data['me'] = $userData;
            $this->renderView('edit');
        } else {
            $this->redirect('users', 'show&id=' . $myId);
        }
    }
}
