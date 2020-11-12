<?php
	namespace google_marketing_tools;
	class gdpr extends master{
		public function is_enabled() {
		    $is_enabled = $this->config->get('google_gdpr_status_'.$this->id_store);
            return $is_enabled;
		}

		public function get_code_head() {
		    $gdpr_title = $this->config->get('google_gdpr_title_'.$this->id_store.'_'.$this->language_id);
            $gdpr_text = $this->config->get('google_gdpr_text_'.$this->id_store.'_'.$this->language_id);
            $gdpr_button_position = $this->config->get('google_gdpr_button_position_'.$this->id_store);
            $gdpr_button_title = $this->config->get('google_gdpr_button_title_'.$this->id_store.'_'.$this->language_id);
            $gdpr_conatiner_width = $this->config->get('google_gdpr_conatiner_width_'.$this->id_store);
            $gdpr_button_accept_text = $this->config->get('google_gdpr_button_accept_text_'.$this->id_store.'_'.$this->language_id);
            $gdpr_button_checkbox_statistics = $this->config->get('google_gdpr_button_checkbox_statistics_'.$this->id_store.'_'.$this->language_id);
            $gdpr_button_checkbox_marketing = $this->config->get('google_gdpr_button_checkbox_marketing_'.$this->id_store.'_'.$this->language_id);
            $gdpr_bar_background = $this->config->get('google_gdpr_bar_background_'.$this->id_store);
            $gdpr_button_configure_background = $this->config->get('google_gdpr_button_configure_background_'.$this->id_store);
            $gdpr_bar_color = $this->config->get('google_gdpr_bar_color_'.$this->id_store);
            $gdpr_bar_background_link = $this->config->get('google_gdpr_bar_background_link_'.$this->id_store);
            $gdpr_bar_background_link_hover = $this->config->get('google_gdpr_bar_background_link_hover_'.$this->id_store);
            $gdpr_bar_background_button = $this->config->get('google_gdpr_bar_background_button_'.$this->id_store);
            $gdpr_bar_background_button_hover = $this->config->get('google_gdpr_bar_background_button_hover_'.$this->id_store);
            $gdpr_bar_color_button = $this->config->get('google_gdpr_bar_color_button_'.$this->id_store);
            $gdpr_custom_code = $this->config->get('google_gdpr_custom_code_'.$this->id_store);
            $gdpr_position = $this->config->get('google_gdpr_position_'.$this->id_store);

            $code = '<style>';
                $code .= 'div.gmt_gdpr {';
                    $code .= 'background: #'.$gdpr_bar_background.';';
                    $code .= 'color: #'.$gdpr_bar_color.';';
                    $code .= ($gdpr_position == 'top' ? 'top' : 'bottom').': 0px;';
                $code .= '}';

                $code .= 'div.gmt_gdpr div.gmt_gdpr_container {';
                    $code .= 'max-width: '.$gdpr_conatiner_width.'px;';
                $code .= '}';

                $code .= 'div.gmt_gdpr span.gmt_gdpr_text a{';
                    $code .= 'color: #'.$gdpr_bar_background_link.';';
                $code .= '}';

                $code .= 'div.gmt_gdpr span.gmt_gdpr_text a:hover{';
                    $code .= 'color: #'.$gdpr_bar_background_link_hover.';';
                $code .= '}';

                $code .= 'div.gmt_gdpr a.button_accept{';
                    $code .= 'background: #'.$gdpr_bar_background_button.';';
                    $code .= 'color: #'.$gdpr_bar_color_button.';';
                $code .= '}';

                $code .= 'div.gmt_gdpr a.button_accept:hover{';
                    $code .= 'background: #'.$gdpr_bar_background_button_hover.';';
                $code .= '}';
                $code .= ':root {
                    --gmt-gdpr-button-config-background: #'.$gdpr_button_configure_background.';
                }';
            $code .= '</style>';

            $code .= htmlspecialchars_decode($gdpr_custom_code);


            $some_cookie_accepted = $this->some_cookie_accepted();
            $statistics_checked = !$some_cookie_accepted || ($some_cookie_accepted && $this->check_cookie('gmt_cookie_statistics'));
            $marketing_checked = !$some_cookie_accepted || ($some_cookie_accepted && $this->check_cookie('gmt_cookie_marketing'));

            if(!empty($gdpr_button_position)) {

                $code .= '<div title="'.$gdpr_button_title.'" onclick="open_gdpr();" class="gmt_gdpr_button_config '.$gdpr_button_position.'">';
                    $code .= '<img class="gmt_gdpr_button_icon" src="catalog/view/theme/devmanextensions_gmt/cookie_button_icon.png">';
                $code .= '</div>';
            }

            $code .= '<div'.($some_cookie_accepted ? ' style="display:none;"':'').' class="gmt_gdpr">';
                $code .= '<div class="gmt_gdpr_container">';
                    //$code .= '<span class="gmt_gdpr_close_button" onclick="close_gdpr()">X</span>';
                    $code .= '<span class="gmt_gdpr_title">'.$gdpr_title.'</span>';
                    $code .= '<span class="gmt_gdpr_text">'.htmlspecialchars_decode($gdpr_text).'</span>';
                    $code .= '<div style="clear:both;"></div>';
                    $code .= '<div class="gmt_gdpr_checkboxes">';
                        $code .= '<label class="checkbox_container">';
                            $code .= '<label>'.$gdpr_button_checkbox_statistics.'</label>';
                            $code .= '<input name="statistics" type="checkbox" class="ios-switch green" '.($statistics_checked ? 'checked="checked"':'').'>';
                            $code .= '<div><div></div></div>';
                        $code .= '</label>';
                        $code .= '<label class="checkbox_container">';
                            $code .= '<label>'.$gdpr_button_checkbox_marketing.'</label>';
                            $code .= '<input name="marketing" type="checkbox" class="ios-switch green" '.($marketing_checked ? 'checked="checked"':'').'>';
                            $code .= '<div><div></div></div>';
                        $code .= '</label>';
                        $code .= '<a class="button_accept" onclick="accept_current_setting();">'.$gdpr_button_accept_text.'</a>';
                    $code .= '</div>';
                $code .= '</div>';
            $code .= '</div>';

            return $code;

        }

        public function check_cookie($cookie_name) {
		    if(!$this->config->get('google_gdpr_status_'.$this->id_store))
		        return true;
		    else {
		        return array_key_exists($cookie_name, $_COOKIE) && $_COOKIE[$cookie_name] == 'accepted' ? true : false;
            }
        }

        public function some_cookie_accepted() {
		    return array_key_exists('gmt_cookie_statistics', $_COOKIE) || array_key_exists('gmt_cookie_marketing', $_COOKIE);
        }
	}
?>