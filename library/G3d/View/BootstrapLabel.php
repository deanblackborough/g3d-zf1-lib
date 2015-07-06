<?php
/**
* Generates the bootstrap label html. The label text and modifier class 
* (primary, default...) can be defined
* 
* If there are any issues generating the HTML due to incorrect params the 
* view helper will generate a HTML comment containing the error reason
* 
* @author Dean Blackborough <dean@g3d-development.com>
* @copyright G3D Development Limited
* @license https://github.com/deanblackborough/g3d-zf1-lib/blob/master/LICENSE.md
*/
class G3d_View_BootstrapLabel extends Zend_View_Helper_Abstract 
{
	/**
	* Override the hinting to allow code completion for our view helpers
	*
	* @var G3d_View_Codehinting
	*/
	public $view;
	
	private $modifier_class; 
	private $label_text;	
	
	private $errors;
	private $render;
	
	private $modifier_classes = array('default', 'primary', 'success', 
		'info', 'warning', 'danger');
	
	/**
	* Set options
	* 
	* @param string $label_text Text for the label
	* @param string $modifier_class Bootstrap modifier class, defaults to 
	* 	'default'
	* @return G3d_View_BootstrapLabel
	*/
	public function bootstrapLabel($label_text, $modifier_class='default') 
	{
		$this->resetParams();
		
		$this->render = $this->validate($modifier_class);
		
		if($this->render == TRUE) {
			$this->label_text = $label_text;
			$this->modifier_class = $modifier_class;
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
		$this->label_text = '';
		$this->modifier_class = 'default';
		$this->errors = array();
		$this->render = FALSE;
	}
	
	/**
	* Validate the submitted params
	* 
	* @param string $modifier_class
	* @return boolean
	*/
	private function validate($modifier_class) 
	{
		if(in_array($modifier_class, $this->modifier_classes) == TRUE) {
			return TRUE;
		} else {
			$this->errors[] = 'Supplied modifier class (' . $modifier_class . 
				') invalid, needs to be one of the following (' . 
				implode(', ', $this->modifier_classes) . ')';			
			
			return FALSE;
		}
	}
	
	/**
	* Generate the label HTML
	* 
	* @return string 
	*/
	private function render() 
	{
		if($this->render == TRUE) {
			$html = '<span class="label label-' . 
				$this->view->escape($this->modifier_class) . '">' . 
				$this->view->escape($this->label_text) . '</span>';
		} else {
			$html = $this->errors();
		}
				
		return $html;
	}
	
	/**
	* Generate the error string
	* 
	* @return string
	*/
	private function errors() 
	{
		$html = '<!-- Bootstrap label view helper';
		
		foreach($this->errors as $error) {
			$html .= ' : '  . $this->view->escape($error);
		}
		
		$html .= ' -->';
		
		return $html;
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