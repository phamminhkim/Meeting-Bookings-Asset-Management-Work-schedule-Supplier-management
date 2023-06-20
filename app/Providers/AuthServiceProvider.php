<?php

namespace App\Providers;

use App\Models\document\Document;
use App\Models\issue\Issue;
use App\Models\managerprice\PriceReq;
use App\Models\payment\Payrequest;
use App\Models\shared\Reminder;
use App\Models\tmdt\SaleOrders;
use App\Models\car\Car;
use App\Models\productivity\HraddMark;
use App\Models\productivity\Hrdayoff;
use App\Models\productivity\HrproductivityMark;
use App\Models\productivity\HrsalaryInfo;
use App\Models\shared\Shared;
use App\Models\work\workflow\runtime\WlworkflowDoc;
use App\Policies\DocumentPolicy;
use App\Policies\IssuePolicy;
use App\Policies\PayrequestPolicy;
use App\Policies\PricePolicy;
use App\Policies\ReminderPolicy;
use App\Policies\SalesOrderPolicy;
use App\Policies\CarPolicy;
use App\Policies\HrAddMarkPolicy;
use App\Policies\HrDayOffPolicy;
use App\Policies\HrRecordPolicy;
use App\Policies\HrSalaryInfoPolicy;
use App\Policies\SharedPolicy;
use App\Policies\WorkflowPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Laravel\Passport\Passport;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Model' => 'App\Policies\ModelPolicy',
        SaleOrders::class => SalesOrderPolicy::class,
        Payrequest::class => PayrequestPolicy::class,
        Reminder::class => ReminderPolicy::class,
        Document::class => DocumentPolicy::class,
        PriceReq::class => PricePolicy::class,
        Issue::class => IssuePolicy::class,
        Car::class => CarPolicy::class,
        Shared::class => SharedPolicy::class,
        WlworkflowDoc::class => WorkflowPolicy::class,
        HraddMark::class => HrAddMarkPolicy::class,
        HrsalaryInfo::class => HrSalaryInfoPolicy::class,
        Hrdayoff::class => HrDayOffPolicy::class,
        HrproductivityMark::class => HrRecordPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();
        Passport::routes();
        Passport::tokensExpireIn(now()->addDays(1));
        Passport::refreshTokensExpireIn(now()->addDays(1));
        Passport::personalAccessTokensExpireIn(now()->addMonths(6));


        Passport::personalAccessClientId(
            config('passport.personal_access_client.id')
        );

        Passport::personalAccessClientSecret(
            config('passport.personal_access_client.secret')
        );
        Gate::define('manage-systems', function ($user) {
            return $user->hasAnyRoles(['Administrator']);
        });
    }
}
