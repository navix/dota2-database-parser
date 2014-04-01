<?php

namespace Parser;

use Config\MainConfig;
use Parser\Entities\Ability;
use Parser\Entities\Hero;
use Parser\Entities\Item;


class MediaLoader {

	/** @var MainConfig */
	private $config;

	/** @var Hero[] */
	private $heroes = [];

	/** @var Ability[] */
	private $abilities = [];

	/** @var Item[] */
	private $items = [];


	public function __construct(MainConfig $config) {
		$this->config = $config;
	}


	public function load() {
		$this->loadEntities();
		$this->loadHeroesImages();
		$this->loadAbilitiesImages();
		$this->loadItemsImages();
	}


	private function loadEntities() {
		$this->heroes = Hero::initFromArray(json_decode(file_get_contents($this->config->results['heroes']), true));
		$this->abilities = Ability::initFromArray(json_decode(file_get_contents($this->config->results['abilities']), true));
		$this->items = Item::initFromArray(json_decode(file_get_contents($this->config->results['items']), true));
	}


	private function loadHeroesImages() {
		foreach ($this->heroes as $hero) {
			foreach ($this->config->imagesSuffixes['heroes'] as $suffix) {
				$imageFilename = str_replace('npc_dota_hero_', '', $hero->name) . $suffix;
				$this->save($imageFilename, 'heroes');
			}
		}
	}


	private function loadAbilitiesImages() {
		foreach ($this->abilities as $ability) {
			foreach ($this->config->imagesSuffixes['abilities'] as $suffix) {
				$imageFilename = $ability->name . $suffix;
				$this->save($imageFilename, 'abilities');
			}
		}
	}


	private function loadItemsImages() {
		foreach ($this->items as $item) {
			foreach ($this->config->imagesSuffixes['items'] as $suffix) {
				$imageFilename = str_replace('item_', '', $item->name) . $suffix;
				$this->save($imageFilename, 'items');
			}
		}
	}


	private function save($imageFilename, $cdn) {
		$cdnFilename = $this->config->cdn[$cdn] . $imageFilename;
		$localFilename = $this->config->images[$cdn] . $imageFilename;
		if (!file_exists($localFilename)) {
			$image = file_get_contents($cdnFilename);
			if ($image)
				file_put_contents($localFilename, $image);
		}
	}

}