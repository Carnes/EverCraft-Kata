<?php
include ("../main/main.php");

class main_Tests implements testInterface
{
    public function initialize()
    {
        @ob_end_flush();
        ob_start();
    }

    public function testSomethingTrue(){
        //Arrange
        $expectedGreeting = "test";
        $main = new main($expectedGreeting);

        //Act
        $main->sayGreeting();
        $output = ob_get_clean();

        //Assert
        assert("'$output' == '$expectedGreeting'");
    }

    public function testSomethingFalse(){
        //Arrange
        $expectedGreeting = "test";
        $main = new main($expectedGreeting);

        //Act
        $main->sayGreeting();
        $output = ob_get_clean();

        //Assert
        assert("'$output' == 'something random'");
    }

    public function exampleBoolean(){
        assert(true);
        assert(1!==false);
    }
}