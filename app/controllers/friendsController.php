*<?php
require_once __DIR__ . '/../models/userModel.php';

class FriendsController extends Controller
{
    public function add()
    {
        $this->isLogged();
        if (isset($_GET['id']) && isset($_GET['action']))
        {
            $User = new UserModel();
            $userId = new MongoId($_GET['id']);
            $myId = $_SESSION['userId'];
            $action = $_GET['action'];

	        if ($userId != $myId)
            {
	            $res = $User->addFriend($myId, $userId);
	            if ($res)
                {
                    $me = $User->findById($myId, ["name", "lastName"]);
                    if ($action == "invite")
                        $text = "@user wants add you to friends";
                    else
                        $text = "@user has accpet you request";
                   
                    $body = [
                        "text" => $text,
                        "userId"=> $myId
                    ];
                    $User->addNotify($userId,$body);
	            }
                else
	                $this->view->addNotify('you cannot add this user', 'error');
	        }
            else
	            $this->view->addNotify('You cannot add yourself to your friends', 'error');
	    }
        else
            $this->view->addNotify("id or action missing", 'error');
        if ($this->view->notifies)
            $this->renderView('error/index');
        else
            $this->redirect('users', 'show&id='.$userId);
    }
}
?>