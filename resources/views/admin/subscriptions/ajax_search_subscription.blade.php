@if (isset($data) && !empty($data) && count($data) > 0)

    <table class="table table-hover table-bordered align-middle text-center">

        <thead class="table-primary">
            <tr>

                <th>المعرف</th>

                <th>المؤسسة</th>

                <th>الباقة</th>

                <th>نوع الاشتراك</th>

                <th>بداية الاشتراك</th>

                <th>نهاية الاشتراك</th>

                <th>الفترة التجريبية</th>

                <th>انتهاء الفترة التجريبية</th>

                <th>التجديد التلقائي</th>

                <th>الحالة</th>

                <th>الإجراءات</th>

            </tr>
        </thead>


        <tbody>

            @foreach ($data as $info)
                <tr>


                    <td>
                        {{ $info->subscription_id }}
                    </td>



                    <td>
                        {{ $info->organization->name ?? '-' }}
                    </td>



                    <td>
                        {{ $info->package->name ?? '-' }}
                    </td>



                    <td>
                        <span class="badge bg-secondary">
                            {{ $info->subscription_type_label }}
                        </span>
                    </td>



                    <td>
                        {{ $info->start_date?->format('Y-m-d') ?? '-' }}
                    </td>



                    <td>
                        {{ $info->end_date?->format('Y-m-d') ?? '-' }}
                    </td>


                    <td>
                        {{ $info->subscription_type === 'paid' ? 'لا يوجد' : $info->trial_period_label }}
                    </td>


                    <td>
                        {{ $info->trial_end_date?->format('Y-m-d') ?? '-' }}
                    </td>



                    <td>

                        @if ($info->auto_renew)
                            <span class="badge bg-success">
                                نعم
                            </span>
                        @else
                            <span class="badge bg-secondary">
                                لا
                            </span>
                        @endif

                    </td>




                    <td>

                        @php

                            $statusClass = match ($info->status) {
                                'active' => 'bg-success',

                                'pending' => 'bg-warning',

                                'expired' => 'bg-danger',

                                'cancelled' => 'bg-dark',

                                default => 'bg-secondary',
                            };

                        @endphp


                        <span class="badge {{ $statusClass }}">

                            {{ $info->status_label }}

                        </span>


                    </td>



                    <td>


                        <x-button
                        :show-route="route('admin.subscriptions.show', $info->subscription_id)"
                        :edit-route="in_array($info->subscription_type, ['trial'])
                            && in_array($info->status, ['active', 'expired'])
                                ? route('admin.subscriptions.edit', $info->subscription_id)
                                : null"
                        :delete-route="route('admin.subscriptions.delete', $info->subscription_id)"
                        :deleted-id="$info->subscription_id"
                    />


                    </td>


                </tr>
            @endforeach


        </tbody>

    </table>



    <div class="d-flex justify-content-between align-items-center mt-3">


        <div>

            إظهار
            {{ $data->firstItem() }}
            إلى
            {{ $data->lastItem() }}
            من أصل
            {{ $data->total() }}
            اشتراك

        </div>



        <div id="ajax_search">

            {{ $data->links() }}

        </div>


    </div>
@else
    <p class="text-center text-danger mt-3">

        لا توجد بيانات لعرضها

    </p>


@endif
