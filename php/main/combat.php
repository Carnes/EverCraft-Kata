<?php
class combat
{
    public function attack($attacker, $defender, $attackRole){
        if($attackRole + $attacker->strengthModifier > $defender->armorClass + $defender->dexterityModifier) {
            $damage = $attacker->getAttackDamage($attackRole);

            $defender->takeDamage($damage);
            $attacker->experience += 10;
            return true;
        }
        return false;
    }
}