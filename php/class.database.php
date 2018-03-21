<?php
class Database {
	protected $_link;
	protected $_result;
	protected $_numRows;

	// private $_host = "192.168.1.250";
	private $_host = "177.227.109.25";
	private $_port = 60033;
	// private $_port = 3306;
	private $_username = "web";
	private $_password = "webfmolvera17";
	private $_database = "datosa";

	/*private $_host = "67.227.237.109";
	private $_username = "zizaram1_datosaF";
	private $_password = "dwzyGskl@@.W";
	private $_database = "zizaram1_datosa";*/

	// Establish connection to database, when class is instantiated
	public function __construct() {
		// $this->_link = new mysqli($this->_host, $this->_username, $this->_password, $this->_database, $this->port);
		$this->_link = new mysqli($this->_host, $this->_username, $this->_password, $this->_database);
		$this->_link->set_charset("utf8");

		if(mysqli_connect_errno()) {
			echo "Connection Failed: " . mysqli_connect_errno();
			// TODO make function to redirect, depend on url; i mean, check how many slashes it has.
			exit();
		}
	}

	// Used by other classes to get the connection
	public function GetLink() {
		return $this->_link;
	}

	// Return the number of rows
	public function NumRows() {
		return $this->_numRows;
	}

	// Sends the query to the connection
	public function Query($sql) {
		$this->_result = $this->_link->query($sql);
		// Reference to $this->_linl->error: https://goo.gl/8kSfzK, find the firs match: getTraceAsString
		if ($this->_link->error) {
			$this->showAndWriteError($sql);
			return false;
		}
		if (!$this->_link->error) $this->_numRows = mysqli_num_rows($this->_result);
	}

	// Fetchs the rows and return them
	public function Rows() {
		$rows = array();

		for($x = 0; $x < $this->NumRows(); $x++) {
			$rows[] = mysqli_fetch_assoc($this->_result);
		}
		return $rows;
	}

	/* NOTE if you want to decrypt file: Example
		require_once(path_to_class.encrypt/encrypt.php);
		$key = "ferremayori_123_iugsdkhg";
		$crypt = new Encryption($key);

		$fichero = "path_to_file/log.txt";
		$actual = file_get_contents($fichero);
		$actual = $crypt->decrypt($actual);
		file_put_contents($fichero, $actual);
	*/
	// NOTE Only keys of sizes 16, 24 or 32 supported to class encryption
	// Reference encript https://goo.gl/tTeIxm
	public function SaveLog($descriptionError) {
		$urlEncryption = "tienda/php/classes/class.encryption.php";
		$urlEncryption = $this->UrlConstructPathBack(getcwd()) . $urlEncryption;
		require_once($urlEncryption);
		$key = "ferremayori_123_iugsdkhg";
		$crypt = new Encryption($key);

		$urlFileLogs = "tienda/php/general/log.txt";
		$fichero = $this->UrlConstructPathBack(getcwd()) . $urlFileLogs;
		// Abre el fichero para obtener el contenido existente
		$actual = file_get_contents($fichero);
		if(strlen($actual) > 0) {
			// $actual = $crypt->decrypt($actual); // TEST comment
		}
		// Añade un registro al archivo
		$date = new DateTime("now", new DateTimeZone("America/Mexico_City"));
		$date = $date->format("Y-m-d H:i:s");
		$simbols = "######################################################################";
		$actual .= $descriptionError . $date . "\n" . $simbols . "\n";
		// $encrypted_string = $crypt->encrypt($actual); // TEST comment
		// Escribe el contenido al fichero
		file_put_contents($fichero, $actual);
	}

	// Securing input data
	public function SecureInput($value) {
		return mysqli_real_escape_string($this->_link, $value);
	}

	public function showAndWriteError($sql) {
		$error = "";
		try {
			throw new Exception("MySQL error: " . $this->_link->error . "\nQuery: $sql", $this->_link->errno);
		} catch(Exception $e ) {
			$error .= "Error No: ".$e->getCode(). " - ". $e->getMessage() . "\n";
			$error .= $e->getTraceAsString() . "\n";
			// echo "Problemas con la base de datos <br>";
			$this->SaveLog($error);
		}
	}

	// Inserts into databse
	public function UpdateDb($sql) {
		$this->_result = $this->_link->query($sql);
		if ($this->_link->error) {
			$this->showAndWriteError($sql);
			return false;
		}
		if (!$this->_link->error) return $this->_result;
	}

	// receive path, and define main path, then check where is it
	// count items(subfolders) after main and return string like ../, depend count
	// and add to main path from anothers files, because in require_once
	// is the path relative of file that make require and concat path of file and return this function like ../general.txt or ../../classes/classes.encryption.php
	// NOTE testing when change of domain, echo url and set main path
	public function UrlConstructPathBack($url) {
		$util = array("arrayUrl" => explode("/", $url),
									"mainPath" => "fmo",
									"flagMainPath" => false,
									"urlResult" => "",
									"urlBack" => "");

		$i = 0;
		foreach ($util["arrayUrl"] as $key => $value) {
			if($util["flagMainPath"]) {
				$util["urlResult"] .= $value . "/";
				$util["urlBack"] .= "../";
				$i++;
			}
			if($value === $util["mainPath"]) $util["flagMainPath"] = true;
		}
		return $util["urlBack"];
	}
}
?>
