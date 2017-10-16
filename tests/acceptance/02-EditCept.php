<?php

$I = new AcceptanceTester($scenario);
$I->wantTo('Test post editing');
$I->loginAdmin();

//$I->saveScreenshot();

// TODO: Test custom post type
$postTypes = ['post'/*, 'page'*/];

foreach ($postTypes as $postType) {

    // Delete all posts
    $I->deletePosts($postType);

    // Create new random post
    $postTitle = 'Foo' . uniqid();
    $postId = $I->createPost($postType, $postTitle);

    // See post in Search
    $I->amOnPage('/');
    $I->submitForm('.search-form', ['s' => $postTitle]);
    $I->seeElement(['css' => 'h2.entry-title a', 'text' => $postTitle]);

    // Test regular post edit
    $I->amOnPage("/wp-admin/post.php?post={$postId}&action=edit");
    $I->dontSeeCheckboxIsChecked('#sep_exclude');
    $I->checkOption('#sep_exclude');
    $I->click('#publish');
    $I->dontSeePostInSearch($postTitle);

    $I->amOnPage("/wp-admin/post.php?post={$postId}&action=edit");
    $I->seeCheckboxIsChecked('#sep_exclude');
    $I->uncheckOption('#sep_exclude');
    $I->click('#publish');
    $I->seePostInSearch($postTitle);

    // Test bulk edit
    $I->amOnPage("/wp-admin/edit.php?post_type={$postType}");
    $I->seeElement(['css' => "#search-exclude-${postId}", 'text' => 'Visible']);
    $I->checkOption("#cb-select-${postId}");
    $I->selectOption('#bulk-action-selector-top', 'Edit');
    $I->click('#doaction');
    $I->selectOption('#posts-filter select[name="sep[exclude]"]', 'Hide');
    $I->click('#bulk_edit');
    $I->seeElement(['css' => "#search-exclude-${postId}", 'text' => 'Hidden']);
    $I->dontSeePostInSearch($postTitle);

    $I->amOnPage("/wp-admin/edit.php?post_type={$postType}");
    $I->checkOption("#cb-select-${postId}");
    $I->selectOption('#bulk-action-selector-top', 'Edit');
    $I->click('#doaction');
    $I->selectOption('#posts-filter select[name="sep[exclude]"]', 'Show');
    $I->click('#bulk_edit');
    $I->seeElement(['css' => "#search-exclude-${postId}", 'text' => 'Visible']);
    $I->seePostInSearch($postTitle);

    // Test quick edit
    $I->amOnPage("/wp-admin/edit.php?post_type={$postType}");
    $I->moveMouseOver("#post-{$postId}");
    $I->click("#post-{$postId} a.editinline");
    $I->checkOption("#sep_exclude");
    $I->click(".inline-edit-save button.save");
    $I->waitForElementVisible(['css' => "#search-exclude-${postId}", 'text' => 'Hidden'], 5);
    $I->seeElement(['css' => "#search-exclude-${postId}", 'text' => 'Hidden']);
    $I->dontSeePostInSearch($postTitle);

    $I->amOnPage("/wp-admin/edit.php?post_type={$postType}");
    $I->moveMouseOver("#post-{$postId}");
    $I->click("#post-{$postId} a.editinline");
    $I->uncheckOption("#sep_exclude");
    $I->click(".inline-edit-save button.save");
    $I->waitForElementVisible(['css' => "#search-exclude-${postId}", 'text' => 'Visible'], 5);
    $I->seeElement(['css' => "#search-exclude-${postId}", 'text' => 'Visible']);
    $I->seePostInSearch($postTitle);
}
/*
- Page with the list of excluded items
*/
