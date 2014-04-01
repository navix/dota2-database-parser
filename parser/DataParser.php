<?php

namespace Parser;

use Config\MainConfig;
use Parser\Entities\Ability;
use Parser\Entities\Hero;
use Parser\Entities\Item;
use Vendors\KeyValues;


class DataParser {

	/** @var MainConfig */
	private $config;

	/** @var KeyValues */
	private $reader;

	/** @var Hero[] */
	private $heroes = [];

	/** @var Ability[] */
	private $abilities = [];

	/** @var Item[] */
	private $items = [];

	private $localeData = [];


	public function __construct(MainConfig $config) {
		$this->config = $config;
	}


	public function parse() {
		$this->initReader();
		$this->parseHeroes();
		$this->parseAbilities();
		$this->replaceAbilitiesNamesToIds();
		$this->removeAbilitiesWithoutHero();
		$this->parseItems();
		$this->localize();
	}


	private function initReader() {
		$this->reader = new KeyValues();
	}


	private function parseHeroes() {
		$heroesData = $this->reader->load($this->config->sources['heroes']);
		foreach ($heroesData as $name => $line) {
			if (isset($line['HeroID'])) {
				if (in_array($name, $this->config->blacklists['heroes']))
					continue;
				$hero = new Hero();
				$hero->id = $line['HeroID'];
				$hero->name = $name;
				$hero->url = $line['url'];
				foreach ($line as $key => $value) {
					if (preg_match('/Ability(\d)/i', $key)) {
						if (in_array($value, $this->config->blacklists['abilities']))
							continue;
						$hero->abilities[] = $value;
					}
				}
				$this->heroes[] = $hero;
			}
		}
	}


	private function parseAbilities() {
		$abilitiesData = $this->reader->load($this->config->sources['abilities']);
		foreach ($abilitiesData as $name => $line) {
			if (!empty($line['ID'])) {
				if (in_array($name, $this->config->blacklists['abilities']))
					continue;
				$ability = new Ability();
				$ability->id = $line['ID'];
				$ability->name = $name;
				$this->abilities[] = $ability;
			}
		}
	}


	private function  replaceAbilitiesNamesToIds() {
		foreach ($this->heroes as $hero) {
			foreach ($hero->abilities as &$abilityName) {
				foreach ($this->abilities as &$ability) {
					if ($ability->name == $abilityName) {
						$abilityName = $ability->id;
						$ability->heroId = $hero->id;
						break;
					}
				}
			}
		}
	}


	private function removeAbilitiesWithoutHero() {
		foreach ($this->abilities as $key => $ability) {
			if (!$ability->heroId)
				unset($this->abilities[$key]);
		}
		$this->abilities = array_values($this->abilities);
	}


	private function parseItems() {
		$itemsData = $this->reader->load($this->config->sources['items']);
		foreach ($itemsData as $name => $line) {
			if (!empty($line['ID'])) {
				$item = new Item();
				$item->id = $line['ID'];
				$item->name = $name;
				$this->items[] = $item;
			}
		}
	}


	private function localize() {
		$this->loadLocaleData();
		$this->localizeHeroes();
		$this->localizeAbilities();
		$this->localizeItems();
	}


	private function loadLocaleData() {
		$this->localeData = $this->reader->load($this->config->sources['locale']);
	}


	private function localizeHeroes() {
		foreach ($this->heroes as $hero)
			$hero->localizedName = $this->getLocalizeFromTokens($hero->name);
	}


	private function localizeAbilities() {
		foreach ($this->abilities as $ability)
			$ability->localizedName = $this->getLocalizeFromTokens('DOTA_Tooltip_ability_' . $ability->name);
	}


	private function localizeItems() {
		foreach ($this->items as $item) {
			$item->localizedName = $this->getLocalizeFromTokens('DOTA_Tooltip_Ability_' . $item->name);
			if (!$item->localizedName && strpos($item->name, '_recipe')) {
				$item->localizedName = $this->getLocalizeFromTokens('DOTA_Tooltip_Ability_' . str_replace('_recipe', '', $item->name));
			}
		}
	}


	private function getLocalizeFromTokens($name) {
		foreach ($this->localeData['Tokens'] as $key => $value) {
			if (strtolower($key) == strtolower($name))
				return $value;
		}
	}


	public function save() {
		file_put_contents($this->config->results['heroes'], json_encode($this->heroes, JSON_PRETTY_PRINT));
		file_put_contents($this->config->results['abilities'], json_encode($this->abilities, JSON_PRETTY_PRINT));
		file_put_contents($this->config->results['items'], json_encode($this->items, JSON_PRETTY_PRINT));
	}

}