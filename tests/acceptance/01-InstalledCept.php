<?php

$I = new AcceptanceTester($scenario);
$I->wantTo('See that plugin is installed');
$I->loginAdmin();
$I->expect('search exclude plugin is installed');
$I->amOnPluginsPage();
$I->seePluginActivated('search-exclude');

//$I->expectTo('see search exclude option');
////$I->click('#menu-settings');
////$I->saveScreenshot();
////$I->seeElement( 'a[href="options-general.php?page=search_exclude"]' );
