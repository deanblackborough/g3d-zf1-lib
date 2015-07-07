<?php
/**
* Generates the HTML for a bootstrap nav components, nav bar typed can be 
* defined, tabs, pills or stacked and supports one level of child menu items
* 
* If there are any issues generating the HTML due to incorrect params the 
* view helper will generate a HTML comment containing the error reason
* 
* @author Dean Blackborough <dean@g3d-development.com>
* @copyright G3D Development Limited
* @license https://github.com/deanblackborough/g3d-zf1-lib/blob/master/LICENSE.md
*/
class G3d_View_BootstrapNav extends Zend_View_Helper_Abstract 
{
	/**
	* Override the hinting to allow code completion for our view helpers
	*
	* @var G3d_View_Codehinting
	*/
	public $view;
	
	private $classes = array();
	private $menu_items;
	private $active_url;
	
	private $errors;
	private $render;

	/**
	* Set the options
	* 
	* @param array $menu_items Array of menu items, each item in the array 
	* 	should be an array with three fields, name, url and title. Optionally 
	* 	disabled and child fields can be defined in the mneu array
	* @param string $active_url The active URL, active class will be attached 
	* 	to the corresponding menu item
	* @return G3d_View_BootstrapNav
	*/
	public function bootstrapNav(array $menu_items, $active_url) 
	{
		$this->resetParams();
		
		$this->render = $this->validate($menu_items);

		if($this->render == TRUE) {
			$this->menu_items = $menu_items;
			$this->active_url = $active_url;
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
	public function resetParams() 
	{
		$this->classes = array('nav');
		$this->menu_items = array();
		$this->active_url = '';
		
		$this->errors = array();
		$this->render = FALSE;
	}
	
	/**
	* Validate the submitted params
	* 
	* @param string $menu_items
	* @return boolean
	*/
	private function validate($menu_items) 
	{
		if(count($menu_items) > 0) {
			return TRUE;
		} else {
			$this->errors[] = 'No menu items passed to the view helper';
			
			return FALSE;
		}
	}
	
	/**
	* Render the menu using tabs
	* 
	* @return G3d_View_BootstrapNav
	*/
	public function tabs() 
	{
		$this->classes[] = 'nav-tabs';
		
		return $this;
	}
	
	/**
	* Render the menu using pills
	* 
	* @return G3d_View_BootstrapNav
	*/
	public function pills() 
	{
		$this->classes[] = 'nav-pills';
		
		return $this;
	}
	
	/**
	* Make the menu items equal width
	* 
	* @return G3d_View_BootstrapNav
	*/
	public function justified() 
	{
		$this->classes[] = 'nav-justified';
		
		return $this;
	}
	
	/**
	* Check to see if the required fields exists for the current menu item, 
	* if validate fails an error message will be added
	* 
	* @param array $item Current menu item
	* @param integer $k Index from base array
	* @return boolean FALSE if the keys are invalid
	*/
	private function validateMenuItemFields($item, $k) 
	{
		if(isset($item['name']) == TRUE && 
			isset($item['title']) == TRUE && 
			isset($item['url']) == TRUE) {
				
			return TRUE;
		} else {
			$this->errors[] = 'The required fields are not validate for 
				item ' . ($k+1) . ' in your menu array, require, name, title 
				and url';
			
			return FALSE;
		}
	}

	/**
	* Generate the html
	* 
	* @return string 
	*/
	private function render() 
	{
		if($this->render == TRUE) {
			
			$html = '<ul class="' . implode(' ', $this->classes)  . '">';

			foreach($this->menu_items as $k=>$item) {
				
				if($this->validateMenuItemFields($item, $k) == TRUE) {

					$class = NULL;
					
					if($item['url'] == $this->active_url) { 
						$class .= 'active ';
					}
					
					if(isset($item['disabled']) == TRUE) {
						$class .= 'disabled ';
					}
					
					$html .= '<li role="presentaion"'; 
					if($class != NULL) {
						$html .= ' class="' . trim($class) . '"';
					}
					$html .= '>';
					$html .= '<a href="' . $this->view->escape($item['url']) . 
						'" title="' . $this->view->escape($item['title']) . 
						'">' . $this->view->escape($item['name']) . '</a>';
						
					$html .= '</li>';					
				}
			}

			$html .= '</ul>';
			
			// Check to see if any key errors have been added
			if(count($this->errors) > 0) {
				$html = $this->errors();
			}
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
		$html = '<!-- Bootstrap nav view helper';
		
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