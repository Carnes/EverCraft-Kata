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

        var logAttackMiss = function(attacker, defender){
            ns.log(attacker.name()+' attacks ' +defender.name() + ' and misses.');
        };

        var logAttackHit = function(attacker, defender, damage){
            ns.log(attacker.name()+' attacks ' +defender.name() + ' and deals '+damage+' damage.');
        };

        self.attack = function (defender) {
            var attackRoll = Random.d20();
            var strModifier = self.strengthModifier();
            var attackBonus = self.class().getAttackBonusPerLevel(self, defender);
            if (attackRoll + strModifier + attackBonus < defender.armorClass()) {
                logAttackMiss(self, defender);
                return false;
            }

            var criticalDamage = self.class().getCriticalDamage(self, defender);
            var dmg = 0;
            if (attackRoll == 20)
                dmg = criticalDamage;
            else
                dmg = 1 + strModifier;

            if(dmg < 1) dmg = 1;

            logAttackHit(self,defender,dmg);
            defender.takeDamage(dmg);
            self.experience(self.experience() + 10);
            return true;
        };

        self.takeDamage = function (newDmg) {
            var oldDmg = _.damage();
            _.damage(oldDmg + newDmg);
        };

        var logIsDead = function(){
            ns.log(self.name() + ' has died.');
        };

        self.isDead = ko.computed({
            read: function () {
                var isDead = self.hitPoints() <= 0;
                if(isDead) logIsDead();
                return isDead;
            }
        });
    };
})(window.EverCraft);
