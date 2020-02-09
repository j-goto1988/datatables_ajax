<?php
const DSN = "";
const USER_NAME = "";
const PASS = "";

try {
	$free_word = null;

	if (isset($_REQUEST["search"]['value'])) {
		if ($_REQUEST["search"]['value'] !== '') {
			$free_word = $_REQUEST["search"]['value'];
		}
	}

	if (isset($_REQUEST["start"]) && isset($_REQUEST["length"])) {

		$order_column = "name";
		$order_dir = "asc";

		if (isset($_REQUEST["order"][0]["column"]) && isset($_REQUEST["order"][0]["dir"])) {
			if ($_REQUEST["order"][0]["column"] == 1) {
				$order_column = "number";
			}
			if ($_REQUEST["order"][0]["dir"] == "desc") {
				$order_dir = "desc";
			}
		}

		$dbh = new PDO(DSN, USER_NAME, PASS);
		$dbh->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
		$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

		//総件数取得
		$sql = "SELECT count(*) as count FROM player";
		$sth = $dbh->prepare($sql);
		$sth->execute();
		$total = $sth->fetch(PDO::FETCH_ASSOC);

		//表示データ取得
		if ($free_word === null) {
			$sql = "SELECT name,number,id FROM player 
				ORDER BY $order_column $order_dir LIMIT ?, ?";
			$sth = $dbh->prepare($sql);
			//
			$sth->bindParam(1, $_REQUEST["start"], PDO::PARAM_INT);
			$sth->bindParam(2, $_REQUEST["length"], PDO::PARAM_INT);
			$sth->execute();
			$ret = $sth->fetchAll(PDO::FETCH_ASSOC);
			$count = $total["count"];
		} else {
			$sql = "SELECT count(*) as count FROM player
				WHERE name like ? OR number like ?";
			$sth = $dbh->prepare($sql);
			//
			$free_word = "%".$free_word."%";
			$sth->bindParam(1, $free_word, PDO::PARAM_STR);
			$sth->bindParam(2, $free_word, PDO::PARAM_STR);
			$sth->execute();
			$ret = $sth->fetch(PDO::FETCH_ASSOC);
			$count = $ret["count"];

			$sql = "SELECT name,number,id FROM player
				WHERE name like ? OR number like ?
				ORDER BY $order_column $order_dir LIMIT ?, ?";
			$sth = $dbh->prepare($sql);

			$free_word = "%".$free_word."%";
			$sth->bindParam(1, $free_word, PDO::PARAM_STR);
			$sth->bindParam(2, $free_word, PDO::PARAM_STR);
			$sth->bindParam(3, $_REQUEST["start"], PDO::PARAM_INT);
			$sth->bindParam(4, $_REQUEST["length"], PDO::PARAM_INT);
			$sth->execute();
			$ret = $sth->fetchAll(PDO::FETCH_ASSOC);
		}


		$json_data = array(
			"draw" => intval($_REQUEST['draw']),
			"recordsTotal" => $total["count"], //総件数
			"recordsFiltered" => $count, //結果件数
			"data" => $ret
		);
		header("Content-Type: text/javascript; charset=utf-8");
		echo json_encode($json_data, JSON_UNESCAPED_SLASHES);
	}
} catch (Exception $e) {

}