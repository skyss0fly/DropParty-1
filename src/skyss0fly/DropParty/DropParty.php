<?php

namespace skyss0fly\DropParty;

use pocketmine\plugin\PluginBase;
use skyss0fly\DropParty\task\DropItemsTask;
use skyss0fly\DropParty\task\DropPartyTask;
use pocketmine\utils\Config;
use pocketmine\scheduler\TaskScheduler;
use pocketmine\world\WorldManager;
use pocketmine\Server;

class DropParty extends PluginBase {
	
	public $secs = 0;
	public $tasks = [];
	public $status;
	public $time;
	public $config;
	public $cfg;
	
	public function onEnable():void {

		$this->saveResource("config.yml");
		$this->config = new Config($this->getDataFolder() . "config.yml", Config::YAML, array());
		$this->cfg = $this->config->getAll();

		
		

		$this->time = $this->cfg["Time"];
		$level = $this->getWorldManager()->getWorldByName($this->cfg["World"]);
		if ($level !== null) {
			$level->loadChunk($this->cfg["Coordinates"]["X"], $this->cfg["Coordinates"]["Z"]);
			$this->getScheduler()->scheduleRepeatingTask(new task\DropPartyTask($this), 20 * 60);
			$this->getScheduler()->scheduleRepeatingTask(new task\DropItemsTask($this), 20);
		}else {
			$this->getLogger()->warning("World not found!Please check your config and try again.");
			$this->getServer()->getPluginManager()->disablePlugin($this);
		}
	}
	
	public function config() {
	  return $this->cfg;
	}
	
	public function getDropPartyTask() {
	  return new DropPartyTask($this);
	}
	
	public function getDropItemsTask() {
	  return new DropItemsTask($this);
	}
	
	public function getRandomItem() {
	  $rand = mt_rand(0, count($this->cfg["Items"]) - 1);
		
	  return $this->cfg["Items"][$rand];
		
	}
	
}
