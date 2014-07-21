<?php
namespace Equipment;
interface IEquipment
{
    function getDamage($wielder, $target);
    function getAttack($wielder, $target);
    function getCriticalMultiplier($wielder, $target);
    function getArmorClass($wearer, $attacker);
    function isEquipable($wearer);
}