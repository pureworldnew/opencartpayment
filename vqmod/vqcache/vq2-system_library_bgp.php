<?php
/*
 * Create a background shell process free of the shell, non-blocking background process 
 */
define('CONF','/var/www/html/gempackeddev/system/storage/logs/conf.c');

class BGP{
	
	protected $_comand;
	protected $_osWin;
	protected $registry;
	protected $_file;
	protected $_class;
	protected $_method;
	protected $_config;
	public static $stat;
	protected $running;
	protected $run_file;
	
	/**
	 * 
	 * @param string $arg0, $arg1 ...
	 * $arg0 is location of php file to run
	 * $arg1 ..is additional params to send to script
	 * 
	 * eg.  new BGP( '/home/cli_scripts/script.php', 'true', 'false' );
	 * 
	 */
	public function __construct($registry = array()){
		$this->registry = $registry;
		$this->_file = false;
		$this->_class = false;
		$this->_method = false;
		$this->running = array();
		$this->run_file = sys_get_temp_dir(). '/bgp.run';
		$this->loadState();
	}
	
	public static function readConfig() {
		$config_file = CONF;
		$config = @file_get_contents($config_file);
		return unserialize(base64_decode($config));
	}
	
	private function writeConfig() {
		$config_file = CONF;
		$config = base64_encode(serialize($this->_config));
		@file_put_contents($config_file,$config);
	}
	
	private function loadState() {
		$run_file = $this->run_file;
		$state = @file_get_contents($run_file);
		if ($state === false){
			$this->running = array();
			return;
		}
		$decode = unserialize(base64_decode($state));
		$this->running = array();
		foreach ($decode as $proc) {
			if (!posix_getpgid($proc['pid']))
				continue;
			array_push($this->running,$proc);
		}
	}
	
	private function saveState() {
		$run_file = $this->run_file;
		$state = base64_encode(serialize($this->running));
		@file_put_contents($run_file,$state);
	}
	
	public function killAll() {
		if (empty(($this->running)))
			$this->loadState();
		foreach ($this->running as $proc) {
			posix_kill($proc['pid'],9); 
		}
		unlink($this->run_file);
	}
	
	public static function check($file,$argv = array(),&$stats) {
		$config = self::readConfig();
		if (empty($argv)) {
			return;
		}
		$filename = isset($argv[0])?$argv[0]:false;
		if (!$file) {
			return;
		}
		
		$flag = false;
		foreach($config['tasks'] as $task) {
			if ($task['file'] === $file) {
				$stats['class'] = $task['class'];
				$stats['method'] = $task['method'];
				$stats['file'] = $task['file'];
				$stats['cmd'] = $task['cmd'];
				
				self::$stat = $stats;
				$flag = true;
				break;
			}
		}
		
		if (!$flag) {
			//not found task list
			$stats['method'] = false;
			return false;
		}
		return true;
	}
	
	public static function execSync($stats = array()) {
		if (empty($stats) && !empty(self::$stat)) {
			$stats = self::$stat;
		}else{
			return false;
		}
		
		
		if (isset($stats['class']) && $stats['class'] && isset($stats['method']) && $stats['method']) {
			$path = pathinfo($stats['file']);
			chdir($path['dirname']);
			$a = new $stats['class'](array());
			$m = $stats['method'];
			$a->$m();
		}else if (isset($stats['method']) && $stats['method']) {
			$path = pathinfo($stats['file']);
			chdir($path['dirname']);
			$m = $stats['method'];
			$m();
		}else{
			return false;
		}
		
		
	}
	
	public function execAsync($arg0) {
		$this->loadState();
		
		if(stripos(php_uname('s'), 'win') > -1){
			$this->_osWin = true;
		}else{
			$this->_osWin = false;
		}
		
		$args = func_get_args();
		if(empty($args)){
			throw new Exception(__CLASS__.' arguments required' );
		}
		
		$file = $this->_file;
		$script = escapeshellarg($file).' '.implode(' ', array_map('escapeshellarg', $args));
		if(false !== ($phpPath = $this->_getPHPExecutableFromPath())){

			if($this->_osWin){	
				$WshShell = new \COM('WScript.Shell');
				$cmd = 'cmd /C '.$phpPath.' '.$script;
				$oExec = $WshShell->Run($cmd, 0, false);
			}else{
				$cmd = $phpPath.' -f '.$script.' > /dev/null &';
				$this->_config['tasks'][] = array('cmd'=> $cmd,
												'class'=>isset($args[0])?$args[0]:false,
												'method'=>isset($args[1])?$args[1]:false,
												'args'=> implode(',', array_map('escapeshellarg', $args)),
												'file' => $file);
				$this->writeConfig();
				
				$path = pathinfo($file);
				chdir($path['dirname']);
				
				$descriptorspec = [
					0 => ['pipe', 'r'],
					1 => ['pipe', 'w'],
					2 => ['pipe', 'w']
				];
				$proc = proc_open($cmd, $descriptorspec, $pipes);
				$proc_details = proc_get_status($proc);
				$pid = $proc_details['pid'];
				//exec($cmd, $oExec);
			}
			
			if ($pid > 0) {
					$this->running[] = array('pid'=>$pid+1,'pid_base'=>$pid,'stats'=>json_encode($proc_details),'cmd'=>$cmd);
					$this->_comand[$pid] = $cmd;
				}
				
				$this->saveState();
				
			echo self::getStats();

		}else{
			throw new Exception( 'Could not find php executable' );
		}
	}
	
	/**
	 * 
	 * @return string
	 */
	public function getCommand(){
		return $this->_comand;
	}
	
	public function getStats() {
		return json_encode($this->running);
	}
	
	/**
	 * Initialize opencart engine and register itself at core controller
	 */
	public static function initOC() {
		if (!defined('DIR_SYSTEM')){
			define('DIR_SYSTEM','/var/www/html/gempackeddev/system/');
			define('DIR_APPLICATION', '/var/www/html/gempackeddev/catalog/');
			define('DIR_MODIFICATION', '/var/www/html/gempackeddev/system/storage/modification/');
		}
		require_once(\VQMod::modCheck(DIR_SYSTEM.'../vqmod' .'/vqmod.php'));
		VQMod::bootup();
		require_once(VQMod::modCheck(DIR_SYSTEM . 'startup.php'));
	}
	
	public function run($file,$class="",$method="",$args=array()) {
		if (empty($this->registry)) {
			//$this->initOC();
		}
		if (!is_file($file)) {
			return;
		}
		$this->_file = $file;
		$this->execAsync($class,$method,extract($args));
		return;
	}
	
	/**
	 * 
	 * @return string|boolean
	 */
	protected function _getPHPExecutableFromPath() {
		$paths = explode(PATH_SEPARATOR, getenv('PATH'));
		if($this->_osWin){
			foreach ($paths as $path) {
				if (strstr($path, 'php')){
					$php_executable =  $path . DIRECTORY_SEPARATOR . 'php.exe';
					if(file_exists($php_executable) && is_file($php_executable)){
						return $php_executable;
					}
				}
			}
		}else{
			foreach ($paths as $path) {
				$php_executable = $path . DIRECTORY_SEPARATOR . "php";
				if (file_exists($php_executable) && is_file($php_executable)) {
					return $php_executable;
				}
			}
			
		}
		return false;
	}
	

}
