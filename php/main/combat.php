<?php
class combat
{
    public function attack($attacker, $defender, $attackRole){
        if($attackRole + $attacker->getAttackRoleBonus($defender) > $defender->armorClass) {
            $damage = $attacker->getAttackDamage($defender, $attackRole);

            $defender->takeDamage($damage);
            $attacker->experience += 10;
            return true;
        }
        return false;
    }
}