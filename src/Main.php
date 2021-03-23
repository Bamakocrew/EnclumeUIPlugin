<?php

namespace EnclumeUIPlugin\Main;



use EnclumeUIPlugin\Enclume\EnclumeUI\EnclumeUI;
use pocketmine\event\Listener;
use pocketmine\plugin\PluginBase;
use pocketmine\Server;

class Main extends PluginBase implements Listener
{

    public function onEnable()
    {
        Server::getInstance()->getPluginManager()->registerEvents(new EnclumeUI($this), $this);
        //FormAPI
        if (!$this->getServer()->getPluginManager()->getPlugin("FormAPI")){
            $this->getLogger()->error("Je ne peux pas fonctionner sans FormAPI, desactivation en cours ...");
            $this->getServer()->getPluginManager()->disablePlugin($this);
        }
        //PurePerm
        if (!$this->getServer()->getPluginManager()->getPlugin("PurePerms")){
            $this->getLogger()->error("Je ne peux pas fonctionner sans PurePerms, desactivation en cours ...");
            $this->getServer()->getPluginManager()->disablePlugin($this);
        }

        $this->getLogger()->info("BamakoPlugin ON - de Bamako");
    }
    /**
     * @return Main
     */
}