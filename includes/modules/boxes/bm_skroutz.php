<?php // vim: set ft=php et ts=4 sts=4 sw=4 ai si:
class bm_skroutz {

    var $code = 'bm_skroutz';
    var $group = 'boxes';
    var $title;
    var $description;
    var $logo;
    var $list_header;
    var $list_item1;
    var $list_item2;
    var $list_item3;
    var $more;
    var $button;
    var $sort_order;
    var $enabled = false;

    function bm_skroutz() {
        $this->title = MODULE_BOXES_SKROUTZ_TITLE;
        $this->description = MODULE_BOXES_SKROUTZ_DESCRIPTION;
        $this->logo = 'skroutz_logo_90x30.png';
        $this->list_header = MODULE_BOXES_SKROUTZ_LIST_HEADER;
        $this->list_item1 = MODULE_BOXES_SKROUTZ_LIST_ITEM1;
        $this->list_item2 = MODULE_BOXES_SKROUTZ_LIST_ITEM2;
        $this->list_item3 = MODULE_BOXES_SKROUTZ_LIST_ITEM3;
        $this->more = MODULE_BOXES_SKROUTZ_MORE;
        $this->button = MODULE_BOXES_SKROUTZ_BUTTON;

        if ( defined('MODULE_BOXES_SKROUTZ_STATUS') ) {
            $this->sort_order = MODULE_BOXES_SKROUTZ_SORT_ORDER;
            $this->enabled = (MODULE_BOXES_SKROUTZ_STATUS == 'True');
            $this->group = ((MODULE_BOXES_SKROUTZ_CONTENT_PLACEMENT == 'Right Column') ? 'boxes_column_right' : 'boxes_column_left');
        }
    }

    function getData() {
        global $PHP_SELF, $HTTP_GET_VARS;

        // Display the button
        $content = tep_draw_form('skroutz_easy', tep_href_link('skroutz.php', '', 'NONSSL', false), 'post').
            '<div align="center">'.
            tep_image(DIR_WS_IMAGES . $this->logo, $this->title).
            '</div>'.
            '<div style="margin: 5px 0">' . $this->description . '.</div>'.
            $this->list_header . ':'.
            '<ul style="margin: 0 0 0.5em 2em; padding-left: 0">'.
            '  <li>' . $this->list_item1 . '</li>'.
            '  <li>' . $this->list_item2 . '</li>'.
            '  <li>' . $this->list_item3 . '</li>'.
            '</ul>'.
            '<div align="center"><a href="http://www.skroutz.gr/easy" style="color: #F68B24">' . $this->more . '</a></div>'.
            tep_draw_input_field('submit', $this->button, 'style="width: 100%; word-wrap: break-word"', 'submit').
            '</form>';
        return $content;
    }

    function execute() {
        global  $oscTemplate;

        $formData = $this->getData();
        $data = '<div>'.
                '    <div class="ui-widget-header infoBoxHeading">' . $this->title . '</div>'.
                '    <div class="ui-widget-content infoBoxContents">' . $formData . '</div>'.
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
        tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Enable Skroutz Easy Module', 'MODULE_BOXES_SKROUTZ_STATUS', 'True', 'Do you want to add the module to your shop?', '6', '1', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");

        tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Content Placement', 'MODULE_BOXES_SKROUTZ_CONTENT_PLACEMENT', 'Right Column', 'Should the module be loaded in the left or right column?', '6', '1', 'tep_cfg_select_option(array(\'Left Column\', \'Right Column\'), ', now())");

        tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort Order', 'MODULE_BOXES_SKROUTZ_SORT_ORDER', '0', 'Sort order of display. Lowest is displayed first.', '6', '0', now())");

    }

    function keys() {
        return array('MODULE_BOXES_SKROUTZ_STATUS', 'MODULE_BOXES_SKROUTZ_CONTENT_PLACEMENT', 'MODULE_BOXES_SKROUTZ_SORT_ORDER');
    }

    function remove() {
        tep_db_query("delete from " . TABLE_CONFIGURATION . " where configuration_key in ('" . implode("', '", $this->keys()) . "')");
    }
}
?>
