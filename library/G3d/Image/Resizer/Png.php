<?php
/**
* Png image resizer
* 
* @see G3d_Image_Resizer
* 
* @author Dean Blackborough
* @copyright G3D Development Limited
*/
class G3d_Image_Resizer_Png extends G3d_Image_Resizer 
{
    protected $mime = 'image/png';
    protected $extension = '.png';
    
    /**
    * Set options for resizer, only the options are set here to allow simple 
    * batch processing, developer can set initial options and then re-use the 
    * same object by calling loadImage and resize repeatedly
    * 
    * @param integer $width Canvas width
    * @param integer $height Canvas height
    * @param integer $quality Quality or compression level for new image
    * @param array $canvas_color Canvas background color
    * @param boolean $maintain_aspect Maintain aspect ratio of image, if set 
    *                                 to TRUE padding is added around best fit 
    *                                 resampled image otherwise image is 
    *                                 stretched to fit
    * @return void|Exception
    */
    public function __construct($width, $height, $quality, 
    array $canvas_color=array('r'=>255, 'g'=>255, 'b'=>255), 
    $maintain_aspect=TRUE) 
    {
        $this->invalid = 0;
        
        if(is_int($quality) == FALSE || $quality < 0 || $quality > 9) {
            $this->invalid++;
            $this->errors[] = 'Quality must be an integer value between 0 and 
            9, 0 being no compression';
        }
        
        parent::__construct($width, $height, $quality, $canvas_color, 
        $maintain_aspect);
    }
    
    /**
    * Create canvas, copy image onto canvas and then save the image
    * 
    * @return void|Exception
    */
    protected function create() 
    {
        $this->canvas = imagecreatetruecolor($this->width, $this->height);
        
        $fill_color = imagecolorallocate($this->canvas, 
        $this->canvas_color['r'], $this->canvas_color['g'], 
        $this->canvas_color['b']);
        imagefill($this->canvas, 0, 0, $fill_color);
        
        $this->copy = imagecreatefrompng($this->path . $this->file);
        
        $result = imagecopyresampled($this->canvas, $this->copy, 
        $this->spacing_x, $this->spacing_y, 0 ,0, $this->dest_width, 
        $this->dest_height, $this->src_width, $this->src_height);
        
        if($result == TRUE) {
            $result = $this->save();
            
            if($result == FALSE) {
                throw new RuntimeException("Unable to save new image");
            }
        } else {
            throw new RuntimeException("Unable to resample the image.");
        }
    }
    
    /**
    * Attempt to save the new image, Overriding  this method to change 
    * destination 
    * 
    * @return boolean
    */
    protected function save() 
    {
        return imagepng($this->canvas, $this->path . 
        str_replace($this->extension, $this->suffix . $this->extension, 
        $this->file), $this->quality);
    }
}