<?php
/**
* Generates bootstrap valid HTML for a table
* 
* This is the very basic version of my html table view helper, it doesn't do 
* any error checking or support advanced features
* 
* The advanced version includes error checking to ensure cells match on each 
* row, colspan and rowspan support as well as the ability to define the class 
* for each cell
* 
* @author Dean Blackborough <dean@g3d-development.com>
* @copyright G3D Development Limited
* @license https://github.com/deanblackborough/g3d-zf1-lib/blob/master/LICENSE.md
*/
class G3d_View_BootstrapTable extends Zend_View_Helper_Abstract 
{
	/**
	* Override the hinting to allow code completion for our view helpers
	*
	* @var G3d_View_Codehinting
	*/
	public $view;
	
	private $header;
	private $rows;
	private $caption;
	private $css;
	
	private $classes = array('active', 'success', 'info', 'warning', 
		'danger');
	
	/**
	* Set options
	* 
	* @param string $caption Optional caption for table
	* @return G3d_View_BootstrapTable
	*/
	public function bootstrapTable($caption) 
	{
		$this->resetParams();
		
		$this->caption = trim($caption);
		
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
		$this->caption = '';
		
		$this->header = array();
		$this->rows = array();
		$this->css = array();
	}
	
	/**
	* Generate the final HTML for the table
	* 
	* @return string 
	*/
	private function render() 
	{
		$html = '<table class="table ' . implode(' ', $this->css) . '">';
		
		if(strlen($this->caption) > 0) {
			$html .= '<caption>' . $this->view->escape($this->caption) . 
				'</caption>';
		}
		
		$html .= $this->headerRowHtml();
		$html .= $this->rowsHtml();
		
		$html .= '</table>';
		
		return $html;
	}
	
	/**
	* Generate the html for the header row
	* 
	* @return string
	*/
	private function headerRowHtml() 
	{
		$html = '<thead><tr>';
		
		foreach($this->header as $data) {
			$html .= '<th>' . $this->view->escape($data) . '</th>';
		}
		
		$html .= '</tr></thead>';
		
		return $html;
	}
	
	/**
	* Generate the html for the table rows
	* 
	* @return string
	*/
	private function rowsHtml() 
	{
		$html = '';
		
		foreach($this->rows as $row) {
			
			 $html .= '<tr';
			 
			 if($row['class'] != NULL) {
				 $html .= ' class="' . $row['class'] . '"';
			 }
			 
			 $html .= '>';
			 
			 foreach($row['data'] as $data) {
				$html .= '<td>' . $this->view->escape($data) . '</td>';
			 }
			 
			 $html .= '</tr>';
		}
		
		return $html;
	}
	
	/**
	* Define the data array for the header cells
	* 
	* @param array $header
	* @return G3d_View_BootstrapTable
	*/
	public function header(array $header) 
	{
		$this->header = $header;
		
		return $this;
	}
	
	/**
	* Define the data array for a table row
	* 
	* @param array $row
	* @param string|NULL $class Contextual class for row
	* @return G3d_View_BootstrapTable
	*/
	public function row(array $row, $class=NULL) 
	{
		$this->rows[] = array('data'=>$row, 'class'=>$class);
		
		return $this;
	}
	
	/**
	* Add striped rows to the table to render in a zebra pattern
	* 
	* @return G3d_View_BootstrapTable
	*/
	public function stripedRows() 
	{
		$this->css[] = 'table-striped';
		
		return $this;
	}
	
	/**
	* Add borders to all sides of the table
	* 
	* @return G3d_View_BootstrapTable
	*/
	public function bordered() 
	{
		$this->css[] = 'table-bordered';
		
		return $this;
	}
	
	/**
	* Add a hover effect to each data row
	* 
	* @return G3d_View_BootstrapTable
	*/
	public function hoverRows() 
	{
		$this->css[] = 'table-hover';
		
		return $this;
	}
	
	/**
	* Condensed version of table
	* 
	* @return G3d_View_BootstrapTable
	*/
	public function condensed() 
	{
		$this->css[] = 'table-condensed';
		
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