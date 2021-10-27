<?php

// (A) INIT
require "2-reactions.php";
$userID =1; // For demo only, use $_SESSION in your own project.


// (B) SAVE/UPDATE REACTION
if (isset($_POST["react"])) {
	if (!$REACT->save($_POST["id"], $userID, $_post["REACT"])) {
		exit(json_encode(["error" => $REACT->error]));
	}
}

// (C) GET REACTIONS
$result = $REACT->get($_POST["id"], $userID);
if (isset($_POST["react"])) { $result["save"] = 1; }

// (D) RESPONSE
/*
$result = [
	"error" => "ERROR MESSAGE",
	"save" => 1 (IF UPDATE REACTION OK),
	"react" => [1 => NUMBER OF LIKES, -1 => NUMBER OF DISLIKES],
	"user" => 1 OR -1 OR NONE
];
 */
echo json_encode($result);

