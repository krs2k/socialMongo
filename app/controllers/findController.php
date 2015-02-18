<?php
require_once __DIR__ . '/../models/userModel.php';

/**
 * Class FindController
 */
class FindController extends Controller
{

    /**
     *
     */
    public function autocomplete()
    {
        $this->isLogged();
        $User = new UserModel();
        $term = $_GET["term"];
        $results = $User->find(
            [
                '$or' => [
                    ['name' => array('$regex' => $term, '$options' => 'i')],
                    ['lastName' => array('$regex' => $term, '$options' => 'i')],
                ]
            ], ['name', 'lastName'])->limit(15);
        $results = iterator_to_array($results, false);
        $this->renderJson($results);
    }
}
