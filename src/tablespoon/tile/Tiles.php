<?php
namespace tablespoon\tile;

use pocketmine\tile\Tile;

class Tiles{

	const ENDER_CHEST = "EnderChest";

	public static function init(){
		Tile::registerTile(EnderChest::class);
	}
}