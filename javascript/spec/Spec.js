//https://github.com/PuttingTheDnDInTDD/EverCraft-Kata

window.EverCraft = window.EverCraft || {};
window.EverCraft.Enums = window.EverCraft.Enums || {};

describe("EverCraft", function() {
    var ns = window.EverCraft;
    var enums = ns.Enums;
    describe('Character', function(){
        it('exists', function(){
            expect(typeof(ns.Character)).toBe('function');
        });
        describe('Name', function(){
            var c = new ns.Character();

            it('exists', function(){
                expect(ko.isObservable(c.name)).toBe(true);
            });

            it('can get and set name', function(){
                //Arrange
                var expectedName = 'jim joe';

                //Act
                c.name(expectedName);

                //Assert
                expect(c.name()).toBe(expectedName);
            });
        });

        describe('Alignment', function(){
            var c = new ns.Character();

            it('exists', function(){
                expect(ko.isObservable(c.alignment)).toBe(true);
            });

            it('can get and set alignment', function(){
                //Arrange
                var expectedAlignment = enums.Alignment.Good;

                //Act
                c.alignment(expectedAlignment);

                //Assert
                expect(c.alignment()).toBe(expectedAlignment);
            });

            it('can get and set alignment', function(){
                //Arrange
                var expectedAlignment = enums.Alignment.Evil;

                //Act
                c.alignment(expectedAlignment);

                //Assert
                expect(c.alignment()).toBe(expectedAlignment);
            });
        });

        describe('Armor Class & Hit Points', function(){
            var c = new ns.Character();
            it('exists', function(){
                expect(ko.isObservable(c.armorClass)).toBe(true);
                expect(ko.isObservable(c.hitPoints)).toBe(true);
            });

            it('has an armor class of 10 by default', function(){
                var c = new ns.Character();
                expect(c.armorClass()).toBe(10);
                expect(c.hitPoints()).toBe(5);
            });
        });

        describe('Character can Attack', function(){

            it('exists', function(){
                var c = new ns.Character();
                expect(typeof(c.attack)).toBe('function');
            });

            it('can miss defender', function(){
                //Arrange
                var attacker = new ns.Character();
                var defender = new ns.Character();
                spyOn(Random, 'int').andCallFake(function(){return 0;});

                //Act
                var result = attacker.attack(defender);

                //Assert
                expect(result).toBe(false);
            });

            it('can hit defender', function(){
                //Arrange
                var attacker = new ns.Character();
                var defender = new ns.Character();
                spyOn(Random, 'int').andCallFake(function(){return 20;});

                //Act
                var result = attacker.attack(defender);

                //Assert
                expect(result).toBe(true);
            });

            it('can hit defender with same attack roll as AC', function(){
                //Arrange
                var attacker = new ns.Character();
                var defender = new ns.Character();
                spyOn(Random, 'int').andCallFake(function(){return defender.armorClass();});

                //Act
                var result = attacker.attack(defender);

                //Assert
                expect(result).toBe(true);
            });
        });

        describe('Character can be damaged', function(){
            it('attack deals 1 dmg to defender on successful attack', function(){
                //Arrange
                var attacker = new ns.Character();
                var defender = new ns.Character();
                spyOn(Random, 'int').andCallFake(function(){return 19;});
                expect(defender.hitPoints()).toBe(5);

                //Act
                var result = attacker.attack(defender);

                //Assert
                expect(defender.hitPoints()).toBe(4);
            });

            it('attack deals double dmg to defender on critical hit', function(){
                //Arrange
                var attacker = new ns.Character();
                var defender = new ns.Character();
                spyOn(Random, 'int').andCallFake(function(){return 20;});

                //Act
                var result = attacker.attack(defender);

                //Assert
                expect(defender.hitPoints()).toBe(3);
            });

            it('declares character is dead when hitpoints are zero or less', function(){
                //Arrange
                var attacker = new ns.Character();
                var defender = new ns.Character();
                spyOn(Random, 'int').andCallFake(function(){return 20;});

                //Act
                attacker.attack(defender);
                attacker.attack(defender);
                expect(defender.isDead()).toBe(false);
                attacker.attack(defender);

                //Assert
                expect(defender.hitPoints()).toBe(-1);
                expect(defender.isDead()).toBe(true);
            });
        });

        describe('Character has abilities', function(){
            it('has core abilities', function(){
                var c = new ns.Character();

                expect(ko.isObservable(c.strength)).toBe(true);
                expect(ko.isObservable(c.dexterity)).toBe(true);
                expect(ko.isObservable(c.constitution)).toBe(true);
                expect(ko.isObservable(c.wisdom)).toBe(true);
                expect(ko.isObservable(c.intelligence)).toBe(true);
                expect(ko.isObservable(c.charisma)).toBe(true);
            });

            it('defaults to 10', function(){
                var c = new ns.Character();

                expect(c.strength()).toEqual(10);
                expect(c.dexterity()).toEqual(10);
                expect(c.constitution()).toEqual(10);
                expect(c.wisdom()).toEqual(10);
                expect(c.intelligence()).toEqual(10);
                expect(c.charisma()).toEqual(10);
            });

            it('has modifiers to core abilities', function(){
                var c = new ns.Character();

                expect(ko.isObservable(c.strengthModifier)).toBe(true);
                expect(ko.isObservable(c.dexterityModifier)).toBe(true);
                expect(ko.isObservable(c.constitutionModifier)).toBe(true);
                expect(ko.isObservable(c.wisdomModifier)).toBe(true);
                expect(ko.isObservable(c.intelligenceModifier)).toBe(true);
                expect(ko.isObservable(c.charismaModifier)).toBe(true);
            });

            it('changes ability modifier based on ability', function(){
                var c = new ns.Character();
                var abilityChart = [null,-5,-4,-4,-3,-3,-2,-2,-1,-1,0,0,1,1,2,2,3,3,4,4,5];

                for(var i=1; i<=20; i++)
                {
                    c.strength(i);
                    c.dexterity(i);
                    c.constitution(i);
                    c.wisdom(i);
                    c.intelligence(i);
                    c.charisma(i);
                    var expectedAbilityModifier = abilityChart[i];
                    expect(c.strengthModifier()).toEqual(expectedAbilityModifier);
                    expect(c.dexterityModifier()).toEqual(expectedAbilityModifier);
                    expect(c.constitutionModifier()).toEqual(expectedAbilityModifier);
                    expect(c.wisdomModifier()).toEqual(expectedAbilityModifier);
                    expect(c.intelligenceModifier()).toEqual(expectedAbilityModifier);
                    expect(c.charismaModifier()).toEqual(expectedAbilityModifier);
                }
            });

            it('adds strengthModifier to attack', function(){
                var attacker = new ns.Character();
                var defender = new ns.Character();
                spyOn(Random, 'int').andCallFake(function(){return defender.armorClass() - 1;});

                attacker.strength(10);
                expect(attacker.strengthModifier()).toEqual(0);
                expect(attacker.attack(defender)).toBe(false);

                attacker.strength(12);
                expect(attacker.strengthModifier()).toEqual(1);
                expect(attacker.attack(defender)).toBe(true);
            });

            it('adds strengthModifier to damage', function(){
                var attacker = new ns.Character();
                var defender = new ns.Character();
                var startingHitPoints = defender.hitPoints();
                spyOn(Random, 'int').andCallFake(function(){return defender.armorClass();});

                attacker.strength(10);
                expect(attacker.strengthModifier()).toEqual(0);
                expect(attacker.attack(defender)).toBe(true);
                expect(defender.hitPoints()).toBe(startingHitPoints - 1);

                defender.hitPoints(startingHitPoints);
                attacker.strength(12);
                expect(attacker.strengthModifier()).toEqual(1);
                expect(attacker.attack(defender)).toBe(true);
                expect(defender.hitPoints()).toBe(startingHitPoints - 2);
            });

            it('adds double strengthModifier to damage on crit', function(){
                var attacker = new ns.Character();
                var defender = new ns.Character();
                var startingHitPoints = defender.hitPoints();
                spyOn(Random, 'int').andCallFake(function(){return 20;});

                attacker.strength(14);
                expect(attacker.strengthModifier()).toEqual(2);
                expect(attacker.attack(defender)).toBe(true);
                expect(defender.hitPoints()).toBe(startingHitPoints - 1 - (2*attacker.strengthModifier()));
            });

        });
    });

});
