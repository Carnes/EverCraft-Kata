window.EverCraft = window.EverCraft || {};
window.EverCraft.Classes = window.EverCraft.Classes || {};
(function(ns){
    ns.Fighter = function(){
        var self = new ns.Default();

        self.hitPointsPerLevel = 10;
        return self;
    };
})(window.EverCraft.Classes);

