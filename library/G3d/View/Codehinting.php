<?php
/**
* PHPed can't provide code insight for view helpers, they are dynamically 
* invoked. In the layout and view scripts add a phpDov to this file and 
* you will then get code insight for any view helper that you require, you can 
* add defintions for any Zend view helpers that you may use, I've added a 
* couple of the common ones
*
* @author Dean Blackborough <dean@g3d-development.com>
* @copyright G3D Development Limited
*/
class G3d_View_Codehinting extends Zend_View_Helper_Abstract
{
	/**
	* Returns site's base url, or file with base url prepended
	*
	* $file is appended to the base url for simplicity
	*
	* @param  string|null $file
	* @return string
	*/
	public function baseUrl($file = null) { }

	/**
	* Set or retrieve doctype
	*
	* @param  string $doctype
	* @return Zend_View_Helper_Doctype
	*/
	public function doctype($doctype = null) { }

	/**
	* Encode data as JSON, disable layouts, and set response header
	*
	* If $keepLayouts is true, does not disable layouts.
	*
	* @param  mixed $data
	* @param  bool $keepLayouts
	* NOTE:   if boolean, establish $keepLayouts to true|false
	*         if array, admit params for Zend_Json::encode as enableJsonExprFinder=>true|false
	*         this array can contains a 'keepLayout'=>true|false
	*         that will not be passed to Zend_Json::encode method but will be used here
	* @return string|void
	*/
	public function json($data, $keepLayouts = false) { }

	/**
	* Escape var to protect from XSS
	*
	* @param string $string String to escape
	* @return string
	*/
	public function escape($string) { } 
	
	/**
	* Set options
	* 
	* @param string $label_text Text for the label
	* @param string $modifier_class Bootstrap modifier class, defaults to 
	* 	'default'
	* @return G3d_View_BootstrapLabel
	*/
	public function bootstrapLabel($label_text, $modifier_class='default') { } 
	
	/**
	* Set options
	* 
	* @param string $icon The name of the glyphicon
	* @return G3d_View_BootstrapGlyphicon
	*/
	public function bootstrapGlyphicon($icon) { } 
	
	/**
	* Set the options
	* 
	* @param array $menu_items Array of menu items, each item in the array 
	* 	should be an array with three fields, name, url and title
	* @param string $active_url The active URL, active class will be attached 
	* 	to the corresponding menu item
	* @return G3d_View_BootstrapNav
	*/
	public function bootstrapNav(array $menu_items, $active_url) { } 
	
	/**
	* Set the base options
	* 
	* @param integer $per_page The number of records to display per page
	* @param integer $start The start record for the pager
	* @param integer $total The total number of records in the entire recordset
	* @param string $url Base url to use for pager links, typiocally the url 
	* 	for the current page
	* 
	* @return G3d_View_SimplePager
	*/
	public function simplePager($per_page, $start, $total, $url) { }
}