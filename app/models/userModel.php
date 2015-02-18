<?php

class userModel extends Model 
{
	protected $schema = array(
		"name" => [
			"type" => "string",
			"required" => true
		],
		"lastName" => [
			"type" => "string",
			"required" => true
		],
		"email"  => [
			"type" => "email",
			"required" => true,
			"unique" =>true
		],
		"password"  => [
			"type" => "string",
			"required" => true
		],
		"age" => [
			"default" => 0,
			"type" => "number"
		],
        "job" => [
			"default" => ""
		],
        "country"  => [
			"default" => ""
		],
        "city" =>  [
			"default" => ""
		],
        "street" => [
			"default" => ""
		],
        'friends' => [
			"default" => []
		],
		'notifies' => [
			"default" => []
		]
	);

	public function findByEmail($email, $fields = array())
	{
		return $this->findOne(array('email' => $email ), $fields);
	}
	public function findById($id, $fields = array())
	{
		return $this->findOne(array('_id' =>new MongoId($id)), $fields);
	}
	public function getFriendsPublicInfo($user)
	{
		if ( $user['friends'])
			return iterator_to_array($this->find(['_id'=> ['$in'=> $user['friends']], 'friends' => $user['_id']], ['name'=>1, "lastName"=>1]),false);
	}
	public function addFriend($inviterUserId, $invitedUserId){
		$res = $this->update(['_id'=> $invitedUserId, 'friends' => ['$ne'=> $inviterUserId ]],['$push'=> ['friends' => $inviterUserId]]);
		return $res['updatedExisting'];
	}
	public function hashPassword($passord)
	{
	    $salt = "ASJ4()sf0skdDVka&sfhsDFdha*SF#$^fDFfJ8)@(KMDVsdf)cksd";
	    return sha1($passord . $salt);
	}
	public function addNotify($userId, $notifyContent)
	{
		$this->update(["_id"=>$userId],['$push' => ["notifies" => $notifyContent]]);
	}
	public function getNotifiesText($notify)
	{
		$freshNotify = array();
		foreach ($notify as $not)
		{
			$user = $this->findOne(['_id'=> $not['userId']],["name", "lastName"]);
			$text = str_replace('@user', $user['name'].' '.$user['lastName'], $not['text']);
			$text = $text .'#'. $not['userId']->__toString();
			array_push($freshNotify,$text);
		}
		return $freshNotify;
	}
}

?>