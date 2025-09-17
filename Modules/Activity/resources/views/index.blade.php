<x-layouts.master title="فعالیت ها">

	<div class="page-header">
		<x-breadcrumb>
			<x-breadcrumb-item title="فعالیت ها" />
		</x-breadcrumb>
	</div>

	<x-card title="فعالیت ها">
		<x-table :pagination="$activities">
			<x-slot name="thead">
				<tr>
					<th>ردیف</th>
					<th>ادمین</th>
					<th>توضیحات</th>
					<th>شناسه لاگ</th>
					<th>تاریخ</th>
					<th>ساعت</th>
				</tr>
			</x-slot>
			<x-slot name="tbody">
				@forelse ($activities as $activity)
					<tr>
						<td class="font-weight-bold">{{ $loop->iteration }}</td>
						<td>{{ $activity->causer->name }}</td>
						<td style="white-space: wrap;">{{ $activity->description }}</td>
						<td>{{ $activity->id }}</td>
						<td><x-jalali-date :date="$activity->created_at" format="date" /></td>
						<td><x-jalali-date :date="$activity->created_at" format="time" /></td>
					</tr>
				@empty
					<x-no-data :colspan="6" />
				@endforelse
			</x-slot>
		</x-table>
	</x-card>

</x-layouts.master>