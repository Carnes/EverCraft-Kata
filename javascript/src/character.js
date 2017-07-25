window.EverCraft = window.EverCraft || {};
(function (ns) {
    ns.Character = function () {
        var self = this;
        var _ = {};

        _.baseArmorClass = ko.observable(10);
        _.damage = ko.observable(0);

        self.name = ko.observable();
        self.alignment = ko.observable();

        self.class = ko.observable(new ns.Classes.Default());

        self.strength = ko.observable(10);
        self.dexterity = ko.observable(10);
        self.constitution = ko.observable(10);
        self.wisdom = ko.observable(10);
        self.intelligence = ko.observable(10);
        self.charisma = ko.observable(10);
        self.experience = ko.observable(0);

        _.baseHitPoints = ko.computed({
            read:function(){
                var c = self.class();
                return c.hitPointsPerLevel;
            }
        });


        self.level = ko.computed({
            read: function () {
                var xp = self.experience();
                return Math.floor(xp / 1000) + 1;
            },
            write: function () {
                throw "Cannot set level directly.  Change character experience.";
            }
        })

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
        self.armorClass = ko.computed({
            read: function () {
                var baseArmorClass = _.baseArmorClass();
                var dexModifier = self.dexterityModifier();
                return baseArmorClass + dexModifier;
            },
            write: function (v) {
                throw "You can't directly alter armorClass.";
            }
        });
        self.hitPoints = ko.computed({
            read: function () {
                var baseHP = _.baseHitPoints();
                var conModifier = self.constitutionModifier();
                var dmg = _.damage();
                var level = self.level();
                var hp = baseHP + conModifier;
                if (hp < 1) hp = 1;
                var hp = hp * level;
                return hp - dmg;
            },
            write: function (v) {
                throw "You can't directly alter hitPoints.  Use gainHitPoints or takeDamage instead.";
            }
        });

        self.gainHitPoints = function (hp) {
            var dmg = _.damage();
            dmg -= hp;
            if (dmg < 0) dmg = 0;
            _.damage(dmg);
        };

        self.attack = function (defender) {
            var attackRoll = Random.d20();
            var strModifier = self.strengthModifier();
            var level = self.level();
            var levelBonus = Math.floor(level / 2);
            if (attackRoll + strModifier + levelBonus < defender.armorClass())
                return false;

            //var baseDamage = 1;
            var bonusDamage = strModifier;
            var dmg = 1;
            if (attackRoll == 20)
                dmg += 1 + (bonusDamage * 2);
            else
                dmg += bonusDamage;

            defender.takeDamage(dmg);
            self.experience(self.experience() + 10);
            return true;
        };

        self.takeDamage = function (newDmg) {
            var oldDmg = _.damage();
            _.damage(oldDmg + newDmg);
        };

        self.isDead = ko.computed({
            read: function () {
                return self.hitPoints() <= 0;
            }
        });
    };
})(window.EverCraft);
