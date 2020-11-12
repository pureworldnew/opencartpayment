<?php
class ModelCatalogYoutubeEmbed extends Model {
	public function getYoutubeVideos($string) {		$youtube_urls = Array();		$urls = $this->getURLs($string);		foreach ($urls as $url) {			if (stripos($url, 'youtu.be') > 0 || stripos($url, '.youtube.com') > 0) {				$youtube_urls[] = $url;			}		}		return array_unique($youtube_urls);	}	public function getURLs($string) {		$reg_exUrl = "/((((http|https|ftp|ftps)\:\/\/)|www\.)[a-zA-Z0-9\-\.]+\.[a-zA-Z]{2,4}(\/\S*)?)/";		preg_match_all($reg_exUrl, strip_tags($string), $matches);		return $matches[0];	}		public function getYoutubeCode($url, $type=0) {
		if ($type == 0) {
			$embed_width = html_entity_decode($this->config->get('youtube_embed_width'));
			$embed_height = html_entity_decode($this->config->get('youtube_embed_height'));
		}
		
		if ($type == 1) {
			$embed_width = html_entity_decode($this->config->get('youtube_embed_category_width'));
			$embed_height = html_entity_decode($this->config->get('youtube_embed_category_height'));
		}
		
		if ($type == 2) {
			$embed_width = html_entity_decode($this->config->get('youtube_embed_information_width'));
			$embed_height = html_entity_decode($this->config->get('youtube_embed_information_height'));
		}
				$embed_fs = html_entity_decode($this->config->get('youtube_embed_fs'));
		if ($embed_fs == 1) { $embed_fs = 'true'; } else { $embed_fs = 'false'; }				$url = str_ireplace('youtu.be/', 'youtube.com/v/', $url);		$url = str_ireplace('youtube.com/watch?v=', 'youtube.com/v/', $url);		$embed_url = $url;					$embed_url .= '?autohide=' . html_entity_decode($this->config->get('youtube_embed_autohide'));		$embed_url .= '&autoplay=' . html_entity_decode($this->config->get('youtube_embed_autoplay'));		$embed_url .= '&border=' . html_entity_decode($this->config->get('youtube_embed_border'));		$embed_url .= '&cc_load_policy=' . html_entity_decode($this->config->get('youtube_embed_cc_load_policy'));		$embed_url .= '&color=' . html_entity_decode($this->config->get('youtube_embed_color'));		$embed_url .= '&color1=' . html_entity_decode($this->config->get('youtube_embed_color1'));		$embed_url .= '&color2=' . html_entity_decode($this->config->get('youtube_embed_color2'));		$embed_url .= '&controls=' . html_entity_decode($this->config->get('youtube_embed_controls'));		$embed_url .= '&disablekb=' . html_entity_decode($this->config->get('youtube_embed_disablekb'));		$embed_url .= '&egm=' . html_entity_decode($this->config->get('youtube_embed_egm'));		$embed_url .= '&fs=' . $embed_fs;		$embed_url .= '&hd=' . html_entity_decode($this->config->get('youtube_embed_hd'));		$embed_url .= '&iv_load_policy=' . html_entity_decode($this->config->get('youtube_embed_iv_load_policy'));		$embed_url .= '&modestbranding=' . html_entity_decode($this->config->get('youtube_embed_modestbranding'));		$embed_url .= '&rel=' . html_entity_decode($this->config->get('youtube_embed_rel'));		$embed_url .= '&showinfo=' . html_entity_decode($this->config->get('youtube_embed_showinfo'));		$embed_url .= '&showsearch=' . html_entity_decode($this->config->get('youtube_embed_showsearch'));		$embed_url .= '&theme=' . html_entity_decode($this->config->get('youtube_embed_theme'));		return "<object width=\"$embed_width\" height=\"$embed_height\"><param name=\"movie\" value=\"$embed_url\"></param><param name=\"allowFullScreen\" value=\"$embed_fs\"></param><param name=\"allowscriptaccess\" value=\"always\"></param><embed src=\"$embed_url\" type=\"application/x-shockwave-flash\" width=\"$embed_width\" height=\"$embed_height\" allowscriptaccess=\"always\" allowfullscreen=\"$embed_fs\"></embed></object>\n";	}
}
?>