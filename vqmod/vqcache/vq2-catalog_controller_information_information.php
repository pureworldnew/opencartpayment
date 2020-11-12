<?php
class ControllerInformationInformation extends Controller {

			private function parseText($node, $keyword, $dom, $link, $target='', $tooltip = 0)
			{
				if (mb_strpos($node->nodeValue, $keyword) !== false)
					{
						$keywordOffset = mb_strpos($node->nodeValue, $keyword, 0, 'UTF-8');
						$newNode = $node->splitText($keywordOffset);
						$newNode->deleteData(0, mb_strlen($keyword, 'UTF-8'));
						$span = $dom->createElement('a', $keyword);
						if ($tooltip)
							{
								$span->setAttribute('href', '#');
								$span->setAttribute('style', 'text-decoration:none');
								$span->setAttribute('class', 'title');
								$span->setAttribute('title', $keyword.'|'.$link);
							}
							else
							{
								$span->setAttribute('href', $link);
								$span->setAttribute('target', $target);
								$span->setAttribute('style', 'text-decoration:none');
							}							
						
						$node->parentNode->insertBefore($span, $newNode);
						$this->parseText($newNode ,$keyword, $dom, $link, $target, $tooltip);
					}					
			}
			
			

			
	public function index() {
		$this->load->language('information/information');

		$this->load->model('catalog/information');

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/home')
		);

		if (isset($this->request->get['information_id'])) {
			$information_id = (int)$this->request->get['information_id'];
		} else {
			$information_id = 0;
		}

		$information_info = $this->model_catalog_information->getInformation($information_id);

		if ($information_info) {
			$this->document->setTitle($information_info['meta_title']?$information_info['meta_title']:$information_info['title']);

				$canonicals = $this->config->get('canonicals');
				if (isset($canonicals['canonicals_info'])) {
					$this->document->removeLink('canonical');
					$this->document->addLink($this->url->link('information/information', 'information_id=' .  $information_id), 'canonical');
					}
			$this->document->setDescription($information_info['meta_description']);
			$this->document->setKeywords($information_info['meta_keyword']);

			$data['breadcrumbs'][] = array(
				'text' => $information_info['title'],
				'href' => $this->url->link('information/information', 'information_id=' .  $information_id)
			);

			$data['heading_title'] = $information_info['title'];

			$data['button_continue'] = $this->language->get('button_continue');

			$data['description'] = html_entity_decode($information_info['description'], ENT_QUOTES, 'UTF-8');


				$autolinks = $this->config->get('autolinks'); 
				
				if (isset($autolinks) && (strpos($data['description'], 'iframe') == false) && (strpos($data['description'], 'object') == false)){
				$xdescription = mb_convert_encoding(html_entity_decode($data['description'], ENT_COMPAT, "UTF-8"), 'HTML-ENTITIES', "UTF-8"); 
				
				libxml_use_internal_errors(true);
				$dom = new DOMDocument; 			
				$dom->loadHTML('<div>'.$xdescription.'</div>');				
				libxml_use_internal_errors(false);
				
						
				$xpath = new DOMXPath($dom);
				
				foreach ($autolinks as $autolink)
				{	
					$keyword = $autolink['keyword'];
					$xlink = mb_convert_encoding(html_entity_decode($autolink['link'], ENT_COMPAT, "UTF-8"), 'HTML-ENTITIES', "UTF-8");
					$target = $autolink['target'];
					$tooltip = isset($autolink['tooltip']);
					
					$pTexts = $xpath->query(
						sprintf('///text()[contains(., "%s")]', $keyword)
					);
					
					foreach ($pTexts as $pText) {
						$this->parseText($pText, $keyword, $dom, $xlink, $target, $tooltip);
					}

									
				}
								
				$data['description'] = $dom->saveXML($dom->documentElement);
				}
				
			
			$data['continue'] = $this->url->link('common/home');

			$data['column_left'] = $this->load->controller('common/column_left');
			$data['column_right'] = $this->load->controller('common/column_right');
			$data['content_top'] = $this->load->controller('common/content_top');
			$data['content_bottom'] = $this->load->controller('common/content_bottom');
			$data['footer'] = $this->load->controller('common/footer');
			$data['header'] = $this->load->controller('common/header');

			if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/information/information.tpl')) {
				$this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/information/information.tpl', $data));
			} else {
				$this->response->setOutput($this->load->view('default/template/information/information.tpl', $data));
			}
		} else {
			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('text_error'),
				'href' => $this->url->link('information/information', 'information_id=' . $information_id)
			);

			$this->document->setTitle($this->language->get('text_error'));

				$canonicals = $this->config->get('canonicals');
				if (isset($canonicals['canonicals_info'])) {
					$this->document->removeLink('canonical');
					$this->document->addLink($this->url->link('information/information', 'information_id=' .  $information_id), 'canonical');
					}

			$data['heading_title'] = $this->language->get('text_error');

			$data['text_error'] = $this->language->get('text_error');

			$data['button_continue'] = $this->language->get('button_continue');

			$data['continue'] = $this->url->link('common/home');

			$this->response->addHeader($this->request->server['SERVER_PROTOCOL'] . ' 404 Not Found');

			$data['column_left'] = $this->load->controller('common/column_left');
			$data['column_right'] = $this->load->controller('common/column_right');
			$data['content_top'] = $this->load->controller('common/content_top');
			$data['content_bottom'] = $this->load->controller('common/content_bottom');
			$data['footer'] = $this->load->controller('common/footer');
			$data['header'] = $this->load->controller('common/header');

			if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/error/not_found.tpl')) {
				$this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/error/not_found.tpl', $data));
			} else {
				$this->response->setOutput($this->load->view('default/template/error/not_found.tpl', $data));
			}
		}
	}

	public function agree() {
		$this->load->model('catalog/information');

		if (isset($this->request->get['information_id'])) {
			$information_id = (int)$this->request->get['information_id'];
		} else {
			$information_id = 0;
		}

		$output = '';

		$information_info = $this->model_catalog_information->getInformation($information_id);

		if ($information_info) {
			$output .= html_entity_decode($information_info['description'], ENT_QUOTES, 'UTF-8') . "\n";
		}

		$this->response->setOutput($output);
	}
}