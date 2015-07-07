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
	* Simple pagination view helper, generates next and previous links as well 
	* as the text between the links. There are two formats for the text between 
	* the links, either item based or page based. If you are using the item 
	* setting all the text can be changed from the defaults.
	* 
	* @param integer $per_page The number of results per page
	* @param integer $start The start result for the current page
	* @param integer $total The total number of results in the entire resultset
	* @param string $url URL to use for pagination links
	* @param integer $text_style Text style for the text that appears between 
	*                            the next and previous links, text can either 
	*                            be page based or item based, use the constants 
	*                            to set the style
	* @return G3d_View_Pagination 
	*/
	public function pagination($per_page, $start, $total, $url, 
	$text_style=G3d_View_Pagination::STYLE_PAGES) { } 
	
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
}