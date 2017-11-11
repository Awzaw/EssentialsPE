<?php

declare(strict_types = 1);

namespace EssentialsPE\Tasks;

use EssentialsPE\Loader;
use pocketmine\Player;
use pocketmine\scheduler\AsyncTask;
use pocketmine\Server;
use pocketmine\utils\Utils;

class GeoLocation extends AsyncTask{
    /** @var Player[]|null */
    private $player = null;
    /** @var array */
    private $ip = [];

    /**
     * @param Player|Player[]|null $player
     */
    public function __construct($player){
        if($player !== null){
        	$playerarray = is_array($player) ? $player : [$player];
            foreach($playerarray as $p){
                $spl = spl_object_hash($p);
                $this->player[$spl] = $p;
                $this->ip[$spl] = $p->getAddress();
            }
        }
    }

    public function onRun(): void{
		if($this->player === null){
			$data = Utils::getURL("http://ip-api.com/json/");
			if($data === null || $data === false) return;
			$list = [];
			foreach($this->ip as $spl => $ip){
				$data = Utils::getURL("http://ip-api.com/json/" . $ip);
				if($data === null || $data === false) continue;
				$data = json_decode($data, true);
				if(isset($data["message"]) && $data["message"] === "private range"){
					$data["country"] = "server";
				}
				if(isset($data["country"])){
					$list[$spl] = $data["country"] ?? "Unknown";
				}
			}
			$this->setResult($list);
		}
	}
    /**
     * @param Server $server
     */
    public function onCompletion(Server $server): void{
        /** @var Loader $plugin */
        $plugin = $server->getPluginManager()->getPlugin("EssentialsPE");
        if(!is_array($this->getResult()) and $this->getResult() !== null){
            $plugin->getAPI()->setServerGeoLocation($this->getResult());
        }else{
        	if ($this->getResult() !== null){
            foreach($this->getResult() as $spl => $loc){
            	if($this->player[$spl] instanceof Player){
					$plugin->getAPI()->updateGeoLocation($this->player[$spl], ($loc !== "server" ?? $plugin->getAPI()->getServerGeoLocation()));
				}
            }
			}
        }
    }
}