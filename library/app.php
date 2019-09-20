<?php
Namespace App;

if (! defined('APP_DIR'))  die("APP_DIR not defined!");
if (! defined('LIB_DIR')) die("LIB_DIR not defined!");

require_once(dirname(LIB_DIR) . DIRECTORY_SEPARATOR . "vendor/autoload.php");
include_once("class_loader.php");
include_once("global.php");
include_once("setting.php");
// include_once("base.php");

Use App\base;

class app extends base
{
	/** Singleton Instance */
	static protected $instance = null;

	/** The path name that points to the library files */
	protected $libPath = '';


	protected function __construct() 
	{
		$dirname = dirname(__FILE__);
		$this->setLibPath(LIB_DIR);
		$includePath = ini_get('include_path');
		ini_set('include_path', '.:' . $dirname . ':' . $dirname . '/pear' . ':' . $includePath);
	}

	/** Load the required file based on the class name provided
     *  To be called by PHP built-in __autoload()
     */
	/** 
     * @param string $className The name of the class
     *
     * @access public
     * @return void
     */
	function autoload($className) 
	{
		$filename = '';
		$libPath = $this->getLibPath();
// echo "Reached autoload function, Class name: " . $className . "<br />";

		if (preg_match('/PHPExcel([a-zA-Z_]+)/', $className, $regs)) {
			$filename = 'adaptor/PHPExcel/' . str_replace('_', '/', $className) . '.php';
		} else {
			$className = strtolower($className);			
			if ($className == 'adorecordset_ext_empty') return;
			if (strpos($className, 'config') !== false) {
				$filename = 'config/' . $className . '.php';
			} elseif (strpos($className, 'store') !== false) {
				$filename = 'datastore/' . $className . '.php';
			} elseif (preg_match('/mx([a-z0-9]+)/', $className, $regs)) {
				$filename = 'objects/' . $regs[1] . '.inc.php';

				if (! file_exists($libPath . $filename)) {
// echo "Reached object folder with no filepath: " . $filename . "<br />";
					if (strpos($filename, 'parameter')) {
						$filename = 'gatewayParameter' . '.php';
					} else {
						$filename = $regs[1] . '.php';
					}
				}
			} elseif (strpos($className, 'worker') !== false) {
				$filename = 'worker/' . $className . '.php';
			} else {
				// $libPath = dirname(__DIR__ ) . DIRECTORY_SEPARATOR;
				$filename = $className . '.php';
			}

// echo "Reached object folder: " . $filename . "<br />";
		}
// echo "Reached libPath: " . $libPath . "<br />";
// echo "Reached object folder with parameter filepath: " . $libPath . $filename . "<br />";

		if (! empty($filename) && file_exists($libPath . $filename)) {
			// $this->log('__autoload('.$className.'): Loading file '.$libPath.$filename.'...', MX_LOG_DEBUG);
// echo "Including file... " . $libPath . $filename . "<br /><br />";
			require_once($libPath . $filename);
			return true;
		} else {
			$this->log('[' . get_class($this) . ' - ' . __FUNCTION__ . '] ' . "Unable to include : " . $libPath . $filename . "(file does not exist) for class " . $className, M_LOG_WARNING);
			throw new Exception('[' . get_class($this) . ' - ' . __FUNCTION__ . '] ' . "Unable to include : " . $libPath . $filename . "(file does not exist) for class " . $className);
		}

		return false;
	}

	/** Set the path that points to the MX library */
    /** 
     * @param string $path Path name that points to the MX library
     *
     * @access protected
     * @return void
     */
	protected function setLibPath($path) 
	{
		$this->libPath = $path;
		if (! preg_match('/\/$/', $this->libPath)) $this->libPath .= '/';
	}

    /** Get the path that points to the MX library */
    /**
     * @access public
     * @return string Path name that points to the MX library
     */
	function getLibPath() 
	{
		return $this->libPath;
	}

	/** Check existence of an instance of class */
	/**
	 * @access public
 	 * @static
 	 */
	static function getInstance() 
	{
		if (self::$instance === null) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	public function __destruct() 
	{
		//session_write_close();
	}
}
?>