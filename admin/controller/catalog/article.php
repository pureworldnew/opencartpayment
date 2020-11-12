<?php
class ControllerCatalogArticle extends Controller {
	private $error = array();

	public function index() {
		$this->language->load('catalog/attribute');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('catalog/attribute');

		
	}

	

	

	public function autocomplete() {
		$json = array();
		if (isset($this->request->get['filter_name'])) {
			$this->load->model('catalog/article');
			$filter_data = array(
				'filter_name' => $this->request->get['filter_name'],
				'start'       => 0,
				'limit'       => 5
			);
            
			$results = $this->model_catalog_article->getArticles($filter_data);

			foreach ($results as $result) {
				$json[] = array(
					'article_id'    => $result['ID'],
					'name'          => strip_tags(html_entity_decode($result['post_title'], ENT_QUOTES, 'UTF-8'))

				);
			}
		}

		$sort_order = array();

		foreach ($json as $key => $value) {
			$sort_order[$key] = $value['name'];
		}
		array_multisort($sort_order, SORT_ASC, $json);

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}
