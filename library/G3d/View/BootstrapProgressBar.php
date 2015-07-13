<?php
/**
* Generates the HTML for the bootstrap progress bar compnent
* 
* @author Dean Blackborough <dean@g3d-development.com>
* @copyright G3D Development Limited
* @license https://github.com/deanblackborough/g3d-zf1-lib/blob/master/LICENSE.md
*/
class G3d_View_BootstrapProgressBar extends Zend_View_Helper_Abstract 
{
	/**
	* Override the hinting to allow code completion for our view helpers
	*
	* @var G3d_View_Codehinting
	*/
	public $view;
	
	private $progress;
		
	/**
	* Set options
	* 
	* @param integer $progress Set the progress percentage, a value between 0 
	* 	and 100
	* @return G3d_View_BootstrapBadge
	*/
	public function bootstrapProgressBar($progress) 
	{
		$this->resetParams();
		
		$this->progress = intval($progress);
		
		return $this;
	}
	
	/**
	* Reset any internal params, interal properties need to be reset so that 
	* if the view helper is called within the view script each request is 
	* unique
	* 
	* @return void
	*/
	private function resetParams() 
	{
		$this->progress = 0;
	}
	
	/**
	* Generate the HTML for the progress bar based on all defined options 
	* 
	* @return string 
	*/
	private function render() 
	{
		return '
		<div class="progress">
			<div class="progress-bar" role="progressbar" aria-valuenow="' . 
				$this->progress . '" aria-valuemin="0" aria-valuemax="100" 
				style="width: ' . $this->progress . '%;">
				<span class="sr-only">' . $this->progress . '% Complete</span>
			</div>
		</div>';
	}
	
	/**
	* Define __toString to allow the result to be returned when echo 
	* and print are called on the object, simply calls the private render 
	* method
	* 
	* @return string The generated html
	*/
	public function __toString() 
	{
		return $this->render();
	}
}