<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/Contacts/controllers/db.php');

function insert_person($name, $surname, $sex, $birthday, $comments, $userid) {
    // global $mysqli;
    // $name = $mysqli->real_escape_string($name);
    // $surname = $mysqli->real_escape_string($surname);
    // $comments = $mysqli->real_escape_string(comments);
	return Query("INSERT INTO `persons` (`userid`,`name`,`surname`,`sex`,`birthday`,`comments`) 
	VALUES ('". $userid ."','" . $name . "','" . $surname . "','" . $sex . "','" . $birthday . "','" . $comments . "')", debug_backtrace());
}

function insert_alias($alias) {
	$alias = ($alias);
	
	$aliasid = 0;
	if (NULL == $aliasid = exists_alias($alias))
		$aliasid = Query("INSERT INTO `aliases` (`alias`) VALUES ( '" . $alias . "' )", debug_backtrace());
	return $aliasid;
}

?>