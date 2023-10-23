<?php

namespace Baro\PipelineQueryCollection\Tests\TestClasses\Models;

use Baro\PipelineQueryCollection\BitwiseFilter;
use Baro\PipelineQueryCollection\BooleanFilter;
use Baro\PipelineQueryCollection\Concerns\Filterable;
use Baro\PipelineQueryCollection\Concerns\Sortable;
use Baro\PipelineQueryCollection\DateFromFilter;
use Baro\PipelineQueryCollection\DateToFilter;
use Baro\PipelineQueryCollection\ExactFilter;
use Baro\PipelineQueryCollection\RelationFilter;
use Baro\PipelineQueryCollection\RelativeFilter;
use Baro\PipelineQueryCollection\ScopeFilter;
use Baro\PipelineQueryCollection\Sort;
use Baro\PipelineQueryCollection\TrashFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TestModel extends Model
{
    use Filterable;
    use HasFactory;
    use SoftDeletes;
    use Sortable;

    protected $guarded = [];

    protected function getFilters()
    {
        return [
            BitwiseFilter::make('type_flag'),
            BooleanFilter::make('is_visible'),
            DateFromFilter::make('created_at'),
            DateToFilter::make('created_at'),
            ExactFilter::make('updated_at'),
            RelationFilter::make('belongs_to_related_models', 'id'),
            RelationFilter::make('belongs_to_many_related_models', 'id'),
            RelativeFilter::make('name'),
            ScopeFilter::make('search'),
            TrashFilter::make(),
        ];
    }

    protected function getSorts()
    {
        return [
            Sort::make(),
        ];
    }

    public function belongsToRelatedModels()
    {
        return $this->belongsTo(RelatedModel::class, 'related_model_id');
    }

    public function belongsToManyRelatedModels()
    {
        return $this->belongsToMany(RelatedModel::class, 'pivot_table');
    }

    public function scopeSearch(Builder $query, string $search)
    {
        return $query->where(
            fn (Builder $query) => $query
                ->where('name', 'like', "%{$search}%")
                ->orWhere('id', $search)
        );
    }
}
