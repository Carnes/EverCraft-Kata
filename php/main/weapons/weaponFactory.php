<?php
include_once("weapon.php");
include_once("formula.php");
class weaponFactory
{
    private $weapon;

    private function __construct($weapon = null){
        if($weapon == null)
            $this->weapon = new Weapon\weapon();
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
        $formula = new Weapon\formula(
            function()use($dmgAmount){ return $dmgAmount; },
            ($dmgAmount >0 ? "+": "").$dmgAmount." damage"
        );

        $this->weapon->damageFormulas[] = $formula;
        return $this;
    }

    public function withAttack($atkAmount)
    {
        $formula = new Weapon\formula(
            function()use($atkAmount){ return $atkAmount; },
            ($atkAmount >0 ? "+": "").$atkAmount." attack"
        );

        $this->weapon->attackFormulas[] = $formula;
        return $this;
    }

    public function getWeapon()
    {
        return $this->weapon;
    }
}