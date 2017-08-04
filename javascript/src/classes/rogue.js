window.EverCraft = window.EverCraft || {};
window.EverCraft.Classes = window.EverCraft.Classes || {};
(function(ns){
    ns.Rogue = function(){
        var self = new ns.Default();

        self.getCriticalDamage = function(attacker, defender){
            return (1 + attacker.strengthModifier()) * 3;
        };

        return self;
    };
})(window.EverCraft.Classes);

