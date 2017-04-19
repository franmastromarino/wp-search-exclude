<?php

$I = new AcceptanceTester($scenario);
$I->wantTo('See that plugin is installed');
$I->amGoingTo('login as an administrator');
$I->loginAsAdmin();
$I->dontSee('ERROR');
$I->see('Dashboard', 'h1');
//$I->expect('search exclude plugin is installed');
//$I->amOnPluginsPage();
//$I->seePluginActivated('search-exclude');

//$I->expectTo('see search exclude option');
//$I->click('#menu-settings');
//$I->saveScreenshot();
//$I->seeElement( 'a[href="options-general.php?page=search_exclude"]' );

$existingPosts = $I->cli('post list --post_type="post" --format=ids');
$I->cli('post delete ' . $existingPosts);

$postTitle = 'Foo';
$I->cli("post create --post_title={$postTitle} --post_content=Foo --post_excerpt=Foo --post_status=publish");

$I->amOnPage('/?s=foo');
$I->seeElement(['css' => 'h2.entry-title a', 'text' => $postTitle]);

//$I->expectTo('see custom fields as an option');
//$I->see('Custom Fields');


/*
+ Delete all posts
+ Create some post
- See that I can find it in the search
- Exclude from search - with usual edit
- See that it so not shown in search
- Disable exclude from search
- See that it is visible again

- Same for page
- Same for custom post type

- Same with with edit
- Same with bulk edit
*/
