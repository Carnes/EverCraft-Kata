<?php
include "testInterface.php";
set_include_path(dirname(__FILE__)."/../main");

function includeDirectory($rootDir){
    $files = scandir($rootDir);
    if($files===false)
        return;
    foreach($files as $file) {
        if($file != "." && $file != ".." && is_dir($file))
            includeDirectory($rootDir."/".$file);
        else{
            $pos = stripos($file, "_tests.php");
            if($pos!==false)
                include($rootDir."/".$file);
        }
    }
}

function includeAllTests(){
    includeDirectory(".");
}

class testTracker{
    public static $failedTests = array();
    public static $executedTests = array();
    public static $currentTest = "";
}

function assertHandler($file, $line, $code, $desc = null)
{
    $testName = testTracker::$currentTest;
    @testTracker::$failedTests[$testName]++;
    if(testTracker::$failedTests[$testName]==1)
        echo "Test: $testName\n";
    echo "Assert failed: $file:$line: $code";
    if ($desc) {
        echo ": $desc";
    }
    echo "\n";
    return true;
}

assert_options(ASSERT_WARNING, 0);
assert_options(ASSERT_CALLBACK, 'assertHandler');

$nonTestClasses = get_declared_classes();
includeAllTests();
$testClassNames = array_diff(get_declared_classes(), $nonTestClasses);

foreach($testClassNames as $testClassName)
{
    $testClassReflection = new ReflectionClass($testClassName);
    if(!$testClassReflection->implementsInterface("testInterface"))
        continue;
    $testMethods = $testClassReflection->getMethods(ReflectionMethod::IS_PUBLIC);
    $testClass = new $testClassName();

    foreach($testMethods as $method)
    {
        if($method->name=="initialize")
            continue;
        if(!isset(testTracker::$executedTests[$method->name]))
            testTracker::$executedTests[$method->name] = 0;
        testTracker::$executedTests[$method->name]++;
        testTracker::$currentTest = $method->name;
        $testClass->initialize();
        $method->invoke($testClass);
    };
}
$totalTestCount = count(testTracker::$executedTests);
$failedTestCount = count(testTracker::$failedTests);
$successfulTestCount = $totalTestCount - $failedTestCount;
echo "\nTesting Results:\n";
echo $successfulTestCount." successful\n";
echo $failedTestCount." failed\n";
echo $totalTestCount." total\n";
