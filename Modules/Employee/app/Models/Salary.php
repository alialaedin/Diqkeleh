<?php

namespace Modules\Employee\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Core\Models\BaseModel;

class Salary extends BaseModel
{
	protected $fillable = ['employee_id', 'amount', 'overtime', 'description', 'paid_at'];
	protected $with = ['employee'];

	public function employee(): BelongsTo
	{
		return $this->belongsTo(Employee::class);
	}
}
