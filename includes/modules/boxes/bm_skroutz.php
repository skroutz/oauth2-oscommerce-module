<?php
class bm_skroutz {

	var $code = 'bm_skroutz';
	var $group = 'boxes';
	var $title;
	var $description;
	var $sort_order;
	var $enabled = false;

	function bm_skroutz() {
		$this->title = MODULE_BOXES_SKROUTZ_TITLE;
		$this->description = MODULE_BOXES_SKROUTZ_DESCRIPTION;
		$this->button = MODULE_BOXES_SKROUTZ_BUTTON;

		if ( defined('MODULE_BOXES_SKROUTZ_STATUS') ) {
			$this->sort_order = MODULE_BOXES_SKROUTZ_SORT_ORDER;
        	$this->enabled = (MODULE_BOXES_SKROUTZ_STATUS == 'True');
			$this->group = ((MODULE_BOXES_SKROUTZ_CONTENT_PLACEMENT == 'Right Column') ? 'boxes_column_right' : 'boxes_column_left');
		}
	}
	
	function getData() {
    	global $PHP_SELF,$HTTP_GET_VARS;

		// Display the button
		$content = tep_draw_form('manufacturers', tep_href_link('skroutz.php', '', 'NONSSL', false), 'post').
			tep_draw_input_field('submit',$this->button,'','submit').
			'</form>';
		return $content;
	}

	function execute() {
		global  $oscTemplate;
		$button = $this->getData();
		$data = '<div>'.
				'	<div class="ui-widget-header infoBoxHeading">'.$this->title.'</div>'.
				'	<div class="ui-widget-content infoBoxContents">'.$button.'</div>'.
				'</div>';
		$oscTemplate->addBlock($data, $this->group);
	}

	function isEnabled() {
		return $this->enabled;
	}

    function check() {
		return defined('MODULE_BOXES_SKROUTZ_STATUS');
	}

	function install() {
		tep_db_query("insert into ".TABLE_CONFIGURATION." (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Enable Submit designs Module', 'MODULE_BOXES_SKROUTZ_STATUS', 'True', 'Do you want to add the module to your shop?', '6', '1', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");

		tep_db_query("insert into ".TABLE_CONFIGURATION." (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Content Placement', 'MODULE_BOXES_SKROUTZ_CONTENT_PLACEMENT', 'Right Column', 'Should the module be loaded in the left or right column?', '6', '1', 'tep_cfg_select_option(array(\'Left Column\', \'Right Column\'), ', now())");

		tep_db_query("insert into ".TABLE_CONFIGURATION." (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort Order', 'MODULE_BOXES_SKROUTZ_SORT_ORDER', '0', 'Sort order of display. Lowest is displayed first.', '6', '0', now())");

	}

	function keys() {
		return array('MODULE_BOXES_SKROUTZ_STATUS', 'MODULE_BOXES_SKROUTZ_CONTENT_PLACEMENT', 'MODULE_BOXES_SKROUTZ_SORT_ORDER');
	}

	function remove() {
		tep_db_query("delete from ".TABLE_CONFIGURATION." where configuration_key in ('".implode("', '", $this->keys()) . "')");
	}

 }

?>