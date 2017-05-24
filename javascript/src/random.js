var Random = (function(){
    var self = {};

    var nextRandomNumber = function (){
        var hi = this.seed / this.Q;
        var lo = this.seed % this.Q;
        var test = this.A * lo - this.R * hi;
        if(test > 0){
            this.seed = test;
        } else {
            this.seed = test + this.M;
        }
        return (this.seed * this.oneOverM);
    };

    var RandomNumberGenerator = function(){
        var d = new Date();
        this.seed = 2345678901 + (d.getSeconds() * 0xFFFFFF) + (d.getMinutes() * 0xFFFF);
        this.A = 48271;
        this.M = 2147483647;
        this.Q = this.M / this.A;
        this.R = this.M % this.A;
        this.oneOverM = 1.0 / this.M;
        this.next = nextRandomNumber;
        return this;
    };

    var rand = new RandomNumberGenerator();
    var createRandomNumber = function(Min, Max){
        return Math.round((Max-Min) * rand.next() + Min);
    };

    self.int = function(max){
        //return Math.floor(Math.random() * max);
        return Math.floor(createRandomNumber(0,max));
    };

    self.d20 = function(){
        return self.int(20);
    };

    return self;
})();
