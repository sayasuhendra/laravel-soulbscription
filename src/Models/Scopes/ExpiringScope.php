<?php

namespace LucasDotDev\Soulbscription\Models\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Support\Carbon;

class ExpiringScope implements Scope
{
    protected $extensions = [
        'Expire',
        'OnlyExpired',
        'WithExpired',
        'WithoutExpired',
    ];

    public function apply(Builder $builder, Model $model)
    {
        $builder->where('expired_at', '>', now());
    }

    public function extend(Builder $builder)
    {
        foreach ($this->extensions as $extension) {
            $this->{"add{$extension}"}($builder);
        }
    }

    protected function addWithExpired(Builder $builder)
    {
        $builder->macro('withExpired', function (Builder $builder, $withExpired = true) {
            if ($withExpired) {
                return $builder->withoutGlobalScope($this);
            }

            return $builder->withoutExpired();
        });
    }

    protected function addWithoutExpired(Builder $builder)
    {
        $builder->macro('withoutExpired', function (Builder $builder) {
            $builder->withoutGlobalScope($this)->where('expired_at', '>', now());

            return $builder;
        });
    }

    protected function addOnlyExpired(Builder $builder)
    {
        $builder->macro('onlyExpired', function (Builder $builder) {
            $builder->withoutGlobalScope($this)->where('expired_at', '<=', now());

            return $builder;
        });
    }

    protected function addExpire(Builder $builder)
    {
        $builder->macro('expire', function (Builder $builder, ?Carbon $expiration = null) {
            $builder->withExpired();

            if (is_null($expiration)) {
                $expiration = now();
            }

            return $builder->update(['expired_at' => $expiration]);
        });
    }
}
