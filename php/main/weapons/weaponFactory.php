<?php
namespace Weapon;
include_once("weapon.php");
class weaponFactory
{
    private $weapon;

    private function __construct($weapon = null){
        if($weapon == null)
            $this->weapon = new weapon();
        else
            $this->weapon = $weapon;
    }

    public static function startForge(){
        $self = new self();
        return $self;
    }

    public static function reForge($weapon){
        $self = new self($weapon);
        return $self;
    }

    public function withName($name)
    {
        $this->weapon->name = $name;
        return $this;
    }

    public function withDamage($dmgAmount)
    {
        $this->weapon->damage = $dmgAmount;
        return $this;
    }

    public function getWeapon()
    {
        return $this->weapon;
    }
}