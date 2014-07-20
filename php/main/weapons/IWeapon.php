<?php
namespace Weapon;
interface IWeapon
{
    function getDamage($wielder, $target);
    function getAttack($wielder, $target);
    function getCriticalMultiplier($wielder, $target);
}