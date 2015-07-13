<?php
/**
* Generates the HTML for the bootstrap progress bar compnent
* 
* If there are any issues generating the HTML due to incorrect params the 
* view helper will generate a HTML comment containing the error reason
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
	
	private $render;
	private $errors;
	
	private $progress;
	private $show_label;
	
	private $modifier_class;
	private $modifier_classes = array('success', 'info', 'warning', 'danger');
		
	/**
	* Set options
	* 
	* @param integer $progress Set the progress percentage, a value between 0 
	* 	and 100
	* @return G3d_View_BootstrapProgressBar
	*/
	public function bootstrapProgressBar($progress) 
	{
		$this->resetParams();
		
		$this->render = $this->validate($progress);
		
		if($this->render == TRUE) {
			$this->progress = $progress;
		}
		
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
		$this->show_label = FALSE;
		$this->modifier_class = NULL;
		
		$this->errors = array();
		$this->render = FALSE;
	}
	
	/**
	* Validate the submitted options
	* 
	* @param integer $progress
	* @return boolean
	*/
	private function validate($progress) 
	{
		if($progress >= 0 && $progress <= 100) {
			return TRUE;
		} else {
			$this->errors[] = 'Progress percentage must be a value between 
				0 and 100, progress value submitted ' . $progress;
				
			return FALSE;
		}
	}
	
	/**
	* Generate the error string
	* 
	* @return string
	*/
	private function errors() 
	{
		$html = '<!-- Bootstrap progress bar view helper';
		
		foreach($this->errors as $error) {
			$html .= ' : '  . $this->view->escape($error);
		}
		
		$html .= ' -->';
		
		return $html;
	}
	
	/**
	* Generate the HTML for the progress bar based on all defined options 
	* 
	* @return string 
	*/
	private function render() 
	{
		if($this->render == TRUE) {
			$html = '<div class="progress"><div class="progress-bar'; 
			
			if($this->modifier_class != NULL) {
				$html .= ' progress-bar-' . $this->modifier_class;
			}
			
			$html .= '" role="progressbar" aria-valuenow="' . $this->progress . 
				'" aria-valuemin="0" aria-valuemax="100" style="width: ' . 
				$this->progress . '%;">';
					
			if($this->show_label == FALSE) {
				$html .= '<span class="sr-only">' . $this->progress . 
					'% Complete</span>';
			} else {
				$html .= $this->progress . '%';
			}
			
			$html .= '</div></div>';
		} else {
			$html = $this->errors();
		}
		
		return $html;
	}
	
	/**
	* Render the progress bar using one of Bootstraps modifier classes, 
	* valid options are success, info, warning and danger
	* 
	* @param string $modifier_class
	* @return G3d_View_BootstrapProgressBar
	*/
	public function modifierClass($modifier_class) 
	{
		if(in_array($modifier_class, $this->modifier_classes) == TRUE) {
			
			$this->modifier_class = $modifier_class;

		} else {
			$this->errors[] = 'Supplied modifier class (' . $modifier_class . 
				') invalid, needs to be one of the following (' . 
				implode(', ', $this->modifier_classes) . ')';			
			
			$this->render = FALSE;
		}
		
		return $this;
	}
	
	/**
	* Show the progress percentage within the progress bar
	* 
	* @return G3d_View_BootstrapProgressBar
	*/
	public function showLabel() 
	{
		$this->show_label = TRUE;
		
		return $this;
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