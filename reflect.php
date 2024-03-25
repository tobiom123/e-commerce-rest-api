<?php

final class ReflectionTest {
    private $mySecret = "I have 99 problems. This isn't one of them.\n";
}


$reflection = new ReflectionClass('ReflectionTest');
$mySecretProperty = $reflection->getProperty('mySecret');
echo $mySecretProperty->getValue($reflection->newInstance());