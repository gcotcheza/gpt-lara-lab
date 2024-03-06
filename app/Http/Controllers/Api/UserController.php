<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\AllowedFilter;
use Illuminate\Http\Resources\Json\ResourceCollection;

class UserController extends Controller
{
    /**
     * Get all users.
     */
    public function index(Request $request): ResourceCollection
    {
        $limit = $request->get('limit', 15);

        $users = QueryBuilder::for(User::class)
            ->allowedFields(['first_name', 'last_name', 'email'])
            ->allowedFilters(
                AllowedFilter::scope('wildcard_search'),
            );

        return UserResource::collection(
            $limit === 0 ? $users->get() : $users->paginate($limit)
        );
    }
}
