<?php

namespace session\model;

/**
 * View class.
 */
class View
{
	// Variables assigned to template.
	private $data = array();

	// Render status of view.
	private $render = false;

	/**
	 * Constructor.
	 *
	 * @param string $template   Name of the template to load.
	 */
	public function __construct($template) {
		$file = dirname(__DIR__) . '/view/' . $template . '.php';
		if (file_exists($file)) {
			$this->render = $file;
		}
	}

	/**
	 * Receives assignments from controller and stores in local data array.
	 *
	 * @param string $variable   The name of the variable.
	 * @param mixed $value       The value of the variable.
	 */
	public function assign($variable, $value) {
		$this->data[$variable] = $value;
	}

	/**
	 * Destructor.
	 */
	public function __destruct() {
		// Parse data variables into local variables, so that they render to the view.
		$data = $this->data;
		// Render view.
		include $this->render;
	}
}
