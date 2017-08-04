window.EverCraft = window.EverCraft || {};
window.EverCraft.Classes = window.EverCraft.Classes || {};
(function(ns){
    ns.Default = function(){
        var self = this;

        self.hitPointsPerLevel = 5;
        self.getAttackBonusPerLevel = function(attacker, defender){
            return Math.floor(attacker.level() / 2);
        };
        self.getCriticalDamage = function(attacker, defender){
            return  (1 + attacker.strengthModifier()) * 2;
        };

        return self;
    };
})(window.EverCraft.Classes);

