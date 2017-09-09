<?php
namespace tablespoon\inventory;

use pocketmine\inventory\ContainerInventory;
use pocketmine\item\Item;
use pocketmine\nbt\NBT;
use pocketmine\nbt\tag\ListTag;
use pocketmine\network\mcpe\protocol\types\WindowTypes;
use pocketmine\Player;

use tablespoon\tile\EnderChest;

class EnderChestInventory extends ContainerInventory{

	/** @var Player */
	private $user;

	public function onOpen(Player $player){
		$this->user = $player;
		if(isset($player->namedtag->EnderItems) && $player->namedtag->EnderItems instanceof ListTag){
			foreach($player->namedtag->EnderItems as $slot => $itemNBT){
				$this->slots[$slot] = Item::nbtDeserialize($itemNBT);
			}
		}else{
			$player->namedtag->EnderItems = new ListTag("EnderItems", array_fill(0, 27, Item::get(Item::AIR, 0, 0)->nbtSerialize()));
			$player->namedtag->EnderItems->setTagType(NBT::TAG_Compound);
		}
		parent::onOpen($player);
	}

	public function setItem(int $index, Item $item, bool $send = true) : bool{
		if(parent::setItem($index, $item, $send)){
			if($this->user !== null){
				$this->user->namedtag->EnderItems->{$index} = $item->nbtSerialize($index);
			}
			//TODO: Send debug message
			return true;
		}
		return false;
	}

	public function getNetworkType() : int{
		return WindowTypes::CONTAINER;
	}

	public function getName() : string{
		return "EnderChest";
	}

	public function getDefaultSize() : int{
		return 27;
	}
}