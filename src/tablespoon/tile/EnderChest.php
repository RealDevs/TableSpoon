<?php
namespace tablespoon\tile;

use pocketmine\inventory\InventoryHolder;
use pocketmine\level\Level;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\StringTag;
use pocketmine\tile\Nameable;
use pocketmine\tile\Spawnable;
use pocketmine\Player;

use tablespoon\inventory\EnderChestInventory;

class EnderChest extends Spawnable implements InventoryHolder, Nameable{

	public function __construct(Level $level, CompoundTag $nbt){
		parent::__construct($level, $nbt);
		$this->inventory = new EnderChestInventory($this);
	}

	public function getInventoryOf(Player $player) : EnderChestInventory{
		return new EnderChestInventory($this);
	}

	public function addAdditionalSpawnData(CompoundTag $nbt){
		if($this->hasName()){
			$nbt->CustomName = $this->namedtag->CustomName;
		}
	}

	/**
	 * @return EnderChestInventory
	 */
	public function getInventory() : EnderChestInventory{
		return $this->inventory;
	}

	/**
	 * @return string
	 */
	public function getName() : string{
		return isset($this->namedtag->CustomName) ? $this->namedtag->CustomName->getValue() : "Ender Chest";
	}

	/**
	 * @return bool
	 */
	public function hasName() : bool{
		return isset($this->namedtag->CustomName);
	}

	/**
	 * @param string $str
	 */
	public function setName(string $str){
		if($str === ""){
			unset($this->namedtag->CustomName);
			return;
		}
		$this->namedtag->CustomName = new StringTag("CustomName", $str);
	}
}