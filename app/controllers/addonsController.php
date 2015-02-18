<?php
require_once __DIR__ . '/../models/userModel.php';

/**
 * Class AddonsController
 */
class AddonsController extends Controller
{
    /**
     *
     */
    public function index()
    {
        //renderView('index');
    }

    /**
     *
     */
    public function addUsers()
    {
        $User = new UserModel();
        $namesList = file_get_contents(__DIR__ . '/../../namesList.txt');
        $lastNamesList = file_get_contents(__DIR__ . '/../../lastNamesList.txt');
        $namesList = explode("\n", $namesList);
        $lastNamesList = explode("\n", $lastNamesList);
        $usersArray = array();

        for ($i = 0; $i < 10000; $i++) {
            $randForName = mt_rand(0, count($namesList) - 1);
            $randForLastName = mt_rand(0, count($lastNamesList) - 1);
            $randForEmail = mt_rand(0, 10000);
            $newUserEmail = $namesList[$randForName] . "." . $lastNamesList[$randForLastName] . $randForEmail . "@a.com";
            $newUser = [
                'name' => ucfirst($namesList[$randForName]),
                'lastName' => ucfirst($lastNamesList[$randForLastName]),
                'email' => $newUserEmail,
                'notifies' => array(),
                'friends' => array(),
                'password' => $User->hashPassword("a"),
                'job' => '',
                'country' => '',
                'city' => '',
                'street' => '',
                'age' => 0
            ];
            array_push($usersArray, $newUser);
        }
        $mongoRes = $User->batchInsert($usersArray);
        $this->renderJson(['status' => $mongoRes['ok']]);
    }

    /**
     *
     */
    public function getCountOfUsers()
    {
        $User = new UserModel();
        $countOfUsers = $User->count();
        $this->renderJson(['count' => $countOfUsers]);
    }

}
