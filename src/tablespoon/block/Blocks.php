<?php
namespace tablespoon\block;

use pocketmine\block\BlockFactory;

class Blocks{

	public static function init(){
		BlockFactory::registerBlock(new EnderChest());
	}
}