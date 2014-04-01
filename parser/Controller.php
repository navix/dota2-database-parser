<?php

namespace Parser;

use Config\MainConfig;


class Controller {

	/** @var \Config\MainConfig */
	private $config;


	public function __construct(MainConfig $config) {
		$this->config = $config;
	}


	public function run() {
		//$this->parseData();
		$this->loadMedia();
		$this->response();
	}


	private function parseData() {
		$dataParser = new DataParser($this->config);
		$dataParser->parse();
		$dataParser->save();
	}


	private function loadMedia() {
		$mediaLoader = new MediaLoader($this->config);
		$mediaLoader->load();
	}


	private function response() {
		echo 'Completed!';
	}

}