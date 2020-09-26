<?php

declare(strict_types=1);

use Doctrine\ORM\Mapping\Builder\ClassMetadataBuilder;

$builder = new ClassMetadataBuilder($metadata);

$builder->createField('route', 'string')->makePrimaryKey()->build();
$builder->createField('method', 'string')->makePrimaryKey()->build();
$builder->createField('visitCount', 'integer')->option('unsigned', true)->build();
$builder->createField('controller', 'string')->nullable(true)->build();
$builder->addField('lastUpdatedAt', 'datetime');
