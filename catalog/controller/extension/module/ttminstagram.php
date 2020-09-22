<?php
class ControllerExtensionModuleTtminstagram extends Controller {
    public function index($setting) {
        $this->load->language('extension/module/ttminstagram');

        $data = array();
		
        $data['text_copyright'] = sprintf($this->language->get('text_copyright'), $this->config->get('config_name'));

        if (empty($setting['limit'])) {
            $setting['limit'] = 10;
        }

        $file = "https://api.instagram.com/v1/users/".$setting['userid']."/media/recent/?access_token=".$setting['access_token'];
		
		$content = @file_get_contents($file);
		
		$data['error_connect']	= true;
		
		if($content === false){			
			$data['error_connect']	= false;
		} else {
			$instagram_json_data = json_decode($content,true);
			$instagram_arrays = $instagram_json_data['data'];

			$data['username'] = $instagram_arrays[0]['user']['username'];

			$data['instagrams'] = array();
			$data['grid'] = $setting['grid'];

			foreach($instagram_arrays as $key => $instagram_array) {
				$instagrams[] = array(
					'likes' => $instagram_array['likes']['count'],
					'small_image' => $instagram_array['images']['thumbnail']['url'], 
					'medium_image' => $instagram_array['images']['low_resolution']['url'], 
					'image' => $instagram_array['images']['low_resolution']['url'],
					'link'  => $instagram_array['link'],
					'created_time' => date('m/d/Y', $instagram_array['created_time']),
					'comment' => $instagram_array['comments']['count'],
					'caption' => $instagram_array['caption']['text'],
				);
				if ($key == $setting['limit'] - 1) break;
			};

			$data['instagrams'] = $instagrams;
			
		}
			return $this->load->view('extension/module/ttminstagram', $data);
    }
}