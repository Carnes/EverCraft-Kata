window.EverCraft = window.EverCraft || {};
(function(ns){
    ns.Character = function(){
        var self = this;

        self.name = ko.observable();
        self.alignment = ko.observable();
        self.armorClass = ko.observable(10);
        self.hitPoints = ko.observable(5);

        self.attack = function(defender){
            var attackRoll = Random.d20();
            if(attackRoll < defender.armorClass())
                return false;

            var dmg = 1;
            if(attackRoll == 20)
                dmg = dmg * 2;

            defender.takeDamage(dmg);
            return true;
        };

        self.takeDamage = function(amount){
            var hp = self.hitPoints() - amount;
            self.hitPoints(hp);
        };

        self.isDead = ko.computed({
            read: function(){
                return self.hitPoints() <= 0;
            }
        });
    };
})(window.EverCraft);
