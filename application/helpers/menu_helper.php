<?php
    if(!function_exists('active_link')){
        function activate_menu($controller){
            $CI = get_instance();
            $class = $CI->router->fetch_class();
            return ($class == $controller) ? 'active' : '';
        }
    }

    function show_err_msg($content='') {
		if ($content != '') {
			return   '<div class="box-body pad res-tb-block">
              <img src="assets/images/alert/alert.png" alt="alert" class="model_img img-fluid" id="sa-basic">              
            </div>';
		}
	}
?>