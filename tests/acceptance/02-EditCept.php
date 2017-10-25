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

    $tester->amOnPage("/wp-admin/post.php?post={$postId}&action=edit");
    $tester->seeCheckboxIsChecked('#sep_exclude');
    $tester->uncheckOption('#sep_exclude');
    $tester->click('#publish');
    $tester->seePostInSearch($postTitle);

    // Test bulk edit
    $tester->amOnPage("/wp-admin/edit.php?post_type={$postType}");
    $tester->seeElement(['css' => "#search-exclude-${postId}", 'text' => 'Visible']);
    $tester->checkOption("#cb-select-${postId}");
    $tester->selectOption('#bulk-action-selector-top', 'Edit');
    $tester->click('#doaction');
    $tester->selectOption('#posts-filter select[name="sep[exclude]"]', 'Hide');
    $tester->click('#bulk_edit');
    $tester->amOnPage("/wp-admin/edit.php?post_type={$postType}");
    $tester->seeElement(['css' => "#search-exclude-${postId}", 'text' => 'Hidden']);
    $tester->dontSeePostInSearch($postTitle);

    $tester->amOnPage("/wp-admin/edit.php?post_type={$postType}");
    $tester->checkOption("#cb-select-${postId}");
    $tester->selectOption('#bulk-action-selector-top', 'Edit');
    $tester->click('#doaction');
    $tester->selectOption('#posts-filter select[name="sep[exclude]"]', 'Show');
    $tester->click('#bulk_edit');
    $tester->amOnPage("/wp-admin/edit.php?post_type={$postType}");
    $tester->seeElement(['css' => "#search-exclude-${postId}", 'text' => 'Visible']);
    $tester->seePostInSearch($postTitle);

    // Test quick edit
    $tester->amOnPage("/wp-admin/edit.php?post_type={$postType}");
    $tester->moveMouseOver("#post-{$postId}");
    $tester->click("#post-{$postId} a.editinline");
    $tester->checkOption("#sep_exclude");
    $tester->click(".inline-edit-save button.save");
    $tester->waitForElementVisible(['css' => "#search-exclude-${postId}", 'text' => 'Hidden'], 5);
    $tester->seeElement(['css' => "#search-exclude-${postId}", 'text' => 'Hidden']);
    $tester->dontSeePostInSearch($postTitle);

    $tester->amOnPage("/wp-admin/edit.php?post_type={$postType}");
    $tester->moveMouseOver("#post-{$postId}");
    $tester->click("#post-{$postId} a.editinline");
    $tester->uncheckOption("#sep_exclude");
    $tester->click(".inline-edit-save button.save");
    $tester->waitForElementVisible(['css' => "#search-exclude-${postId}", 'text' => 'Visible'], 5);
    $tester->seeElement(['css' => "#search-exclude-${postId}", 'text' => 'Visible']);
    $tester->seePostInSearch($postTitle);
}
/*
- Page with the list of excluded items
*/
