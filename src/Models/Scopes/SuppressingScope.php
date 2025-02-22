<?php

namespace LucasDotDev\Soulbscription\Models\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Support\Carbon;

class SuppressingScope implements Scope
{
    protected $extensions = [
        'Suppress',
        'OnlySuppressed',
        'WithSuppressed',
        'WithoutSuppressed',
    ];

    public function apply(Builder $builder, Model $model)
    {
        $builder->whereNull('suppressed_at');
    }

    public function extend(Builder $builder)
    {
        foreach ($this->extensions as $extension) {
            $this->{"add{$extension}"}($builder);
        }
    }

    protected function addWithSuppressed(Builder $builder)
    {
        $builder->macro('withSuppressed', function (Builder $builder, $withSuppressed = true) {
            if ($withSuppressed) {
                return $builder->withoutGlobalScope($this);
            }

            return $builder->withoutSuppressed();
        });
    }

    protected function addWithoutSuppressed(Builder $builder)
    {
        $builder->macro('withoutSuppressed', function (Builder $builder) {
            $builder->withoutGlobalScope($this)->whereNull('suppressed_at');

            return $builder;
        });
    }

    protected function addOnlySuppressed(Builder $builder)
    {
        $builder->macro('onlySuppressed', function (Builder $builder) {
            $builder->withoutGlobalScope($this)->whereNotNull();

            return $builder;
        });
    }

    protected function addSuppress(Builder $builder)
    {
        $builder->macro('suppress', function (Builder $builder, ?Carbon $suppressation = null) {
            $builder->withoutSuppressed();

            $suppressation = $suppressation ?: now();

            return $builder->update(['suppressed_at' => $suppressation]);
        });
    }
}
