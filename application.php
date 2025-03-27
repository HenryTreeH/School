#!/usr/bin/env php
<?php

require __DIR__ . '/vendor/autoload.php';

use Symfony\Component\Console\Application;
use App\Commands\ScrapePage;
use App\Commands\HelloWorld;

// Create the application instance
$application = new Application();

// Add commands
$application->add(new ScrapePage());
$application->add(new HelloWorld());

// Run the application
$application->run();
