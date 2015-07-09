<?php
/**
* Crops the source image based on the selection area and position, all params 
* will be validated including ensuring that the crop selection fits within the 
* source image
* 
* @see G3d_Image_Crop
* 
* @author Dean Blackborough <dean@g3d-development.com>
* @copyright G3D Development Limited
* @license https://github.com/deanblackborough/g3d-zf1-lib/blob/master/LICENSE.md
*/
class G3d_Image_Crop_Jpeg extends G3d_Image_Crop 
{
	protected $mime = 'image/jpeg';
	protected $extension = '.jpg';
	
	/**
	* Set the crop options, set here to allow batch processing by repeatedly 
	* calling the loadImage and crop methods
	* 
	* @param integer $x_position X position of crop selection rectangle
	* @param integer $y_position Y position of crop selection rectangle
	* @param integer $width Width of crop selection rectangle
	* @param integer $height Height of crop selected rectangle
	* @param integer $quality Quality or compression level for new image, must 
	*                         be a value between 0 and 100
	* @return void|Exception
	*/
	public function __construct($x_position, $y_position, $width, $height, 
	$quality) 
	{
		$this->invalid = 0;
		
		if(is_int($quality) == FALSE || $quality < 1 || $quality > 100) {
			$this->invalid++;
			$this->errors[] = 'Quality must be an integer value between 1 
			and 100';
		}
		
		parent::__construct($x_position, $y_position, $width, $height, 
		$quality);
	}
	
	/**
	* Crop the image base the supplied params
	* 
	* @return void|Exception
	*/
	protected function create() 
	{
		$this->src_image = imagecreatefromjpeg($this->path . $this->file);
		
		$this->cropImage();
	}
	
	/**
	* Attempt to save the new image, Overriding  this method to change 
	* destination 
	* 
	* @return boolean
	*/
	protected function save() 
	{
		return imagejpeg($this->cropped_image, $this->path . 
		str_replace($this->extension, $this->suffix . $this->extension, 
		$this->file), $this->quality);
	}
}