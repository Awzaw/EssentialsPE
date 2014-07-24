<?php
namespace EssentialsPE\Commands;

use EssentialsPE\BaseCommand;
use EssentialsPE\Loader;
use pocketmine\command\CommandSender;
use pocketmine\Server;
use pocketmine\utils\TextFormat;

class Broadcast extends BaseCommand{
    public function __construct(Loader $plugin){
        parent::__construct($plugin, "broadcast", "Broadcast a message.", "/broadcast <message>", ["bcast"]);
        $this->setPermission("essentials.command.broadcast.use");
    }

    public function execute(CommandSender $sender, $alias, array $args){
        if(!$this->testPermission($sender)){
            return false;
        }
        if(count($args) == 0){
            $sender->sendMessage(TextFormat::RED . "Usage: " . $this->getUsage());
        }else{
            $message = $this->getAPI()->colorMessage(implode(" ",$args));
            Server::getInstance()->broadcastMessage(TextFormat::LIGHT_PURPLE . "[Broadcast] " . TextFormat::RESET . $message);
        }
        return true;
    }
}