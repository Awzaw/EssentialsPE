<?php
namespace EssentialsPE\Tasks;


use pocketmine\scheduler\PluginTask;

class TravisKillTask extends PluginTask{
    /**
     * Actions to execute when run
     *
     * @param $currentTick
     *
     * @return void
     */
    public function onRun($currentTick){
        $this->getOwner()->getServer()->forceShutdown();
        exit(0);
    }

}