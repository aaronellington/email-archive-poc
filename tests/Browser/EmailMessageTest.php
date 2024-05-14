<?php

use App\Models\User;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseTruncation;

uses(DatabaseTruncation::class);

test('Happy Path', function () {

    $this->browse(function (Browser $browser) {
        $user = User::factory()->create();

        $browser
            ->loginAs($user->id)
            // Test the upload
            ->visit('/')
            ->assertSee('Upload')
            ->attach("#upload0", __DIR__."/../TestFiles/script_injection.eml")
            ->waitFor("#submit-form",2)
            ->click("#submit-form")
            ->waitForText("COMPLETE")
            // Navigate to the view page
            ->click(".row-action-menu")
            ->waitFor('.row-view-button')
            ->click(".row-view-button")
            ->waitForText("PLEASE PROCEED WITH CAUTION.")
            // Confirm iframe is sandboxed
            ->withinFrame("iframe", function(Browser $iframe) {
                $iframe
                    ->assertDontSee("you shall not pass")
                ;
            })
            // Make sure the js we expected was actually in the HTML
            // and the sandbox setting actually prevented something
            // We do this by directly viewing the html NOT
            // in the sandboxed iframe
            ->visit($browser->driver->getCurrentURL().".html?override=1")
            ->waitForText("you shall not pass")
        ;
    });
});
