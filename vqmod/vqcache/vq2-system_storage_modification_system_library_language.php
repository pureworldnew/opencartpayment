<?php
class Language {
	private $default = 'english';
	private $directory;

            private $path = DIR_LANGUAGE;
	private $data = array();

	public function __construct($directory = '') {
		$this->directory = $directory;
	}


	public function setPath($path) {
		if (!is_dir($path)) {
			trigger_error('Error: check path exists: '.$path);
			exit;
		}

		$this->path = $path;
	}
	
	public function get($key) {
		return (isset($this->data[$key]) ? $this->data[$key] : $key);
	}

	public function all() {
		return $this->data;
	}
	
	public function load($filename) {
		$_ = array();

		$file = $this->path . $this->default . '/' . $filename . '.php';

		if (file_exists($file)) {
			require(\VQMod::modCheck(modification($file), $file));
		}

		$file = $this->path . $this->directory . '/' . $filename . '.php';

		if (file_exists($file)) {
			require(\VQMod::modCheck(modification($file), $file));
		}

		$this->data = array_merge($this->data, $_);

		return $this->data;
	}
}