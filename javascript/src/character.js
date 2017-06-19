window.EverCraft = window.EverCraft || {};
(function (ns) {
    ns.Character = function () {
        var self = this;

        self.name = ko.observable();
        self.alignment = ko.observable();
        self.armorClass = ko.observable(10);
        self.hitPoints = ko.observable(5);

        self.strength = ko.observable(10);
        self.dexterity = ko.observable(10);
        self.constitution = ko.observable(10);
        self.wisdom = ko.observable(10);
        self.intelligence = ko.observable(10);
        self.charisma = ko.observable(10);

        self.strengthModifier = ko.computed({
            read: function () {
                var str = self.strength();
                return ns.Enums.AbilityChart[str];
            }
        });
        self.dexterityModifier = ko.computed({
            read: function () {
                var dex = self.dexterity();
                return ns.Enums.AbilityChart[dex];
            }
        });
        self.constitutionModifier = ko.computed({
            read: function () {
                var con = self.constitution();
                return ns.Enums.AbilityChart[con];
            }
        });
        self.wisdomModifier = ko.computed({
            read: function () {
                var wis = self.wisdom();
                return ns.Enums.AbilityChart[wis];
            }
        });
        self.intelligenceModifier = ko.computed({
            read: function () {
                var int = self.intelligence();
                return ns.Enums.AbilityChart[int];
            }
        });
        self.charismaModifier = ko.computed({
            read: function () {
                var cha = self.charisma();
                return ns.Enums.AbilityChart[cha];
            }
        });

        self.attack = function (defender) {
            var attackRoll = Random.d20();
            if (attackRoll + self.strengthModifier() < defender.armorClass())
                return false;

            //var baseDamage = 1;
            var bonusDamage = self.strengthModifier();
            var dmg = 1;
            if (attackRoll == 20)
                dmg += 1 + (bonusDamage * 2);
            else
                dmg += bonusDamage;

            defender.takeDamage(dmg);
            return true;
        };

        self.takeDamage = function (amount) {
            var hp = self.hitPoints() - amount;
            self.hitPoints(hp);
        };

        self.isDead = ko.computed({
            read: function () {
                return self.hitPoints() <= 0;
            }
        });
    };
})(window.EverCraft);
