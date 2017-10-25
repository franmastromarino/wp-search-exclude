<?php

$tester = new AcceptanceTester($scenario);
$tester->wantTo('Test post editing');
$tester->loginAdmin();

// TODO: Test custom post type
$postTypes = ['post'/*, 'page'*/];

foreach ($postTypes as $postType) {
    // Delete all posts
    $tester->deletePosts($postType);

    // Create new random post
    $postTitle = 'Foo' . uniqid();
    $postId = $tester->createPost($postType, $postTitle);

    // Test regular post edit
    $tester->amOnPage("/wp-admin/post.php?post={$postId}&action=edit");
    $tester->dontSeeCheckboxIsChecked('#sep_exclude');
    $tester->checkOption('#sep_exclude');
    $tester->click('#publish');
    $tester->dontSeePostInSearch($postTitle);

    $tester->amOnPage('/wp-admin/options-general.php?page=search_exclude');
    $tester->checkOption('#sep_exclude_from_search_engines');
    $tester->click('#search_exclude_submit');
    $tester->amOnPage('/?p=' . $postId);
    $tester->seeInSource('name="robots"');

    $tester->amOnPage('/wp-admin/options-general.php?page=search_exclude');
    $tester->uncheckOption('#sep_exclude_from_search_engines');
    $tester->click('#search_exclude_submit');
    $tester->amOnPage('/?p=' . $postId);
    $tester->dontSeeInSource('name="robots"');
}