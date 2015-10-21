<?php
class inputDb {

	private $db_host;
	private $db_name;
	private $db_user;
	private $db_passwd;
	private $db_link;
	private $query;
	private $result;

	public function __construct(array $settings) {
		if (!isset($settings['db_host'])
			|| !isset($settings['db_name'])
			|| !isset($settings['db_user'])
			|| !isset($settings['db_passwd']))
			throw new Exception('Make sure you are passing in the correct parameters');

		$this->db_host = $settings['db_host'];
		$this->db_name = $settings['db_name'];
		$this->db_user = $settings['db_user'];
		$this->db_passwd = $settings['db_passwd'];
	}

	public function connectDb() {
		$this->db_link = mysqli_connect($this->db_host, $this->db_user, $this->db_passwd, $this->db_name);
		if (!$this->db_link)
			throw new Exception('Could not connect to database');
		return $this;
	}

	public function sendQuery($string) {
		$this->result = '';
		if ($string == '')
			throw new Exception('Cannot submit an empty query');
		$this->result = mysqli_query($this->db_link, $string);

		$this->query = $string;

		return $this;
	}

	public function getQuery() {
		return $this->query;
	}

	public function getResult() {
		if ($this->result == '')
			throw new Exception('No result available');
		return array($this->result);
	}

	public function getDatabaseName() {
		return $this->db_name;
	}
}
?>