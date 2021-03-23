<?php

namespace EnclumeUIPlugin\Enclume\EnclumeUI;



use EnclumeUIPlugin\Main\Main;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\item\Armor;
use pocketmine\item\Item;
use pocketmine\item\Tool;
use pocketmine\Player;
use pocketmine\utils\TextFormat;

class EnclumeUI implements Listener {
    private $c;
    public function __construct(Main $c)
    {
        $this->c = $c;
    }

    public function onTouch(PlayerInteractEvent $event){
        $player = $event->getPlayer();
        $block = $event->getBlock();

        if ($block->getId() === Item::ANVIL){
            $event->setCancelled();
            $this->onAnvil($player);
        }
    }
    public function onAnvil(Player $player){
        $form = new SimpleForm(function (Player $player, int $data = null){
            $result = $data;
            if ($result === null){
                return true;
            }
            switch ($result){
                case 0:
                    if ($player->getXpLevel() >= 1){
                        $index = $player->getInventory()->getHeldItemIndex();
                        $item = $player->getInventory()->getItemInHand();
                        if ($item instanceof Tool or $item instanceof Armor){
                            if ($item->getDamage() > 0){
                                $player->getInventory()->setItem($index, $item->setDamage(0));
                                $player->sendMessage(TextFormat::GREEN . "Vous avez bien repair l'item dans votre main!");
                                $player->setXpLevel($player->getXpLevel() - 1);
                            } else{
                                $player->sendMessage(TextFormat::RED . "Votre item est deja repair!");
                            }
                        } else{
                            $player->sendMessage(TextFormat::RED . "L'item que vous tenez ne peut pas etre repair!");
                        }
                    } else{
                        $player->sendMessage(TextFormat::RED . "Vous n'avez pas assez d'xp");
                    }
                    break;
                case 1:
                    if ($player->getXpLevel() >= 1) {
                        foreach ($player->getInventory()->getContents() as $index => $item) {
                            if ($item instanceof Tool or $item instanceof Armor) {
                                if ($item->getDamage() > 0) {
                                    $player->getInventory()->setItem($index, $item->setDamage(0));
                                    $player->setXpLevel($player->getXpLevel() - 1);
                                    $player->sendMessage(TextFormat::GREEN . "Vous avez repair tout votre inventaire");
                                } else{
                                    $player->sendMessage(TextFormat::RED . "Tout vos items sont repair");
                                }
                            }
                        }
                    } else{
                        $player->sendMessage(TextFormat::RED . "Vous n'avez pas assez de level");
                    }
                    break;
                case 2:
                    if ($player->getXpLevel() >= 1) {
                        foreach ($player->getArmorInventory()->getContents() as $index => $item) {
                            if ($item instanceof Armor) {
                                if ($item->getDamage() > 0) {
                                    $player->getArmorInventory()->setItem($index, $item->setDamage(0));
                                    $player->setXpLevel($player->getXpLevel() - 1);
                                    $player->sendMessage(TextFormat::GREEN . "Vous avez repair tout votre inventaire");
                                } else{
                                    $player->sendMessage(TextFormat::RED . "Tout vos items sont repair");
                                }
                            }
                        }
                    } else{
                        $player->sendMessage(TextFormat::RED . "Vous n'avez pas assez de level");
                    }
                    break;
            }
        });
        $form->setTitle(TextFormat::GOLD . "Enclume");
        $form->setContent(TextFormat::GRAY . "Bienvenue dans le menu de l'enclume");
        $form->addButton("Repair dans ma main\n10 xp");
        $form->addButton("Repair tout mon inventaire\n30 xp");
        $form->addButton("Repair tout mon armure\n30 xp");
        $form->addButton(TextFormat::RED . "Quitter");
    }
}