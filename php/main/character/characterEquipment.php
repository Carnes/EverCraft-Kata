<?php
class characterEquipmentSlot
{
    public $equipment;
    public $description;

    public function __construct($description) {
        if(!is_string($description))
            throw new Exception("characterEquipmentSlot must be initialized with a description");
        $this->description = $description;
        $this->equipment = null;
    }
}

class characterEquipmentGroup
{
    public $slots;
    public $type;

    public function __construct($slots) {
        if($slots instanceof characterEquipmentSlot)
            $slots = [$slots];
        if(is_array($slots))
            $this->slots = $slots;
        else
            $this->slots = array();
    }

    public function hasAvailableSlot()
    {
        foreach($this->slots as $slot)
            if($slot->equipment == null)
                return true;
        return false;
    }

    public function freeEquipmentSlot($equipment)
    {
        foreach($this->slots as $slot)
            if($slot->equipment == $equipment)
            {
                $slot->equipment = null;
                return;
            }
        throw new Exception("Could not free an equipment slot: ".$this->type);
    }

    public function occupyEquipmentSlot($equipment)
    {
        foreach($this->slots as $slot)
            if($slot->equipment == null)
            {
                $slot->equipment = $equipment;
                return;
            }
        throw new Exception("Could not occupy an equipment slot: ".$this->type);
    }
}

class characterEquipment
{
    public $body;
    public $equiped;
    private $character;

    public function __construct($character)
    {
        $this->character = $character;
        $this->equiped = array();
        $this->body = array();
        $this->body[\Equipment\slotType::$Chest] = new characterEquipmentGroup(new characterEquipmentSlot("Armor"));
        $this->body[\Equipment\slotType::$Hand] = new characterEquipmentGroup([
            new characterEquipmentSlot("Left hand"),
            new characterEquipmentSlot("Right hand")
        ]);
        $this->body[\Equipment\slotType::$Finger] = new characterEquipmentGroup([
            new characterEquipmentSlot("Left hand decor"),
            new characterEquipmentSlot("Right hand decor")
        ]);
        $this->body[\Equipment\slotType::$Arm] = new characterEquipmentGroup([
            new characterEquipmentSlot("Left arm"),
            new characterEquipmentSlot("Right arm")
        ]);
        $this->body[\Equipment\slotType::$Head] = new characterEquipmentGroup(new characterEquipmentSlot("Head"));
        $this->body[\Equipment\slotType::$Foot] = new characterEquipmentGroup([
            new characterEquipmentSlot("Left foot"),
            new characterEquipmentSlot("Right foot")
        ]);
        $this->body[\Equipment\slotType::$Waist] = new characterEquipmentGroup(new characterEquipmentSlot("Waist"));
        $this->body[\Equipment\slotType::$Back] = new characterEquipmentGroup(new characterEquipmentSlot("Back"));
        $this->body[\Equipment\slotType::$NeckDecoration] = new characterEquipmentGroup(new characterEquipmentSlot("Neck decor"));
        $this->body[\Equipment\slotType::$Neck] = new characterEquipmentGroup(new characterEquipmentSlot("Neck"));
        $this->body[\Equipment\slotType::$Leg] = new characterEquipmentGroup([
            new characterEquipmentSlot("Left leg"),
            new characterEquipmentSlot("Right right")
        ]);
    }

    private function getItemInSlot($itemSlot)
    {
        foreach($this->equiped as $equipment)
            foreach($equipment->getRequiredSlots() as $slot)
                if($slot == $itemSlot)
                    return $equipment;
        return null;
    }

    public function getEquipmentInSlot($slot)
    {
        $equipment = array();
        foreach($this->body[$slot]->slots as $slot)
            if($slot->equipment != null)
                $equipment[] = $slot->equipment;
        return $equipment;
    }

    public function getEquipmentOfType($equipmentType)
    {
        foreach($this->equiped as $needle)
            if($needle->type == $equipmentType)
                return $needle;
        return null;
    }

    public function equip($equipment)
    {
        $removedEquipment = array();

        if(!($equipment instanceof \Equipment\IEquipment && $equipment->isEquipable($this->character)))
            return [$equipment];

        foreach($equipment->getRequiredSlots() as $slot)
        {
            if(!($this->body[$slot]->hasAvailableSlot()))
                $removedEquipment[] = $this->unequip($this->getItemInSlot($slot));
            $this->body[$slot]->occupyEquipmentSlot($equipment);
        }

        $this->equiped[] = $equipment;

        return $removedEquipment;
    }

    private function isEquiped($equipment)
    {
        foreach($this->equiped as $needle)
            if($needle == $equipment)
                return true;
        return false;
    }

    private function removeEquipment($equipment){
        foreach($this->equiped as $key=>$needle)
            if($needle == $equipment)
                unset($this->equiped[$key]);
    }

    public function unequip($equipment)
    {
        if(!($equipment instanceof \Equipment\IEquipment))
            throw new Exception("Cannot unequip something that isn't equipment.");
        if($this->isEquiped($equipment)===false)
            throw new Exception("Cannot unequip something that isn't already equiped.");

        foreach($equipment->getRequiredSlots() as $slot)
        {
            $removeSuccess = $this->body[$slot]->freeEquipmentSlot($equipment);
            if($removeSuccess === false)
                throw new Exception("unequip failed to remove a ".$slot." slot from ".$equipment->getName());
        }

        $this->removeEquipment($equipment);
        return $equipment;
    }
}