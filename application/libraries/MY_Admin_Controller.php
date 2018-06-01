<?

class MY_Admin_Controller extends CI_Controller{
	
	protected $user_info;
	protected $login_info;
	
	public function __construct(){
        parent::__construct();
		/*if(!app_access()){
			show_404();
		}*/

		$this->load->helper('admin');
    }
}

?>