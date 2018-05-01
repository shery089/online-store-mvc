<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class PD_Photo extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://localhost/pak_dempcrates/photo
	 *	- or -
	 *		http://localhost/pak_dempcrates/index.php/home/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://localhost/pak_dempcrates/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/home/<method_name>
	 */	

	public function __construct()
	{
		parent::__construct();	
	}

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
     		
     		$config['max_size'] = 100000000;

			$this->upload->initialize($config);

            // $this->load->library('upload', $config);

            if ($this->upload->do_upload($name))
            {
            	$this->upload->data();
            	return $new_name;
            }
    	}
	}	


	public function delete_picture($image_directory, $data)
	{
		if(!is_numeric(current($data)))
		{
			$data = current($data);
			$image = custom_echo($data, 'image', 'no_case_change');
			$profile_image = custom_echo($data, 'profile_image', 'no_case_change');
			$thumbnail = custom_echo($data, 'thumbnail', 'no_case_change');
		}
		else {
			$image = $data['image'];
			$profile_image = $data['profile_image'];
			$thumbnail = $data['thumbnail'];
		}

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

	/*

	public function delete_picture($id, $model, $method, $image_directory, $db_field)
	{
		$model = explode('/', $model);
		$model = $model[1];

		$result = $this->$model->$method($id, TRUE); // TRUE to get full record including picture

		$image = $model == 'user_model' ? $result[0][$db_field]: $result[$db_field];

		if($image != 'no_image_600.png')
		{
			unlink($image_directory . DS . $image);
		}

		$allowed_models = array('category_model', 'user_model');

		if(in_array($model, $allowed_models))
		{
			$image_thumb = $model == 'user_model' ? $result[0]['thumbnail']: $result['thumbnail'];

			if($model !== 'user_model')
			{
				$profile_image = $model == 'user_model' ? $result[0][$db_field]: $result['profile_image'];
			}

			if($image_thumb != 'no_image_600_thumb.png')
			{
				unlink($image_directory . DS . $image_thumb);
			}

			if($model !== 'user_model')
			{
				if($profile_image != 'no_image_600_profile_image.png')
				{
					unlink($image_directory . DS . $profile_image);
				}
			}

		}
	}*/

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