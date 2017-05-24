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
                attacker.attack(defender);

                //Assert
                expect(defender.hitPoints()).toBe(-1);
                expect(defender.isDead()).toBe(true);
            });
        });
    });

});
