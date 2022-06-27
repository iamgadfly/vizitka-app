@component('mail::message')
# Жалоба на визитку

Жалоба на визитку {{ $report->phoneNumber }} <br>
Причина: {{ $report->reason }}

@endcomponent
