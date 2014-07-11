<?php
class combat
{
    public function attack($attacker, $defender, $damageRole){
        if($damageRole + $attacker->strengthModifier > $defender->armorClass + $defender->dexterityModifier) {
            $damage = 1 + $attacker->strengthModifier;
            if($damageRole==20)
                $damage *= 2;
            if($damage<1)
                $damage=1;

            $defender->takeDamage($damage);
            $attacker->experience += 10;
            return true;
        }
        return false;
    }
}