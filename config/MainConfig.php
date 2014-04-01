<?php

namespace Config;


class MainConfig {

	public $sources = [
		'heroes' => 'sources/npc_heroes.txt',
		'abilities' => 'sources/npc_abilities.txt',
		'items' => 'sources/items.txt',
		'locale' => 'sources/dota_english.txt',
	];

	public $blacklists = [
		'heroes' => [
			'npc_dota_hero_abyssal_underlord',
		],
		'abilities' => [
			'attribute_bonus',
			'morphling_morph',
			'rubick_empty1',
			'rubick_empty2',
			'rubick_hidden1',
			'rubick_hidden2',
			'rubick_hidden3',
			'wisp_empty1',
			'wisp_empty2',
			'doom_bringer_empty1',
			'doom_bringer_empty2',
			'invoker_empty1',
			'invoker_empty2',
		],
	];

	public $results = [
		'heroes' => '../dota2-database/json/heroes.json',
		'abilities' => '../dota2-database/json/abilities.json',
		'items' => '../dota2-database/json/items.json',
	];

	public $cdn = [
		'heroes' => 'http://cdn.dota2.com/apps/dota2/images/heroes/',
		'abilities' => 'http://cdn.dota2.com/apps/dota2/images/abilities/',
		'items' => 'http://cdn.dota2.com/apps/dota2/images/items/',
	];

	public $images = [
		'heroes' => '../dota2-database/images/heroes/',
		'abilities' => '../dota2-database/images/abilities/',
		'items' => '../dota2-database/images/items/',
	];

	public $imagesSuffixes = [
		'heroes' => ['_sb.png', '_lg.png', '_full.png', '_vert.jpg'],
		'abilities' => ['_hp1.png', '_hp2.png'],
		'items' => ['_lg.png'],
	];

}