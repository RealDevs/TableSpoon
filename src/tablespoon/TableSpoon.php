<?php
namespace tablespoon;

use pocketmine\plugin\PluginBase;
use pocketmine\PocketMine;
use pocketmine\utils\TextFormat;

use tablespoon\block\Blocks;
use tablespoon\tile\Tiles;

class TableSpoon extends PluginBase{

	public function onEnable(){
		if(
			PHP_INT_MAX === 0x7FFFFFFF ||
			!defined("INT32_MIN") ||
			!defined("INT32_MAX") ||
			defined("GENISYS_API_VERSION") ||
			$this->getServer()->getName() !== "PocketMine-MP"
		){
			$this->getServer()->getLogger()->warning(TextFormat::RED."TableSpoon was not made for spoons.");
			$this->getServer()->getPluginManager()->disablePlugin($this);
			return;
		}

		$this->start();
	}

	private function start(){
		$this->getServer()->getLogger()->notice(TextFormat::GREEN."TableSpoon has been enabled.");

		Blocks::init();
		Tiles::init();
	}
}