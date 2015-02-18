<?php
require_once __DIR__ . '/../models/userModel.php';

class LoginController extends Controller
{
    public function index()
    {
        //renderView('index');
    }
    public function attempt()
    {
        if (isset($_POST['email']) && isset($_POST['password']))
        {
            $User = new UserModel();
            $email =  $_POST['email'];
            $password = $_POST['password'];
  		
  			$user = $User->findByEmail($email, ['friends'=> 0, 'notify' => 0]);
  			if ($user && $user['password'] == $User->hashPassword($password))
            {
  				$_SESSION["userId"] = $user['_id'];
                self::redirect('users', 'show');
            }
  			else
            {
  				$this->view->addNotify("Email or password is incorrect.", 'error');
                $this->renderView('index');
  			}
  		}
    }
    public function logout()
    {
        $this->isLogged();
        $_SESSION["userId"] = NULL;
        self::redirect('login', 'index');
    }
}
?>