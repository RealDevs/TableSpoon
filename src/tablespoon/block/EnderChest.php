<?php
namespace tablespoon\block;

use pocketmine\block\Block;
use pocketmine\block\BlockFactory;
use pocketmine\block\Solid;
use pocketmine\item\enchantment\Enchantment;
use pocketmine\item\Item;
use pocketmine\item\ItemFactory;
use pocketmine\item\Tool;
use pocketmine\math\AxisAlignedBB;
use pocketmine\math\Vector3;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\IntTag;
use pocketmine\nbt\tag\StringTag;
use pocketmine\Player;
use pocketmine\tile\Tile;

use tablespoon\tile\EnderChest as TileEnderChest;
use tablespoon\tile\Tiles;

class EnderChest extends Solid{

	protected $id = self::ENDER_CHEST;

	public function __construct(int $meta = 0){
		$this->meta = $meta;
	}

	public function getLightLevel() : int{
		return 7;
	}

	public function getName() : string{
		return "Ender Chest";
	}

	public function getToolType() : int{
		return Tool::TYPE_PICKAXE;
	}

	public function getHardness() : float{
		return 22.5;
	}

	public function getBlastResistance() : float{
		return 3000;
	}

	public function place(Item $item, Block $blockReplace, Block $blockClicked, int $face, Vector3 $facePos, Player $player = null) : bool{
		$this->meta = [
			0 => 4,
			1 => 2,
			2 => 5,
			3 => 3,
		][$player instanceof Player ? $player->getDirection() : 0];

		$this->getLevel()->setBlock($blockReplace, $this, true, true);
		$nbt = new CompoundTag("", [
			new StringTag("id", Tiles::ENDER_CHEST),
			new IntTag("x", $this->x),
			new IntTag("y", $this->y),
			new IntTag("z", $this->z)
		]);
		if($item->hasCustomName()){
			$nbt->CustomName = new StringTag("CustomName", $item->getCustomName());
		}
		if($item->hasCustomBlockData()){
			foreach($item->getCustomBlockData() as $key => $v){
				$nbt->{$key} = $v;
			}
		}
		Tile::createTile(Tiles::ENDER_CHEST, $this->getLevel(), $nbt);
		return true;
	}

	public function onActivate(Item $item, Player $player = null) : bool{
		if($player instanceof Player){
			$tile = $this->getLevel()->getTile($this);
			if(!($tile instanceof TileEnderChest)){
				$nbt = new CompoundTag("", [
					new StringTag("id", Tiles::ENDER_CHEST),
					new IntTag("x", $this->x),
					new IntTag("y", $this->y),
					new IntTag("z", $this->z)
				]);
				$tile = Tile::createTile(Tiles::ENDER_CHEST, $this->getLevel(), $nbt);
			}
			$player->addWindow($tile->getInventory());
		}
		return true;
	}

	protected function recalculateBoundingBox(){
		return new AxisAlignedBB(
			$this->x + 0.0625,
			$this->y,
			$this->z + 0.0625,
			$this->x + 0.9375,
			$this->y + 0.9475,
			$this->z + 0.9375
		);
	}

	public function getDrops(Item $item) : array{
		if($item->isPickaxe()){
			if($item->hasEnchantment(Enchantment::SILK_TOUCH)){
				return parent::getDrops($item);
			}
			return [ItemFactory::get(Item::OBSIDIAN, 0, 8)];
		}
		return [];
	}
}