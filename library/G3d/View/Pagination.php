<?php
/**
* Simple pagination view helper, generates next and previous links as well as 
* the text between the next and previous links.
* 
* There are two formats for the text, result based or page based, in both cases 
* all the text for the links can be defined
* 
* The generated html will be a ul/li with a pagination class added to the ul
* 
* @author Dean Blackborough <dean@g3d-development.com>
* @copyright G3D Development Limited
*/
class G3d_View_Pagination extends Zend_View_Helper_Abstract 
{
    CONST STYLE_PAGES = 1;
    CONST STYLE_ITEMS = 2;
    
    private $text_styles = array(G3d_View_Pagination::STYLE_PAGES, 
    G3d_View_Pagination::STYLE_ITEMS);
    private $text_style;
    
    private $per_page;
    private $start;
    private $total;
    private $url;
    
    private $previous;
    private $next;
    
    private $item;
    private $items;
    
    private $valid = TRUE;
    
    /**
    * Simple pagination view helper, generates next and previous links as well 
    * as the text between the links. There are two formats for the text between 
    * the links, either item based or page based. If you are using the item 
    * setting all the text can be changed from the defaults.
    * 
    * Four of the required params (not url), are checked for validioty, rather 
    * than throwing an exception which doesn't fit this helper will silently 
    * fail if the params are not of the correct type and generate a html 
    * comment, I'd suggest you modify the render() method to output your 
    * desired error
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
    $text_style=G3d_View_Pagination::STYLE_PAGES) 
    {
        $this->resetParams();
        
        $this->validate($per_page, $start, $total, $text_style);
        
        $this->url = $this->view->escape($url);
                
        return $this;
    }
    
    /**
    * Validate the supplied params, if any are invalidate the view helper will 
    * silently fail
    * 
    * @param integer $per_page
    * @param integer $start
    * @param integer $total
    * @param integer $text_style
    * @return void
    */
    private function validate($per_page, $start, $total, $text_style) 
    {
        if(is_int($per_page) == FALSE || is_int($start) == FALSE || 
        is_int($total) == FALSE || 
        in_array($text_style, $this->text_styles) == FALSE) {
            $this->valid = FALSE;
        } else {
            $this->per_page = intval($per_page);
            $this->start = intval($start);
            $this->total = intval($total);
            $this->text_style = $text_style;
        }
    }
    
    /**
    * Set new text for the next and previous links, defaults to 'Next' and 
    * 'Previous'
    * 
    * @param string $next Text for the next page link
    * @param string $preview Text for the previous page link
    * @return G3d_View_Pagination
    */
    public function setLinksText($next='Next', $previous='Previous') 
    {
        $this->next = $this->view->escape($next);
        $this->previous = $this->view->escape($previous);
        
        return $this;
    }
    
    /**
    * Set the text to use if the user has decided to use the items based text 
    * version, defaults to 'item' and 'items'
    * 
    * @param string $item Item text to use for 'Item n of m' string when only a 
    *                     single items
    * @param string $items Items text to use for 'Items n-m of o' when there 
    *                      are multiple results
    * @return G3d_View_Pagination
    */
    public function setItemsText($item='item', $items='items') 
    {
        $this->item = $this->view->escape($item);
        $this->items = $this->view->escape($items);
        
        return $this;
    }
    
    /**
    * Reset any internal params, need to reset the params in case the view 
    * helper is called multiple times within the same view.
    * 
    * @return void
    */
    public function resetParams() 
    {
        $this->per_page = 0;
        $this->start = 0;
        $this->total = 0;
        $this->url = NULL;
        $this->text_style = 1;
        
        $this->next = 'Next';
        $this->previous = 'Previous';
        
        $this->item = 'item';
        $this->items = 'items';
    }
    
    /**
    * Generate the pagination html
    * 
    * @return string 
    */
    private function render() 
    {
        if($this->total > 0) {        
            $html = '<ul class="pagination">' . PHP_EOL;
            
            $html .= $this->previousPage();            
            $html .= $this->itemsText();            
            $html .= $this->nextPage();
            
            $html .= '</ul>' . PHP_EOL;
        
            return $html;        
        } else {
            return '';
        }
        
        return $html;
    }
    
    /**
    * Generate the html for the previous page link
    * 
    * @return string
    */
    private function previousPage() 
    {
        $html = '';
        
        if($this->start > 0 && ($this->start < $this->total)) { 
            if($this->start > $this->per_page) {
                $html .= '<li>';
                $html .= '<a href="' . $this->url . '/start/';
                $html .= ($this->start - $this->per_page) . '">';
                $html .= $this->previous  .'</a></li>' . PHP_EOL;
            } else {
                $html .= '<li>';
                $html .= '<a href="' . $this->url . '">';
                $html .= $this->previous . '</a></li>' . PHP_EOL;
            }
        }
        
        return $html;
    }
    
    /**
    * Generate the html for the next page link
    * 
    * @return string
    */
    private function nextPage() 
    {
        $html = '';
        
        if($this->total > ($this->start + $this->per_page)) {
            $html .= '<li>'; 
            $html .= '<a href="' . $this->url . '/start/'; 
            $html .= ($this->start + $this->per_page) . '">';
            $html .= $this->next . '</a></li>' . PHP_EOL;
        }
        
        return $html;
    }
    
    /**
    * Generate the html for the current page text, shows which items have 
    * been selected and if more than one page how many items there are in total
    * 
    * @return string
    */
    private function itemsText() 
    {
        $html = '';
        
        if($this->text_style == G3d_View_Pagination::STYLE_ITEMS) {        
            if($this->total > 1) {
                $first = $this->start + 1;
                $of_text = '';
                
                if($this->total > $this->start + $this->per_page) {
                    $last = $this->start + $this->per_page;
                    $of_text = ' of ' . $this->total;
                } else {
                    $last = $this->total;
                }
                            
                $html .= '<li><strong>' . $this->items . ' ' . $first; 
                $html .= ' - ' . $last . $of_text . '</strong></li>' . PHP_EOL;
            } else {
                $html .= '<li><strong>' . $this->item . ' 1 of 1</strong></li>' . 
                PHP_EOL;
            }
        } else {
            if($this->total > $this->per_page) {
                $pages = ceil($this->total/$this->per_page);
                $page = ceil($this->start/$this->per_page)+1;
                
                $html .= '<li><strong>Page ' . $page. ' of ' . $pages . 
                '</strong></li>' . PHP_EOL;
            } else {
                $html .= '<li><strong>Page 1</strong></li>' . PHP_EOL;
            }
        }
        
        return $html;
    }
    
    /**
    * The view helpers can be output directly, no need to call and return the 
    * render method, we define the __toString method so that echo and print 
    * calls on the object return the html generated by the render method
    * 
    * @return string Generated html
    */
    public function __toString() 
    {
        if($this->valid == TRUE) {
            return $this->render();
        } else {
            return '<!-- Error rendering pagination html -->';
        }
    }
} 