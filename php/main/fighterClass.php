<?php
include_once("ICharacterClass.php");
class fighterClass implements ICharacterClass
{
    private static $name = "Fighter";
    private static $modifiers = null;

    public function getName(){
        return $this::$name;
    }

    public function __construct(){
        if($this::$modifiers == null)
        {
            $this::$modifiers = array();
            $this::$modifiers[] = new formula(
                availableFormulaCategories::$AttackDamagePerLevel,
                function($character){return $character->level;},
                "Fighter gets +1 attack damage for each level"
            );
            $this::$modifiers[] = new formula(
                availableFormulaCategories::$MaxHitPointsPerLevel,
                function($character){return (10 * $character->level) + $character->constitutionModifier;},
                "Fighter gets 10 hit points per level plus constitution modifier"
            );
        }
    }

    public function getModifiers() {

        return $this::$modifiers;
    }
}