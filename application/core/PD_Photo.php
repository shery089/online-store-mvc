<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class PD_Photo extends CI_Controller {

	public function __construct()
	{
		parent::__construct();	
	}

    /**
     * Saves an image
     * @param $photo
     * @param $upload_path
     * @param $name
     * @return string
     */
	public function save_photo($photo, $upload_path, $name)
	{
    	if(!empty($photo))
    	{
	    	$parts = pathinfo($photo);
	    	$extension	= strtolower($parts['extension']);

	    	$new_name = IMAGE_PREPEND . time() . '_' . date("j_n_Y") . '.' . $extension;
	    	
	    	$config['upload_path'] = $upload_path;		        
	        
	        $config['allowed_types'] = 'jpg|jpeg|png';

	        $config['file_name'] = $new_name;
     		
     		$config['max_size'] = 15000000;

			$this->upload->initialize($config);

            if ($this->upload->do_upload($name))
            {
            	$this->upload->data();
            	return $new_name;
            }
    	}
	}

    /**
     * Deletes an image
     * @param $image_directory
     * @param $data
     */
	public function delete_picture($image_directory, $data)
	{
        $image = $data['image'];
        $profile_image = $data['profile_image'];
        $thumbnail = $data['thumbnail'];

		if($image != 'no_image_600.png')
		{
			unlink($image_directory . DS . $image);
		}

		if($profile_image != 'no_image_600_profile_image.png')
		{
			unlink($image_directory . DS . $profile_image);
		}

		if($thumbnail != 'no_image_600_thumb.png')
		{
			unlink($image_directory . DS . $thumbnail);
		}
	}

    /**
     * Makes new compressed images
     * @param $img
     * @param $dest
     * @param $desired_width
     */
	public function make_thumb($img, $dest, $desired_width)
	{
		$path_parts = pathinfo($img);
		$extension = $path_parts['extension'];

		if(strtolower($extension) == 'jpg' || strtolower($extension) == 'jpeg')
		{
			/* read the source image */
			$img = imagecreatefromjpeg($img);
		}
		if(strtolower($extension) == 'png')
		{
			/* read the source image */
			$img = imagecreatefrompng($img);
		}
		
		$width = imagesx($img);
		$height = imagesy($img);

		/* find the "desired height" of this thumbnail, relative to the desired width  */
		$desired_height = floor($height * ($desired_width / $width));

		/* create a new, "virtual" image */
		$virtual_image = imagecreatetruecolor($desired_width, $desired_height);

		/* copy source image at a resized size */
		imagecopyresampled($virtual_image, $img, 0, 0, 0, 0, $desired_width, $desired_height, $width, $height);

		/* create the physical thumbnail image to its destination */

		if($extension == 'jpg' || $extension == 'jpeg')
		{
			imagejpeg($virtual_image, $dest);
		}
		if($extension == 'png')
		{
			imagepng($virtual_image, $dest);
		}
	}
}