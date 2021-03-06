<?php
/**
* Base abstract class for the image resizers
*  
* @author Dean Blackborough <dean@g3d-development.com>
* @copyright G3D Development Limited
* @license https://github.com/deanblackborough/g3d-zf1-lib/blob/master/LICENSE.md
*/
abstract class G3d_Image_Resizer 
{
	protected $width;
	protected $height;
	
	protected $dest_width;
	protected $dest_height;
	
	protected $spacing_x;
	protected $spacing_y;

	protected $src_width;
	protected $src_height;    
	protected $src_file;
	protected $src_path;
	protected $src_aspect_ratio;
	
	protected $canvas;
	protected $copy;
	
	protected $maintain_aspect;
	
	protected $canvas_color = array('r'=>255, 'g'=>255, 'b'=>0);
	protected $quality;
	
	protected $mime;    
	protected $extension;
	
	protected $suffix = '-thumb';
	
	protected $invalid;
	protected $errors  = array();
	
	/**
	* Set options for resizer, only the options are set here to allow simple 
	* batch processing, developer can set initial options and then re-use the 
	* same object by calling loadImage and resize repeatedly
	* 
	* @param integer $width Width for the new image
	* @param integer $height Height for the new image
	* @param integer $quality Quality or compression level for new image, this 
	*                         depends on the desired format, the specific 
	*                         resizer weill document acceptable values
	* @param array $canvas_color Canvas background color, passed in as an rgb 
	*                            array
	* @param boolean $maintain_aspect Maintain aspect ratio of orginal image, 
	*                                 if set to TRUE padding will be added 
	*                                 around a best fit resampled image, 
	*                                 otherwise the image will be stretched to 
	*                                 fit the canvas
	* @return void|Exception Params are validated, an exception will be thrown 
	*                        if params are not valid
	*/
	public function __construct($width, $height, $quality, 
	array $canvas_color=array('r'=>255, 'g'=>255, 'b'=>255), 
	$maintain_aspect=TRUE) 
	{
		if(is_int($width) == FALSE || $width < 1) {            
			$this->invalid++;
			$this->errors[] = 'Width not valid, must be an integer above 0';
		}
		
		if(is_int($height) == FALSE || $height < 1) {
			$this->invalid++;
			$this->errors[] = 'Height not valid, must be an integer above 0';
		}
		
		if($this->colorIndexValid('r', $canvas_color) == FALSE || 
		$this->colorIndexValid('g', $canvas_color) == FALSE || 
		$this->colorIndexValid('b', $canvas_color) == FALSE) {
			$this->invalid++;
			$this->errors[] = 'Canvas color array invalid, must contain three 
			indexes, r, g and b each with values between 0 and 255';
		}
		
		if($this->invalid == 0) { 
			$this->width = $width;
			$this->height = $height;
			$this->quality = $quality;
			$this->canvas_color = $canvas_color;
			if($maintain_aspect == TRUE) {
				$this->maintain_aspect = TRUE;
			} else {
				$this->maintain_aspect = FALSE;
			}
		} else {
			throw new InvalidArgumentException("Error(s) creating resizer: " . 
			implode(' - ', $this->errors));
		}
	}
	
	/**
	* Check to see if the supplied color index is valid
	* 
	* @param string $index
	* @param array Color array to check
	* @return boolean
	*/
	private function colorIndexValid($index, array $canvas_color) 
	{
		if(array_key_exists($index, $canvas_color) == TRUE && 
		$canvas_color[$index] >= 0 && $canvas_color[$index] <= 255) {
			return TRUE;
		} else {
			return FALSE;
		}
	}
	
