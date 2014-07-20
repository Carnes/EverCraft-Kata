<?php
namespace Weapon;
interface IWeapon
{
    function getDamage();
    function getAttack();
    function getCriticalMultiplier($wielder);
}