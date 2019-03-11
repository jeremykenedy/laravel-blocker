<?php

namespace jeremykenedy\LaravelBlocker\App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BlockedItem extends Model
{
    use SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table;

    /**
     * The connection name for the model.
     *
     * @var string
     */
    protected $connection;

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = true;

    /**
     * The attributes that are not mass assignable.
     *
     * @var array
     */
    protected $guarded = [
        'id',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    /**
     * Fillable fields.
     *
     * @var array
     */
    protected $fillable = [
        'typeId',
        'value',
        'note',
        'userId',
    ];

    protected $casts = [
        'typeId'    => 'integer',
        'value'     => 'string',
        'note'      => 'string',
        'userId'    => 'integer',
    ];

    /**
     * Create a new instance to set the table and connection.
     *
     * @return void
     */
    public function __construct($attributes = [])
    {
        parent::__construct($attributes);
        $this->connection = config('laravelblocker.blockerDatabaseConnection');
        $this->table = config('laravelblocker.blockerDatabaseTable');
    }

    /**
     * Get the database connection.
     */
    public function getConnectionName()
    {
        return $this->connection;
    }

    /**
     * Get the database table.
     */
    public function getTableName()
    {
        return $this->table;
    }

    /**
     * The one-to-one relationship between pages and tags.
     *
     * @return hasOne
     */
    public function blockedType()
    {
        return $this->belongsTo(BlockedType::class, 'typeId');
    }
}