	/**
	* Load the image
	* 
	* @param string $file File name and extension
	* @param string $path Full patch to image
	* @return void|Exception Throws an exception if the images fails mime type 
	*                        validation or can't be found 
	*/
	public function loadImage($file, $path='') 
	{
		if(file_exists($path . $file) == TRUE) {
			
			$this->file = $file;
			$this->path = $path; 
			
			$this->validateImage();
			
			$this->sourceDimensions();
		} else {
			throw new RuntimeException("File couldn't be found, supplied 
			destination: '" . $path . $file . "'");
		}
	}
		
	/**
	* Validate image is of correct mimetype, if not throw exception, called 
	* by the load image method
	* 
	* @return void|Exception
	*/
	protected function validateImage() 
	{
		$validator = new Zend_Validate_File_IsImage();
		$validator->setMimeType($this->mime);
		
		if($validator->isValid($this->path . $this->file) == FALSE) {
			throw new InvalidArgumentException("Supplied image not correct, 
			mime type invalid, needs to be '" . $this->mime . "'");
		}
	}
	
	/**
	* Fetch the dimensions for the source image and the aspect ratio. Also 
	* checks to ensure that the requested sizes aren't larger than the supplied 
	* image, the resizer does not upscale images
	* 
	* @return void|Exception Writes the source dimesions to src properties, 
	*                        throws an exception if the src dimensions are 
	*                        smaller then the dimensions defined in the 
	*                        constructor
	*/
	protected function sourceDimensions() 
	{
		$dimensions = getimagesize($this->path . $this->file);
		
		$this->src_width = $dimensions[0];
		$this->src_height = $dimensions[1];
		$this->src_aspect_ratio = $this->src_width / $this->src_height;
		
		if($this->width > $this->src_width || 
		$this->height > $this->src_height) {
			throw new InvalidArgumentException("Set resizer width or height 
			are larger then source width or height, the resizer does not 
			upscale images.");
		}
	}
	
	/**
	* Resize, calculate the size for the resized image, the the maintain 
	* aspect ratio value is set to true a best fit size is calculated and then 
	* the required x and y spacing is calculated for when the image is copied 
	* onto the canvas
	* 
	* Although the suffix for the new image can be defined the path cannot be 
	* changed, that is outside the scope of this class, it is down to the 
	* client developer to create directories and then oevrride the save method
	* 
	* @param string $suffix Suffix for newly created image 
	* @return void|Exception Throws an exception if no suffic is supplied
	*/
	public function resize($suffix='-thumb') 
	{
		if(strlen(trim($suffix)) > 0) {
			$this->suffix = trim($suffix);
		} else {
			throw new InvalidArgumentException("Suffix must be defined 
			otherwise newly created image conflit with source image");
		}
		
		if($this->src_aspect_ratio > 1) {
			$this->resizeLandscape();
		} else if($this->src_aspect_ratio == 1) {
			$this->resizeSquare();
		} else {
			$this->resizePortrait();
		}
		
		if($this->maintain_aspect == TRUE) {
			$this->spacingX();
		
			$this->spacingY();
		} else {
			$this->dest_width = $this->width;
			$this->dest_height = $this->height;
		}
		
		$this->create();
	}
	
	/**
	* Source image is a landscapoe based image, assume resizing to requested 
	* width and then modify the values are required
	* 
	* @return void
	*/
	protected function resizeLandscape() 
	{
		// Set width and then calculate height
		$this->dest_width = $this->width;
		$this->dest_height = intval(round(
		$this->dest_width / $this->src_aspect_ratio, 0));
		
		// If height larger than requested, set and calculate new width
		if($this->dest_height > $this->height) {
			$this->dest_height = $this->height;
			$this->dest_width = intval(round(
			$this->dest_height * $this->src_aspect_ratio, 0));
		}
	}
	
	/**
	* Source image is a square, fit as appropriate
	* 
	* @return void
	*/
	protected function resizeSquare() 
	{
		if($this->height == $this->width) {
			// Requesting a sqaure image, set destination sizes, no spacing
			$this->dest_width = $this->width;
			$this->dest_height = $this->height;
		} else if($this->width > $this->height) {
			// Requested landscapoe image, set height as dimension, will need 
			// horizontal spacing
			$this->dest_width = $this->height;
			$this->dest_height = $this->height;            
		} else {
			// Requested portrait image, set width as dimension, will need 
			// vertical spacing
			$this->dest_height = $this->width;
			$this->dest_width = $this->width;
		}
	}
	
	/**
	* Source image is a portrait based image, assume resizing to requested 
	* height and then modify the values are required
	* 
	* @return void
	*/
	protected function resizePortrait() 
	{
		// Set height and then calculate width
		$this->dest_height = $this->height;
		$this->dest_width = intval(round(
		$this->dest_height * $this->src_aspect_ratio, 0));
		
		// If width larger than requested, set and calculate new height
		if($this->dest_width > $this->width) {
			$this->dest_width = $this->width;
			$this->dest_height = intval(round(
			$this->dest_width / $this->src_aspect_ratio, 0));
		}
	}
	
	/**
	* Calculate the x spacing if the width of the resampled image will be 
	* smaller than the width defined for the new thumbnail
	* 
	* @return void
	*/
	protected function spacingX() 
	{
		$this->spacing_x = 0;
		
		if($this->dest_width < $this->width) {
			$width_difference = $this->width - $this->dest_width;
			
			if($width_difference % 2 == 0) {
				$this->spacing_x = $width_difference / 2;
			} else {
				if($width_difference > 1) {
					$this->spacing_x = ($width_difference-1) / 2 + 1;
				} else {
					$this->spacing_x = 1;
				}
			}
		}
	}
	
	/**
	* Calculate the y spacing if the height of the resampled image will be 
	* smaller than the height defined for the new thumbnail
	* 
	* @return void
	*/
	protected function spacingY() 
	{
		$this->spacing_y = 0;
		
		if($this->dest_height < $this->height) {
			
			$height_difference = $this->height - $this->dest_height;
			
			if($height_difference % 2 == 0) {
				$this->spacing_y = $height_difference / 2;
			} else {
				if($height_difference > 1) {
					$this->spacing_y = ($height_difference-1) / 2 + 1;
				} else {
					$this->spacing_y = 1;
				}
			}
		}
	}
	
	/**
	* Destroy the image resources
	* 
	* @return void
	*/
	public function __destruct() 
	{
		if(isset($this->canvas) == TRUE) {
			imagedestroy($this->canvas);
		}
		if(isset($this->copy) == TRUE) {
			imagedestroy($this->copy); 
		}
	}
	
	/**
	* Required process method in child classes, this method creates canvas, 
	* copies and then saves new image
	* 
	* @return void|Exception Throws an exception if there was an error 
	*                        either creating or saving the new image
	*/
	abstract protected function create();
}