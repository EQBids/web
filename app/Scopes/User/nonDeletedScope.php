<?php
/**
 * Created by PhpStorm.
 * User: smith
 * Date: 3/15/18
 * Time: 5:32 PM
 */

namespace App\Scopes\User;


use App\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class nonDeletedScope implements Scope {

	function apply( Builder $builder, Model $model ) {
		$builder->whereNotIn($model->getTable().'.status',[User::STATUS_INACTIVE, User::STATUS_BANNED, User::STATUS_AWAY]);
	}

}