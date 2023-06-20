<?php

namespace Tests\Unit\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

//use PHPUnit\Framework\TestCase;
use Tests\TestCase;
use App\Models\shared\Department;
use App\Models\shared\Company;
use App\Models\shared\Comment;
use App\Models\shared\File;
use App\Models\shared\Team;

use App\Models\service\Ticket;
use App\Models\service\ServiceCategory;

use App\User;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;


class ModelTest extends TestCase
{

    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function testExample()
    {
        $this->assertTrue(true);
    }


    public function test_company_has_many_department()
    {
        $this->assertHasMany(Department::class, 'company_id', new Company , 'departments');
    }
    public function test_department_has_one_conpany()
    {
        $this->assertHasOne(Company::class,   new Department , 'company');
    }
    public function test_ticket_has_many_comments()
    {
        $this->assertMorphMany(Comment::class, '', new Ticket , 'comments','commentable');
    }

    public function test_ticket_has_many_files()
    {
        $this->assertMorphMany(File::class, '', new Ticket , 'files','fileable');
    }

    public function test_ticket_belongsTo_one_service_category()
    {
        $this->assertBelongsTo(ServiceCategory::class, 'service_category_id','id', new Ticket , 'category');
    }
    public function test_ticket_belongsTo_one_company()
    {
        $this->assertBelongsTo(Company::class, 'company_id','id', new Ticket , 'company');
    }

    public function test_ticket_belongsTo_one_create_by()
    {
        $this->assertBelongsTo(User::class, 'create_by','id', new Ticket , 'createBy');
    }
    public function test_ticket_belongsTo_one_request_by()
    {
        $this->assertBelongsTo(User::class, 'request_by','id', new Ticket , 'requestBy');
    }
    public function test_ticket_belongsTo_one_team()
    {
        $this->assertBelongsTo(Team::class, 'team_id','id', new Ticket , 'team');
    }

    //Các hàm check
    protected function assertHasMany($related, $foreignKey, $model, $relationName)
    {
        $relation = $model->$relationName();
       $this->assertInstanceOf(HasMany::class, $relation, 'Relation is wrong');
       $this->assertInstanceOf($related, $relation->getRelated(), 'Related model is wrong');
        $this->assertEquals($foreignKey, $relation->getForeignKeyName(), 'Foreign key is wrong');
    }
    protected function assertHasOne($related,   $model, $relationName)
    {
        $relation = $model->$relationName();
       $this->assertInstanceOf(HasOne::class, $relation, 'Relation is wrong');
       $this->assertInstanceOf($related, $relation->getRelated(), 'Related model is wrong');

    }

    protected function assertBelongsTo($related, $foreignKey, $ownerKey, $model, $relationName)
    {
        $relation = $model->$relationName();

        $this->assertInstanceOf(BelongsTo::class, $relation, 'Relation is wrong');
        $this->assertInstanceOf($related, $relation->getRelated(), 'Related model is wrong');
        $this->assertEquals($ownerKey, $relation->getOwnerKeyName(), 'Owner key is wrong');
        $this->assertEquals($foreignKey, $relation->getForeignKeyName(), 'Foreign key is wrong');
    }

    protected function assertMorphMany($related, $foreignKey, $model, $relationName,$ablename)
    {
        $relation = $model->$relationName();
       $this->assertInstanceOf(MorphMany::class, $relation, 'Relation is wrong');
       $this->assertInstanceOf($related, $relation->getRelated(), 'Related model is wrong');
       $this->assertEquals($ablename . '_type', $relation->getMorphType(), $ablename.'_type is wrong');
       $this->assertEquals($ablename . '_id', $relation->getForeignKeyName(),$ablename.'_id is wrong');
    }


    // public function assertMorphMany($relation, $class)
    // {
    //     $this->assertRelationship($relation, $class, 'morphMany');
    // }
    public function assertBelongsToMany($parent, $child)
    {
        // $this->assertRelationship($parent, $child, 'belongsToMany');
    }
}
