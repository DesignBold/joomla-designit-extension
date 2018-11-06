<?php
defined('_JEXEC') or die;
/**
 * @package Joomla.Plugin
 * @version 1.0
 * @author Designit
 * @copyright (C) 2010- Designit
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
/**
* Add button design image into editor
*
* @package Joomla.Plugin
* @subpackage Editors.designit
* @since 1.0.0
*/

class PlgButtonDesignit extends JPlugin{
	/**
	* Display the button
	*
	* @param string $name The name of the button to add
	*
	* @return array A four element array of (article_id, article_title, category_id, object)
	*/

	public function onDisplay($name){
		$doc = JFactory::getDocument();
		$doc->addStyleSheet('/joomla/plugins/editors-xtd/designit/assets/css/style.css');
		$doc->addScript('/joomla/plugins/editors-xtd/designit/assets/js/button.js');
		$button = new JObject;
		$button->modal = false;
		$button->class = 'btn';
		$button->text = 'Designit';
		$button->name = 'svg-designit';
		$button->onclick = "DBSDK.startOverlay();";
		$button->link = '#';

		return $button;
	}
}

