<?php
	/*
		Can be run over the web to insert a message into a forum
			/plugins/inserter/index.php?forum=[Forum Name]&msg=[Message URL-encoded]&code=2498jfknf-changeme
			
		You should change the 'passcode' element to your unique code before allowing this plugin,
		and even then we would recommend only running this plugin over an HTTPS connection, to prevent
		interception of the unencrypted request passcode via the URL.
	
	
	*/
	
	function trim_trailing_slash_local($str) {
        return rtrim($str, "/");
    }
    
    function add_trailing_slash_local($str) {
        //Remove and then add
        return rtrim($str, "/") . '/';
    }

	if(!isset($insert_config)) {
        //Get global plugin config - but only once
		$data = file_get_contents (dirname(__FILE__) . "/config/config.json");
        if($data) {
            $insert_config = json_decode($data, true);
            if(!isset($insert_config)) {
                echo "Error: insert config/config.json is not valid JSON.";
                exit(0);
            }
     
        } else {
            echo "Error: Missing config/config.json in insert plugin.";
            exit(0);
     
        }
  
  
    }



 	$start_path = add_trailing_slash_local($insert_config['serverPath']);

	$staging = $insert_config['staging'];	
	
	$notify = true;
	include_once($start_path . 'config/db_connect.php');	
	
	$define_classes_path = $start_path;     //This flag ensures we have access to the typical classes, before the cls.pluginapi.php is included
	require($start_path . "classes/cls.pluginapi.php");
	
	$api = new cls_plugin_api();
	
	
	if((isset($_REQUEST['code'])) && ($_REQUEST['code'] == $insert_config['passcode'])) {
	
		if(isset($_REQUEST['msg'])) {
			$message = strval($_REQUEST['msg']);
			if(isset($_REQUEST['hideImages'])) {
				$message = str_ireplace(".jpg", "", $message);
			}
		} else {
			$message = "";
		}
	
		if(isset($_REQUEST['forum'])) {
			$forum_name = strval($_REQUEST['forum']);
		} else {
			$forum_name = $insert_config['defaultForum'];
		}
	
	

		 $shouted = $message;		//guid may not be url for some feeds, may need to have link
		 $your_name = $insert_config['name'];
		 $whisper_to = null;		//to the whole forum
		 $email = $insert_config['email'];
		 $ip = "92.27.10.17"; //must be something anything


		 //Get the forum id
		 $forum_info = $api->get_forum_id($forum_name);
	
		 //Send the message
		 $options = array('always_send_email' => true,
				 'notification' => true);
		 $api->new_message($your_name, $shouted, $whisper_to, $email, $ip, $forum_info['forum_id'], $options);
	
		 echo "Message has been queued successfully.";
		 $api->complete_parallel_calls();
	} else {
		echo "Sorry, that does not have the correct code. No message has been added.";
	
	}
	
	
?>
