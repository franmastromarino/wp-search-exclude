<?php


/**
 * Inherited Methods
 * @method void wantToTest($text)
 * @method void wantTo($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method \Codeception\Lib\Friend haveFriend($name, $actorClass = NULL)
 *
 * @SuppressWarnings(PHPMD)
*/
class AcceptanceTester extends \Codeception\Actor
{
    use _generated\AcceptanceTesterActions;

    public function loginAdmin()
    {
        $I = $this;
        $I->amOnPage('/');
        // if snapshot exists - skipping login
        if ($I->loadSessionSnapshot('login')) {
            return;
        }
        // logging in
        $I->amGoingTo('login as an administrator');
        $I->loginAsAdmin();
        $I->dontSee('ERROR');
        $I->see('Dashboard', 'h1');
        // saving snapshot
        $I->saveSessionSnapshot('login');
    }

    public function seePostInSearch($postTitle)
    {
        $I = $this;
        $I->amOnPage('/?s=' . $postTitle);
        $I->seeElement(['css' => 'h2.entry-title a', 'text' => $postTitle]);
    }

    public function dontSeePostInSearch($postTitle)
    {
        $I = $this;
        $I->amOnPage('/?s=' . $postTitle);
        $I->dontSeeElement(['css' => 'h2.entry-title a', 'text' => $postTitle]);
    }

    public function deletePosts($type)
    {
        $I = $this;
        $existingPosts = $I->cli("post list --post_type=\"{$type}\" --format=ids");
        if (is_array($existingPosts)) {
            $existingPosts = implode(', ', $existingPosts);
        }
        $I->cli('post delete ' . $existingPosts);
    }

    public function createPost($type, $title)
    {
        $I = $this;
        $postId = $I->cli("post create --post_title={$title} --post_type=\"{$type}\" --post_content=Foo --post_excerpt=Foo --post_status=publish --porcelain");

        return $postId;
    }
}
