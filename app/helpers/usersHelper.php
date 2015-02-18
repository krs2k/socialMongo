<?php
class UsersHelper
{
	public function getRelationBetweenUsers($user1, $user2)
	{
		$relation = "unfriend";
		if (in_array($user2['_id'], $user1['friends'])){
			$relation = "request";
			if (in_array($user1['_id'], $user2
				['friends'])){
				$relation = "friend";
			}
		}
		if (in_array($user1['_id'], $user2['friends'])){
			$relation = "invite";
			if (in_array($user2['_id'], $user1
				['friends'])){
				$relation = "friend";
			}
		}
		return $relation;
	}
}