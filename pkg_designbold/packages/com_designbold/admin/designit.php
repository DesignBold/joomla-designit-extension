<?php
/**
 * @package Joomla.Component
 * @version 1.0
 * @author DesignBold
 * @copyright (C) 2018- DesignBold
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
*/

defined('_JEXEC') or die();

Class DesignitController extends JControllerLegacy{

	public function __construct (){
		JLoader::register('JFile', JPATH_LIBRARIES . '/joomla/filesystem/file.php');
		JLoader::register('JFolder', JPATH_LIBRARIES . '/joomla/filesystem/folder.php');
		$this->dbsdk_run();
	}

	public function dbsdk_renameDuplicates($path, $file)
	{
		$fileName = pathinfo($path . $file, PATHINFO_FILENAME);
		$fileExtension = "." . pathinfo($path . $file, PATHINFO_EXTENSION);

		$returnValue = $fileName . $fileExtension;

		$copy = 1;
		while(file_exists($path . $returnValue))
		{
			$returnValue = $fileName . $copy . $fileExtension;
			$copy++;
		}
		return $returnValue;
	}

	public function dbsdk_download_url() {
		// Create new folder designbold inside images folder default of joomla
		$cur_year = date("Y");
		$cur_month = date('m');
		$designit_path = JFolder::create(JPATH_SITE."/images/designbold/".$cur_year."/".$cur_month);

		// Handle Request
		$post_url = JFactory::getApplication()->input->get('dbsdk_post_url') ? trim(JFactory::getApplication()->input->get('dbsdk_post_url')) : ''; 
		$post_url = filter_var ( $post_url, FILTER_SANITIZE_STRING);
		$file_name = basename( parse_url( $post_url, PHP_URL_PATH ) );

		if ( isset( $post_url ) && $post_url != '' && $file_name != '') {
			$obj_data = (object)[];
			// Check file_array is an image or not
			if(@is_array(getimagesize($post_url))){
				$dbsdk_save_dir = JPATH_SITE."/images/designbold/".$cur_year."/".$cur_month;

				// Create a stream
				$opts = [
				    "http" => [
				        "method" => "GET",
				        "header" => "Accept-language: en\r\n" .
				            "Cookie: foo=bar\r\n"
				    ]
				];
				$context = stream_context_create($opts);
				$contentFile = file_get_contents($post_url, false, $context);

				// Check file name exit
				$newName = $this->dbsdk_renameDuplicates($dbsdk_save_dir, $file_name);

				if(file_put_contents($dbsdk_save_dir . $newName, $contentFile)){
					$obj_data->image_info = array('url' => "images/designbold/".$cur_year."/".$cur_month."/". $newName);
				}

				header("Content-type: application/json; charset=utf-8");
				echo json_encode($obj_data);
				JFactory::getApplication()->close();
			}else{
				echo 'The uploaded file is not a valid image';
				JFactory::getApplication()->close();
			}
		}else{
			echo 'Well come to DesignBold API !';
			JFactory::getApplication()->close();
		}
	}

	public function dbsdk_run(){
		$this->dbsdk_download_url();
	}
}

$api = new DesignitController();
