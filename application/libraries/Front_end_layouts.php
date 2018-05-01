<?php 

class Front_end_layouts
{

  // hold codeigniter instance
	private $CI;

	// hold layout title
	private $layout_title = NULL;

	// hold description
	private $layout_description = NULL;

	// hold includes like css and js files
	private $includes = array();

	private $include_content = array();

	public function __construct()
	{
		$this->CI =& get_instance();
	}

	// set layout title
	public function set_title($title)
	{
		$this->layout_title = $title;
	}

	// set layout description
	public function set_description($description)
	{
		$this->layout_description = $description;
	}

	// add includes like css and js
	public function add_include($path, $prepend_base_url = true)
	{
		if($prepend_base_url)
		{
			$this->includes[] = $path;
			// $this->includes[] = base_url() . $path;
		}
		else
		{
			$this->includes[] = $path;
		}

		return $this;
	}

	// add includes like css and js
	public function add_content($content)
	{
		$this->include_content[] = $content;
		return $this;
	}

	// print the includes
	public function print_css_includes()
	{
		$final_includes = '';

		foreach($this->includes as $include)
		{
			if (preg_match('/css$/', $include))
			{
				$final_includes .= '<link href="' . $include . '" rel="stylesheet"/>' . "\n\r";
			}
		}

		return $final_includes;
	}

	// print js includes
	public function print_js_includes()
	{
		$final_includes = '';

		foreach($this->includes as $include)
		{
			if (preg_match('/js$/', $include))
			{
				$final_includes .= '<script src="' . $include . '"></script>' . "\n\r";
			}
		}

		return $final_includes;
	}

	// print js content
	public function print_js_content()
	{
		$final_content = '';

		foreach($this->include_content as $content)
		{
			$final_content .= '<script>' . $content . '</script>';
		}

		return $final_content;
	}

	// call the layouts view from the controller
	public function view($view_name, $params = array(), $default = true, $layouts = array())
	{
		if (is_array($layouts) && count($layouts) >= 1)
		{

			foreach ($layouts as $layout_key => $layout)
			{

				$params[$layout_key] = $this->CI->load->view($layout, $params, true);

			}
			
		}

		// set layout title
		$params['layout_title'] 		= $this->layout_title;

		// set layout description
		$params['layout_description']	= $this->layout_description;
		if ($default)
		{

			// var_dump($this->print_css_includes());

			if($default) 
			{

				// render header
				$this->CI->load->view('templates/front_end/header', $params);

				// render navbar
				$this->CI->load->view('templates/front_end/navbar', $params);
				
				// render left_sidebar
				$this->CI->load->view('templates/front_end/left_sidebar', $params);

				// render view
				$this->CI->load->view($view_name, $params);

				// render right_sidebar
				$this->CI->load->view('templates/front_end/right_sidebar', $params);
				
				// render modal
				$this->CI->load->view('templates/front_end/login_register_modal', $params);

				// render footer
				$this->CI->load->view('templates/front_end/footer', $params);

			}

		} 
		else 
		{
			// render header
			$this->CI->load->view('templates/front_end/header', $params);

			// render navbar
			$this->CI->load->view('templates/front_end/navbar', $params);
			
			// render view
			$this->CI->load->view($view_name, $params);
			
			// render modal
			$this->CI->load->view('templates/front_end/login_register_modal', $params);

			// render footer
			$this->CI->load->view('templates/front_end/footer', $params);

		}

	}

}

?>