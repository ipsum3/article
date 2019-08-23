<?php

namespace Ipsum\Article\app\Policies;


use Illuminate\Auth\Access\HandlesAuthorization;
use Ipsum\Admin\app\Models\Admin;
use Ipsum\Article\app\Models\Article;

class ArticlePolicy
{
    use HandlesAuthorization;

    public function before(Admin $user, $ability)
    {
        if ($user->isSuperAdmin()) {
            return true;
        }
    }

    public function viewAny(Admin $user)
    {
        if ($user->isAdmin()) {
            return true;
        }
    }

    public function create(Admin $user)
    {
        if ($user->isAdmin()) {
            return true;
        }
    }

    public function createSuperAdmin(Admin $user)
    {
        return false;
    }

    public function update(Admin $user, Article $model)
    {
        if (($user->isAdmin() and !$model->isSuperAdmin()) or $model->id == $user->id) {
            return true;
        }
    }

    public function delete(Admin $user, Article $model)
    {
        if ($user->isAdmin() and !$model->isSuperAdmin()) {
            return true;
        }
    }


}
